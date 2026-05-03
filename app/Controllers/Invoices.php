<?php

namespace App\Controllers;

use App\Models\InvoiceModel;
use App\Models\OrderModel;
use App\Models\OrderItemModel;
use App\Models\CustomerModel;
use App\Models\CouponModel;
use App\Models\ProductModel;
use App\Models\GamingVisitModel;
use App\Models\GamingVisitFoodItemModel;
use App\Models\GamingCategoryModel;
use App\Models\GamingModeModel;
use CodeIgniter\HTTP\RedirectResponse;

class Invoices extends BaseController
{
    protected InvoiceModel $invoiceModel;
    protected OrderModel $orderModel;
    protected OrderItemModel $orderItemModel;
    protected CustomerModel $customerModel;
    protected CouponModel $couponModel;
    protected ProductModel $productModel;
    protected GamingVisitModel $gamingVisitModel;
    protected GamingVisitFoodItemModel $gamingVisitFoodModel;

    public function __construct()
    {
        $this->invoiceModel        = model(InvoiceModel::class);
        $this->orderModel          = model(OrderModel::class);
        $this->orderItemModel      = model(OrderItemModel::class);
        $this->customerModel       = model(CustomerModel::class);
        $this->couponModel         = model(CouponModel::class);
        $this->productModel        = model(ProductModel::class);
        $this->gamingVisitModel    = model(GamingVisitModel::class);
        $this->gamingVisitFoodModel = model(GamingVisitFoodItemModel::class);
    }

    /**
     * List invoices with optional search and sort.
     */
    public function index(): string
    {
        $q = $this->request->getGet('q');
        $sortCol   = $this->request->getGet('sort');
        $sortOrder = strtolower((string) $this->request->getGet('order')) === 'desc' ? 'desc' : 'asc';
        $allowedSort = ['invoice_number', 'order_number', 'customer_name', 'issued_at', 'total_amount', 'status', 'created_at'];
        $prefix = $this->invoiceModel->db->DBPrefix;
        $builder = $this->invoiceModel->builder()
            ->select('invoices.*, orders.order_number, customers.name AS customer_name, customers.phone AS customer_phone')
            ->join($prefix . 'orders orders', 'orders.id = invoices.order_id', 'left')
            ->join($prefix . 'customers customers', 'customers.id = invoices.customer_id', 'left');

        if ($q !== null && $q !== '') {
            $builder->groupStart()
                ->like('invoices.invoice_number', $q)
                ->orLike('orders.order_number', $q)
                ->orLike('customers.name', $q)
                ->orLike('customers.phone', $q)
                ->groupEnd();
        }

        if ($sortCol !== null && in_array($sortCol, $allowedSort, true)) {
            $orderCol = match ($sortCol) {
                'order_number' => 'orders.order_number',
                'customer_name' => 'customers.name',
                default => 'invoices.' . $sortCol,
            };
            $builder->orderBy($orderCol, $sortOrder);
        } else {
            $builder->orderBy('invoices.created_at', 'desc');
        }
        $invoices = $builder->get()->getResultArray();

        return view('layout/main', [
            'pageTitle' => 'Invoices - Gameshala ERP',
            'content'   => view('sales/invoices/index', [
                'invoices' => $invoices,
                'searchQ'  => $q ?? '',
                'sort'     => $sortCol ?? 'created_at',
                'order'    => $sortCol !== null && in_array($sortCol, $allowedSort, true) ? $sortOrder : 'desc',
            ]),
        ]);
    }

    /**
     * Printable / PDF-ready invoice view. Use browser Print (Ctrl+P) or "Save as PDF".
     */
    public function view(int $id): string|RedirectResponse
    {
        $invoice = $this->invoiceModel->find($id);
        if (! $invoice) {
            return redirect()->to('sales/invoices')->with('error', 'Invoice not found.');
        }

        $customer = $this->customerModel->find($invoice['customer_id']);
        $order    = null;
        $items    = [];
        $isGaming = ! empty($invoice['gaming_visit_id']);

        if ($isGaming) {
            $visit = $this->gamingVisitModel->find($invoice['gaming_visit_id']);
            $prefix = $this->gamingVisitFoodModel->db->DBPrefix;
            $foodRows = $this->gamingVisitFoodModel->builder()
                ->select('gaming_visit_food_items.*, fbi.name AS fbi_name, p.name AS product_name, p.sku AS product_sku, COALESCE(fbi.name, p.name) AS item_name')
                ->join($prefix . 'food_beverage_items fbi', 'fbi.id = gaming_visit_food_items.food_beverage_item_id', 'left')
                ->join($prefix . 'products p', 'p.id = gaming_visit_food_items.product_id', 'left')
                ->where('gaming_visit_food_items.gaming_visit_id', $invoice['gaming_visit_id'])
                ->get()
                ->getResultArray();
            $catName = '—';
            $modeName = '—';
            if ($visit && ! empty($visit['gaming_price_rule_id'])) {
                $ruleModel = model(\App\Models\GamingPriceRuleModel::class);
                $rule = $ruleModel->find($visit['gaming_price_rule_id']);
                if ($rule) {
                    $catName = (model(GamingCategoryModel::class)->find($rule['gaming_category_id'])['name'] ?? '—');
                    $modeName = (model(GamingModeModel::class)->find($rule['gaming_mode_id'])['name'] ?? '—');
                }
            }
            $gamingAmount = (float) ($visit['gaming_amount'] ?? 0);
            $items = [];
            if ($gamingAmount > 0) {
                $items[] = ['description' => 'Gaming - ' . $catName . ' / ' . $modeName, 'qty' => 1, 'unit_price' => $gamingAmount, 'line_total' => $gamingAmount, 'product_name' => null, 'sku' => null];
            }
            foreach ($foodRows as $row) {
                $label = self::cleanInvoiceFoodLineDescription($row);
                $items[] = [
                    'description'  => $label,
                    'qty'          => (int) $row['quantity'],
                    'unit_price'   => (float) $row['line_total'] / max(1, (int) $row['quantity']),
                    'line_total'   => (float) $row['line_total'],
                    'product_name' => $label,
                    'sku'          => ! empty($row['product_sku']) ? (string) $row['product_sku'] : null,
                ];
            }
        } else {
            $order = $this->orderModel->find($invoice['order_id']);
            $items = $this->orderItemModel->builder()
                ->select('order_items.*, products.name AS product_name, products.sku')
                ->join('products', 'products.id = order_items.product_id', 'left')
                ->where('order_items.order_id', $invoice['order_id'])
                ->get()
                ->getResultArray();
        }

        $coupon = null;
        if (! empty($order['coupon_id'])) {
            $coupon = $this->couponModel->find($order['coupon_id']);
        }

        $company = config('Company');

        $customerName  = trim((string) ($customer['name'] ?? ''));
        $invNo         = (string) ($invoice['invoice_number'] ?? '');
        $companyName   = is_object($company) ? (string) ($company->name ?? 'Gameshaala') : 'Gameshaala';
        $invoiceUrl    = base_url('sales/invoices/view/' . $id);
        $totalStr      = number_format((float) ($invoice['total_amount'] ?? 0), 2);
        $shareBody     = 'Hi ' . ($customerName !== '' ? $customerName : 'there') . ",\n\n"
            . 'Your invoice ' . $invNo . ' from ' . $companyName . " is ready.\n"
            . 'Amount: ₹' . $totalStr . "\n\n"
            . 'View or print: ' . $invoiceUrl . "\n\n"
            . 'Thank you.';

        $waPhone = CustomerModel::whatsappDialNumber($customer['phone'] ?? null);
        $shareWhatsappUrl = $waPhone !== null
            ? 'https://wa.me/' . $waPhone . '?text=' . rawurlencode($shareBody)
            : null;

        $customerEmail = trim((string) ($customer['email'] ?? ''));
        $shareEmailUrl = $customerEmail !== ''
            ? 'mailto:' . $customerEmail
                . '?subject=' . rawurlencode('Invoice ' . $invNo . ' — ' . $companyName)
                . '&body=' . rawurlencode($shareBody)
            : null;

        $data = [
            'invoice'           => $invoice,
            'order'             => $order,
            'customer'          => $customer,
            'items'             => $items,
            'coupon'            => $coupon,
            'company'           => $company,
            'isGaming'          => $isGaming,
            'shareWhatsappUrl'  => $shareWhatsappUrl,
            'shareEmailUrl'     => $shareEmailUrl,
        ];

        return view('sales/invoices/print', $data);
    }

    /**
     * Customer-facing label for gaming F&B lines: use catalog product name when present;
     * otherwise strip synthetic "[Catalog] … (#id)" from legacy food_beverage_rows.
     *
     * @param array<string, mixed> $row Joined gaming_visit_food_items row
     */
    protected static function cleanInvoiceFoodLineDescription(array $row): string
    {
        $productName = isset($row['product_name']) ? trim((string) $row['product_name']) : '';
        if ($productName !== '') {
            return $productName;
        }

        $name = trim((string) ($row['item_name'] ?? ''));
        if ($name === '') {
            return 'Food item';
        }

        $name = (string) preg_replace('/^\[Catalog\]\s+/u', '', $name);
        $name = (string) preg_replace('/\s*\(#\d+\)\s*$/u', '', $name);
        $name = trim($name);

        return $name !== '' ? $name : 'Food item';
    }
}
