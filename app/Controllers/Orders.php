<?php

namespace App\Controllers;

use App\Models\OrderModel;
use App\Models\OrderItemModel;
use App\Models\CustomerModel;
use App\Models\CouponModel;
use App\Models\ProductModel;
use App\Models\StockBatchModel;
use App\Models\StockMovementModel;
use App\Models\BatchProcurementRuleModel;
use App\Models\ProcurementRuleModel;
use App\Models\InvoiceModel;
use CodeIgniter\HTTP\RedirectResponse;
use CodeIgniter\HTTP\ResponseInterface;

class Orders extends BaseController
{
    protected OrderModel $orderModel;
    protected OrderItemModel $orderItemModel;
    protected CustomerModel $customerModel;
    protected CouponModel $couponModel;
    protected ProductModel $productModel;
    protected StockBatchModel $stockBatchModel;
    protected StockMovementModel $stockMovementModel;
    protected BatchProcurementRuleModel $batchRuleModel;
    protected ProcurementRuleModel $procurementRuleModel;
    protected InvoiceModel $invoiceModel;

    public function __construct()
    {
        $this->orderModel          = model(OrderModel::class);
        $this->orderItemModel      = model(OrderItemModel::class);
        $this->customerModel       = model(CustomerModel::class);
        $this->couponModel         = model(CouponModel::class);
        $this->productModel        = model(ProductModel::class);
        $this->stockBatchModel     = model(StockBatchModel::class);
        $this->stockMovementModel  = model(StockMovementModel::class);
        $this->batchRuleModel      = model(BatchProcurementRuleModel::class);
        $this->procurementRuleModel = model(ProcurementRuleModel::class);
        $this->invoiceModel        = model(InvoiceModel::class);
    }

    /**
     * List orders.
     */
    public function index(): string
    {
        helper('form');
        $sortCol   = $this->request->getGet('sort');
        $sortOrder = strtolower((string) $this->request->getGet('order')) === 'desc' ? 'desc' : 'asc';
        $allowedSort = ['order_number', 'customer_name', 'customer_phone', 'status', 'total_amount', 'created_at'];
        $builder = $this->orderModel->builder()
            ->select('orders.*, customers.name AS customer_name, customers.phone AS customer_phone, invoices.id AS invoice_id')
            ->join('customers', 'customers.id = orders.customer_id', 'left')
            ->join('invoices', 'invoices.order_id = orders.id', 'left');
        if ($sortCol !== null && in_array($sortCol, $allowedSort, true)) {
            $orderCol = match ($sortCol) {
                'customer_name' => 'customers.name',
                'customer_phone' => 'customers.phone',
                default => 'orders.' . $sortCol,
            };
            $builder->orderBy($orderCol, $sortOrder);
        } else {
            $builder->orderBy('orders.created_at', 'desc');
        }
        $orders = $builder->get()->getResultArray();

        $data = [
            'pageTitle' => 'Orders - Gameshala ERP',
            'orders'    => $orders,
            'sort'      => $sortCol ?? 'created_at',
            'order'     => $sortCol !== null && in_array($sortCol, $allowedSort, true) ? $sortOrder : 'desc',
        ];

        return view('layout/main', [
            'pageTitle' => $data['pageTitle'],
            'content'   => view('sales/orders/index', $data),
        ]);
    }

    /**
     * Create order form (simple screen: product search, customer by phone, coupon, line items).
     */
    public function create(): string
    {
        helper('form');
        $data = [
            'pageTitle' => 'Create Order - Gameshala ERP',
        ];

        return view('layout/main', [
            'pageTitle' => $data['pageTitle'],
            'content'   => view('sales/orders/create', $data),
        ]);
    }

    /**
     * Store new order: create order + items, deduct stock, create stock_movements.
     */
    public function store(): RedirectResponse
    {
        $customerId = $this->request->getPost('customer_id');
        $couponCode = trim((string) $this->request->getPost('coupon_code'));
        $items      = $this->request->getPost('items'); // array of [product_id, batch_id, qty, unit_price, unit_cost_snapshot]

        if (empty($customerId) || empty($items) || ! is_array($items)) {
            return redirect()->back()->withInput()->with('error', 'Customer and at least one line item are required.');
        }

        $customer = $this->customerModel->find((int) $customerId);
        if (! $customer) {
            return redirect()->back()->withInput()->with('error', 'Customer not found.');
        }

        $couponId        = null;
        $discountAmount  = 0.0;
        $subtotal       = 0.0;
        $validItems     = [];

        foreach ($items as $row) {
            $productId = (int) ($row['product_id'] ?? 0);
            $batchId   = (int) ($row['batch_id'] ?? 0);
            $qty       = (int) ($row['qty'] ?? 0);
            $unitPrice    = (float) ($row['unit_price'] ?? 0);
            $listingPrice = isset($row['listing_price_snapshot']) ? (float) $row['listing_price_snapshot'] : $unitPrice;
            $unitCost     = (float) ($row['unit_cost_snapshot'] ?? 0);
            if ($productId <= 0 || $batchId <= 0 || $qty <= 0 || $unitPrice <= 0) {
                continue;
            }
            $batch = $this->stockBatchModel->find($batchId);
            if (! $batch || (int) $batch['product_id'] !== $productId || (int) $batch['remaining_qty'] < $qty) {
                return redirect()->back()->withInput()->with('error', 'Invalid or insufficient stock for one or more items.');
            }
            $lineTotal = round($unitPrice * $qty, 2);
            $subtotal += $lineTotal;
            $lineProductDiscount = ($listingPrice > $unitPrice) ? round(($listingPrice - $unitPrice) * $qty, 2) : 0.0;
            $validItems[] = [
                'product_id'             => $productId,
                'stock_batch_id'         => $batchId,
                'qty'                    => $qty,
                'unit_price'             => $unitPrice,
                'listing_price_snapshot' => $listingPrice,
                'unit_cost_snapshot'    => $unitCost,
                'discount_amount'        => $lineProductDiscount,
                'line_total'             => $lineTotal,
            ];
        }

        if (empty($validItems)) {
            return redirect()->back()->withInput()->with('error', 'Add at least one product with valid quantity.');
        }

        $subtotal = round($subtotal, 2);

        if ($couponCode !== '') {
            $coupon = $this->couponModel->findByCode($couponCode);
            $couponResult = $this->validateCouponForSubtotal($coupon, $subtotal);
            if ($couponResult['valid']) {
                $couponId       = (int) $coupon['id'];
                $discountAmount = $couponResult['discount_amount'];
            }
        }

        $taxAmount   = 0.00;
        $totalAmount = round($subtotal - $discountAmount + $taxAmount, 2);

        $orderNumber = 'ORD-' . date('Ymd') . '-' . strtoupper(bin2hex(random_bytes(3)));

        $orderData = [
            'order_number'     => $orderNumber,
            'customer_id'      => (int) $customerId,
            'status'           => 'PENDING',
            'subtotal'         => $subtotal,
            'discount_amount'  => $discountAmount,
            'tax_amount'       => $taxAmount,
            'total_amount'     => $totalAmount,
            'coupon_id'        => $couponId,
            'created_via'      => 'ADMIN',
        ];

        $orderItemsTable   = $this->orderItemModel->db->DBPrefix . 'order_items';
        $hasListingPriceCol = $this->orderItemModel->db->fieldExists('listing_price_snapshot', $orderItemsTable);

        $this->orderModel->db->transStart();
        try {
            $orderId = $this->orderModel->insert($orderData);
            if (! $orderId) {
                throw new \RuntimeException('Failed to insert order.');
            }
            $orderId = (int) $orderId;

            foreach ($validItems as $item) {
                $row = [
                    'order_id'            => $orderId,
                    'product_id'          => $item['product_id'],
                    'stock_batch_id'      => $item['stock_batch_id'],
                    'qty'                 => $item['qty'],
                    'unit_price'          => $item['unit_price'],
                    'unit_cost_snapshot'  => $item['unit_cost_snapshot'],
                    'discount_amount'     => $item['discount_amount'],
                    'line_total'          => $item['line_total'],
                ];
                if ($hasListingPriceCol) {
                    $row['listing_price_snapshot'] = $item['listing_price_snapshot'] ?? $item['unit_price'];
                }
                if (! $this->orderItemModel->insert($row)) {
                    $err = $this->orderItemModel->db->error();
                    throw new \RuntimeException('Failed to save order line. ' . (is_array($err) && ! empty($err['message']) ? $err['message'] : 'Run: php spark migrate'));
                }

                $batchId = $item['stock_batch_id'];
                $qty     = $item['qty'];
                $this->stockBatchModel->set('remaining_qty', 'remaining_qty - ' . (int) $qty, false)->where('id', $batchId)->update();

                $this->stockMovementModel->insert([
                    'product_id'      => $item['product_id'],
                    'batch_id'        => $batchId,
                    'movement_type'   => 'SALE',
                    'qty_in'          => 0,
                    'qty_out'         => $qty,
                    'reference_type'  => 'order',
                    'reference_id'    => $orderId,
                    'note'            => 'Order #' . $orderNumber,
                ]);
            }

            if ($couponId) {
                $this->couponModel->set('used_count', 'used_count + 1', false)->where('id', $couponId)->update();
            }

            $this->logActivity('sales', 'order_create', $orderId, 'Created order: ' . $orderNumber);
            $this->orderModel->db->transComplete();
        } catch (\Throwable $e) {
            $this->orderModel->db->transRollback();
            return redirect()->back()->withInput()->with('error', 'Order could not be created: ' . $e->getMessage());
        }

        if ($this->orderModel->db->transStatus() === false) {
            return redirect()->back()->withInput()->with('error', 'Order could not be created.');
        }

        return redirect()->to('sales/orders')->with('message', 'Order created: ' . $orderNumber);
    }

    /**
     * Update order status. When set to PAID, create an invoice if one does not exist.
     */
    public function setStatus(int $id): RedirectResponse
    {
        $order = $this->orderModel->find($id);
        if (! $order) {
            return redirect()->back()->with('error', 'Order not found.');
        }

        $status = $this->request->getPost('status');
        $allowed = ['PENDING', 'CONFIRMED', 'PAID', 'CANCELLED'];
        if ($status === null || ! in_array($status, $allowed, true)) {
            return redirect()->back()->with('error', 'Invalid status.');
        }

        $this->orderModel->update($id, ['status' => $status]);

        if ($status === 'PAID') {
            $existing = $this->invoiceModel->where('order_id', $id)->first();
            if (! $existing) {
                $invoiceNumber = 'INV-' . date('Ymd') . '-' . strtoupper(bin2hex(random_bytes(3)));
                $this->invoiceModel->insert([
                    'invoice_number'  => $invoiceNumber,
                    'order_id'        => $id,
                    'customer_id'     => (int) $order['customer_id'],
                    'subtotal'        => (float) $order['subtotal'],
                    'discount_amount' => (float) $order['discount_amount'],
                    'tax_amount'      => (float) $order['tax_amount'],
                    'total_amount'    => (float) $order['total_amount'],
                    'status'          => 'ISSUED',
                    'issued_at'       => date('Y-m-d H:i:s'),
                ]);
                $this->logActivity('sales', 'invoice_create', (int) $this->invoiceModel->getInsertID(), 'Invoice ' . $invoiceNumber . ' for order ' . ($order['order_number'] ?? ''));
            }
        }

        $this->logActivity('sales', 'order_status', $id, 'Order status set to ' . $status);
        return redirect()->back()->with('message', 'Order status updated to ' . $status . ($status === 'PAID' ? '. Invoice created.' : ''));
    }

    /**
     * API: Search products (for typeahead). GET ?q=
     */
    public function apiProducts(): ResponseInterface
    {
        $q = $this->request->getGet('q');
        if ($q === null || $q === '') {
            return $this->response->setJSON([]);
        }
        $products = $this->productModel
            ->where('is_active', 1)
            ->groupStart()
                ->like('name', $q)
                ->orLike('sku', $q)
            ->groupEnd()
            ->orderBy('name', 'asc')
            ->limit(15)
            ->findAll();
        return $this->response->setJSON($products);
    }

    /**
     * API: Get customer by phone. GET ?phone=
     */
    public function apiCustomerByPhone(): ResponseInterface
    {
        $phone = $this->request->getGet('phone');
        if ($phone === null || $phone === '') {
            return $this->response->setStatusCode(400)->setJSON(['found' => false]);
        }
        $customer = $this->customerModel->findActiveByPhoneComparable($phone);
        if (! $customer) {
            return $this->response->setJSON(['found' => false]);
        }
        return $this->response->setJSON(['found' => true, 'customer' => $customer]);
    }

    /**
     * API: Validate coupon. GET or POST: code=, subtotal=
     * (GET avoids CSRF token staleness after other POSTs on the same page.)
     */
    public function apiValidateCoupon(): ResponseInterface
    {
        $code = trim((string) ($this->request->getGet('code') ?? $this->request->getPost('code') ?? ''));
        $subtotal = round((float) ($this->request->getGet('subtotal') ?? $this->request->getPost('subtotal') ?? 0), 2);
        if ($code === '') {
            return $this->response->setJSON(['valid' => false, 'message' => 'Coupon code is required.']);
        }
        $coupon = $this->couponModel->findByCode($code);
        $result = $this->validateCouponForSubtotal($coupon, $subtotal);
        if ($result['valid'] && $coupon) {
            $result['code'] = $coupon['code'] ?? '';
        }
        return $this->response->setJSON($result);
    }

    /**
     * API: Get selling price (and batch + rule details) for a product. GET ?product_id=
     * FIFO batch with remaining_qty > 0. BEVE-/FOOD-* SKUs use batch selling_price when set; else procurement rule.
     */
    public function apiProductPrice(): ResponseInterface
    {
        $productId = (int) $this->request->getGet('product_id');
        if ($productId <= 0) {
            return $this->response->setStatusCode(400)->setJSON(['error' => 'Invalid product.']);
        }
        $product = $this->productModel->find($productId);
        if (! $product || ! (int) ($product['is_active'] ?? 0)) {
            return $this->response->setJSON(['found' => false]);
        }

        $batch = $this->stockBatchModel
            ->where('product_id', $productId)
            ->where('remaining_qty >', 0)
            ->orderBy('received_at', 'asc')
            ->first();
        if (! $batch) {
            return $this->response->setJSON(['found' => false, 'message' => 'No stock available.']);
        }

        $unitCost = (float) $batch['unit_cost'];
        $sku      = (string) ($product['sku'] ?? '');

        if (ProductModel::skuIsFoodOrBeverage($sku)) {
            $rawSp = $batch['selling_price'] ?? null;
            if ($rawSp !== null && $rawSp !== '') {
                $unitPrice    = round((float) $rawSp, 2);
                $listingPrice = $unitPrice;

                return $this->response->setJSON([
                    'found'         => true,
                    'product_id'    => $productId,
                    'product_name'  => $product['name'] ?? '',
                    'batch_id'      => (int) $batch['id'],
                    'batch_code'    => $batch['batch_code'] ?? '',
                    'unit_cost'     => $unitCost,
                    'unit_price'    => $unitPrice,
                    'listing_price' => $listingPrice,
                    'remaining_qty' => (int) $batch['remaining_qty'],
                    'rule_name'     => 'Batch selling price',
                    'rule_id'       => 0,
                ]);
            }
        }

        $ruleRow = $this->batchRuleModel
            ->where('batch_id', $batch['id'])
            ->where('is_active', 1)
            ->orderBy('id', 'desc')
            ->first();
        if (! $ruleRow) {
            return $this->response->setJSON(['found' => false, 'message' => 'No pricing rule for available batch.']);
        }

        $rule = $this->procurementRuleModel->find($ruleRow['procurement_rule_id']);
        if (! $rule) {
            return $this->response->setJSON(['found' => false]);
        }

        $unitPrice    = $this->computeSellingPrice($unitCost, $rule);
        $listingPrice = $this->computeListingPrice($unitPrice, $rule);

        return $this->response->setJSON([
            'found'         => true,
            'product_id'    => $productId,
            'product_name'  => $product['name'] ?? '',
            'batch_id'      => (int) $batch['id'],
            'batch_code'    => $batch['batch_code'] ?? '',
            'unit_cost'     => $unitCost,
            'unit_price'    => $unitPrice,
            'listing_price' => $listingPrice,
            'remaining_qty' => (int) $batch['remaining_qty'],
            'rule_name'     => $rule['name'] ?? '',
            'rule_id'       => (int) $rule['id'],
        ]);
    }

    /**
     * API: Quick-add customer from create order screen. POST (customer_type, name, phone, ...). Returns JSON { success, customer: { id, name, phone } }.
     */
    public function apiQuickAddCustomer(): ResponseInterface
    {
        $rules = [
            'customer_type' => 'required|in_list[INDIVIDUAL,BUSINESS,WALK_IN]',
            'name'          => 'required|max_length[150]',
            'phone'          => 'required|max_length[30]',
            'email'          => 'permit_empty|max_length[191]',
            'address_line1'  => 'permit_empty|max_length[255]',
            'address_line2'  => 'permit_empty|max_length[255]',
            'city'           => 'permit_empty|max_length[100]',
            'state'          => 'permit_empty|max_length[100]',
            'postal_code'    => 'permit_empty|max_length[20]',
            'tax_number'     => 'permit_empty|max_length[100]',
            'notes'          => 'permit_empty|max_length[500]',
        ];
        if (! $this->validate($rules)) {
            return $this->response->setJSON(['success' => false, 'errors' => $this->validator->getErrors()]);
        }
        $phone = trim((string) $this->request->getPost('phone'));
        if ($this->customerModel->findOtherByPhoneComparable($phone, null) !== null) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'A customer with this phone number already exists. Look them up by phone instead.',
            ]);
        }
        $data = [
            'customer_type' => $this->request->getPost('customer_type'),
            'name'          => $this->request->getPost('name'),
            'phone'         => $phone,
            'email'         => $this->request->getPost('email') ?: null,
            'address_line1' => $this->request->getPost('address_line1') ?: null,
            'address_line2' => $this->request->getPost('address_line2') ?: null,
            'city'          => $this->request->getPost('city') ?: null,
            'state'         => $this->request->getPost('state') ?: null,
            'postal_code'   => $this->request->getPost('postal_code') ?: null,
            'tax_number'    => $this->request->getPost('tax_number') ?: null,
            'notes'         => $this->request->getPost('notes') ?: null,
            'is_active'     => 1,
        ];
        $id = $this->customerModel->insert($data);
        if (! $id) {
            return $this->response->setJSON(['success' => false, 'errors' => $this->customerModel->errors()]);
        }
        $this->logActivity('sales', 'customer_create', (int) $id, 'Quick-add customer: ' . $data['name']);
        return $this->response->setJSON([
            'success'  => true,
            'customer' => ['id' => (int) $id, 'name' => $data['name'], 'phone' => $data['phone']],
        ]);
    }

    /**
     * Compute selling price from buying price (unit_cost) and procurement rule.
     * Selling price = buying price + profit (profit is FLAT or % of buying price).
     */
    protected function computeSellingPrice(float $unitCost, array $rule): float
    {
        $profitType  = $rule['profit_type'] ?? 'FLAT';
        $profitValue = (float) ($rule['profit_value'] ?? 0);

        $profitAmount = $profitType === 'PERCENTAGE'
            ? $unitCost * ($profitValue / 100)
            : $profitValue;

        $sellingPrice = $unitCost + $profitAmount;
        return round(max(0, $sellingPrice), 2);
    }

    /**
     * Compute listing price (MRP) from selling price and procurement rule.
     * FLAT discount: "₹X off" from list price → listing_price = selling_price + X. E.g. ₹20 off, selling 105 → listing 125.
     * PERCENTAGE discount: "X% off" from list price → listing_price = selling_price / (1 - X/100).
     */
    protected function computeListingPrice(float $sellingPrice, array $rule): float
    {
        $discountType  = $rule['discount_type'] ?? 'FLAT';
        $discountValue = (float) ($rule['discount_value'] ?? 0);
        if ($discountType === 'FLAT' && $discountValue > 0) {
            return round($sellingPrice + $discountValue, 2);
        }
        if ($discountType === 'PERCENTAGE' && $discountValue > 0 && $discountValue < 100) {
            $listingPrice = $sellingPrice / (1 - $discountValue / 100);
            return round(max($sellingPrice, $listingPrice), 2);
        }
        return $sellingPrice;
    }

    /**
     * Validate coupon for a given subtotal. Returns [ 'valid' => bool, 'discount_amount' => float, 'message' => string ].
     */
    protected function validateCouponForSubtotal(?array $coupon, float $subtotal): array
    {
        $subtotal = round($subtotal, 2);
        if (! $coupon || ! (int) ($coupon['is_active'] ?? 0)) {
            return ['valid' => false, 'discount_amount' => 0.0, 'message' => 'Invalid or inactive coupon.'];
        }
        $now = date('Y-m-d H:i:s');
        if (($coupon['valid_from'] ?? '') > $now) {
            return ['valid' => false, 'discount_amount' => 0.0, 'message' => 'Coupon not yet valid.'];
        }
        if (($coupon['valid_to'] ?? '') < $now) {
            return ['valid' => false, 'discount_amount' => 0.0, 'message' => 'Coupon has expired.'];
        }
        $usageLimit = isset($coupon['usage_limit']) && $coupon['usage_limit'] !== '' && $coupon['usage_limit'] !== null
            ? (int) $coupon['usage_limit']
            : null;
        $usedCount = (int) ($coupon['used_count'] ?? 0);
        if ($usageLimit !== null && $usageLimit > 0 && $usedCount >= $usageLimit) {
            return ['valid' => false, 'discount_amount' => 0.0, 'message' => 'Coupon usage limit reached.'];
        }
        $minOrder = isset($coupon['min_order_amount']) && $coupon['min_order_amount'] !== null && $coupon['min_order_amount'] !== ''
            ? round((float) $coupon['min_order_amount'], 2)
            : null;
        if ($minOrder !== null && $subtotal < $minOrder) {
            return ['valid' => false, 'discount_amount' => 0.0, 'message' => 'Minimum order amount not met.'];
        }

        $discountType  = $coupon['discount_type'] ?? 'FLAT';
        $discountValue = (float) ($coupon['discount_value'] ?? 0);
        $maxDiscount   = isset($coupon['max_discount_amount']) ? (float) $coupon['max_discount_amount'] : null;

        if ($discountType === 'PERCENTAGE') {
            $discountAmount = round($subtotal * $discountValue / 100, 2);
        } else {
            $discountAmount = $discountValue;
        }
        if ($maxDiscount !== null && $discountAmount > $maxDiscount) {
            $discountAmount = $maxDiscount;
        }
        $discountAmount = min($discountAmount, $subtotal);
        return ['valid' => true, 'discount_amount' => $discountAmount, 'message' => 'Coupon applied.', 'code' => $coupon['code'] ?? ''];
    }
}
