<?php

namespace App\Controllers;

use App\Models\CustomerModel;
use App\Models\FoodBeverageItemModel;
use App\Models\GamingCategoryModel;
use App\Models\GamingModeModel;
use App\Models\GamingPriceRuleModel;
use App\Models\GamingVisitFoodItemModel;
use App\Models\GamingVisitModel;
use App\Models\InvoiceModel;
use CodeIgniter\HTTP\RedirectResponse;
use CodeIgniter\HTTP\ResponseInterface;

class Gaming extends BaseController
{
    protected GamingCategoryModel $categoryModel;
    protected GamingModeModel $modeModel;
    protected GamingPriceRuleModel $priceRuleModel;
    protected FoodBeverageItemModel $foodBeverageItemModel;
    protected GamingVisitModel $visitModel;
    protected GamingVisitFoodItemModel $visitFoodModel;
    protected CustomerModel $customerModel;
    protected InvoiceModel $invoiceModel;

    public function __construct()
    {
        $this->categoryModel        = model(GamingCategoryModel::class);
        $this->modeModel            = model(GamingModeModel::class);
        $this->priceRuleModel       = model(GamingPriceRuleModel::class);
        $this->foodBeverageItemModel = model(FoodBeverageItemModel::class);
        $this->visitModel           = model(GamingVisitModel::class);
        $this->visitFoodModel       = model(GamingVisitFoodItemModel::class);
        $this->customerModel        = model(CustomerModel::class);
        $this->invoiceModel         = model(InvoiceModel::class);
    }

    /**
     * Categories page: gaming categories and gaming modes (add, edit, deactivate).
     */
    public function categories(): string
    {
        helper('form');
        $categories = $this->categoryModel->orderBy('name', 'asc')->findAll();
        $modes      = $this->modeModel->orderBy('name', 'asc')->findAll();

        return view('layout/main', [
            'pageTitle' => 'Categories - Gaming',
            'content'   => view('gaming/categories', [
                'categories' => $categories,
                'modes'     => $modes,
            ]),
        ]);
    }

    public function addCategory(): RedirectResponse
    {
        if (! $this->validate(['name' => 'required|max_length[100]'])) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        $name = trim($this->request->getPost('name'));
        $id   = $this->categoryModel->insert(['name' => $name, 'is_active' => 1]);
        $this->logActivity('gaming', 'category_create', (int) $id, 'Created gaming category: ' . $name);
        return redirect()->back()->with('message', 'Category added.');
    }

    public function updateCategory(int $id): RedirectResponse
    {
        $cat = $this->categoryModel->find($id);
        if (! $cat) {
            return redirect()->back()->with('error', 'Category not found.');
        }
        if (! $this->validate(['name' => 'required|max_length[100]'])) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        $name = trim($this->request->getPost('name'));
        $this->categoryModel->update($id, ['name' => $name]);
        $this->logActivity('gaming', 'category_update', $id, 'Updated gaming category: ' . $name);
        return redirect()->back()->with('message', 'Category updated.');
    }

    public function setStatusCategory(int $id): RedirectResponse
    {
        $cat = $this->categoryModel->find($id);
        if (! $cat) {
            return redirect()->back()->with('error', 'Category not found.');
        }
        $status = (int) $this->request->getPost('is_active');
        $status = $status === 1 ? 1 : 0;
        $this->categoryModel->update($id, ['is_active' => $status]);
        $action = $status === 1 ? 'category_activate' : 'category_deactivate';
        $this->logActivity('gaming', $action, $id, ($status === 1 ? 'Activated' : 'Deactivated') . ' gaming category: ' . ($cat['name'] ?? '#' . $id));
        return redirect()->back()->with('message', $status === 1 ? 'Category marked active.' : 'Category marked inactive.');
    }

    public function addMode(): RedirectResponse
    {
        if (! $this->validate(['name' => 'required|max_length[100]'])) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        $name = trim($this->request->getPost('name'));
        $id   = $this->modeModel->insert(['name' => $name, 'is_active' => 1]);
        $this->logActivity('gaming', 'mode_create', (int) $id, 'Created gaming mode: ' . $name);
        return redirect()->back()->with('message', 'Mode added.');
    }

    public function updateMode(int $id): RedirectResponse
    {
        $mode = $this->modeModel->find($id);
        if (! $mode) {
            return redirect()->back()->with('error', 'Mode not found.');
        }
        if (! $this->validate(['name' => 'required|max_length[100]'])) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        $name = trim($this->request->getPost('name'));
        $this->modeModel->update($id, ['name' => $name]);
        $this->logActivity('gaming', 'mode_update', $id, 'Updated gaming mode: ' . $name);
        return redirect()->back()->with('message', 'Mode updated.');
    }

    public function setStatusMode(int $id): RedirectResponse
    {
        $mode = $this->modeModel->find($id);
        if (! $mode) {
            return redirect()->back()->with('error', 'Mode not found.');
        }
        $status = (int) $this->request->getPost('is_active');
        $status = $status === 1 ? 1 : 0;
        $this->modeModel->update($id, ['is_active' => $status]);
        $action = $status === 1 ? 'mode_activate' : 'mode_deactivate';
        $this->logActivity('gaming', $action, $id, ($status === 1 ? 'Activated' : 'Deactivated') . ' gaming mode: ' . ($mode['name'] ?? '#' . $id));
        return redirect()->back()->with('message', $status === 1 ? 'Mode marked active.' : 'Mode marked inactive.');
    }

    /**
     * Price rules page: list, add, edit, activate/deactivate.
     */
    public function priceRules(): string
    {
        helper('form');
        $categories = $this->categoryModel->where('is_active', 1)->orderBy('name', 'asc')->findAll();
        $modes      = $this->modeModel->where('is_active', 1)->orderBy('name', 'asc')->findAll();
        $prefix     = $this->priceRuleModel->db->DBPrefix;
        $rules      = $this->priceRuleModel->builder()
            ->select('gaming_price_rules.*, gc.name AS category_name, gm.name AS mode_name')
            ->join($prefix . 'gaming_categories gc', 'gc.id = gaming_price_rules.gaming_category_id', 'left')
            ->join($prefix . 'gaming_modes gm', 'gm.id = gaming_price_rules.gaming_mode_id', 'left')
            ->orderBy('gc.name', 'asc')
            ->orderBy('gm.name', 'asc')
            ->get()
            ->getResultArray();

        return view('layout/main', [
            'pageTitle' => 'Price Rules - Gaming',
            'content'   => view('gaming/price_rules', [
                'categories' => $categories,
                'modes'     => $modes,
                'rules'     => $rules,
            ]),
        ]);
    }

    public function addPriceRule(): RedirectResponse
    {
        $rules = [
            'gaming_category_id' => 'required|integer',
            'gaming_mode_id'    => 'required|integer',
            'price_type'        => 'required|in_list[PER_MINUTE,PER_30_MIN,PER_HOUR,FIXED]',
            'price'             => 'required|decimal',
        ];
        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        $categoryId = (int) $this->request->getPost('gaming_category_id');
        $modeId     = (int) $this->request->getPost('gaming_mode_id');
        $priceType  = $this->request->getPost('price_type');
        $price      = (float) $this->request->getPost('price');
        $id         = $this->priceRuleModel->insert([
            'gaming_category_id' => $categoryId,
            'gaming_mode_id'     => $modeId,
            'price_type'         => $priceType,
            'price'              => $price,
            'is_active'          => 1,
        ]);
        $this->logActivity('gaming', 'price_rule_create', (int) $id, 'Created gaming price rule.');
        return redirect()->back()->with('message', 'Price rule added.');
    }

    public function updatePriceRule(int $id): RedirectResponse
    {
        $rule = $this->priceRuleModel->find($id);
        if (! $rule) {
            return redirect()->back()->with('error', 'Price rule not found.');
        }
        $rules = [
            'gaming_category_id' => 'required|integer',
            'gaming_mode_id'     => 'required|integer',
            'price_type'         => 'required|in_list[PER_MINUTE,PER_30_MIN,PER_HOUR,FIXED]',
            'price'              => 'required|decimal',
        ];
        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        $this->priceRuleModel->update($id, [
            'gaming_category_id' => (int) $this->request->getPost('gaming_category_id'),
            'gaming_mode_id'     => (int) $this->request->getPost('gaming_mode_id'),
            'price_type'         => $this->request->getPost('price_type'),
            'price'              => (float) $this->request->getPost('price'),
        ]);
        $this->logActivity('gaming', 'price_rule_update', $id, 'Updated gaming price rule.');
        return redirect()->back()->with('message', 'Price rule updated.');
    }

    public function setStatusPriceRule(int $id): RedirectResponse
    {
        $rule = $this->priceRuleModel->find($id);
        if (! $rule) {
            return redirect()->back()->with('error', 'Price rule not found.');
        }
        $status = (int) $this->request->getPost('is_active');
        $status = $status === 1 ? 1 : 0;
        $this->priceRuleModel->update($id, ['is_active' => $status]);
        $action = $status === 1 ? 'price_rule_activate' : 'price_rule_deactivate';
        $this->logActivity('gaming', $action, $id, ($status === 1 ? 'Activated' : 'Deactivated') . ' gaming price rule #' . $id);
        return redirect()->back()->with('message', $status === 1 ? 'Price rule marked active.' : 'Price rule marked inactive.');
    }

    /**
     * Food & Beverages page: list, add, edit, activate/deactivate.
     */
    public function foodBeverages(): string
    {
        helper('form');
        $items = $this->foodBeverageItemModel->orderBy('name', 'asc')->findAll();

        return view('layout/main', [
            'pageTitle' => 'Food & Beverages - Gaming',
            'content'   => view('gaming/food_beverages', ['items' => $items]),
        ]);
    }

    public function addFoodBeverageItem(): RedirectResponse
    {
        $rules = [
            'name'       => 'required|max_length[100]',
            'unit_label' => 'max_length[50]',
            'price'      => 'required|decimal',
        ];
        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        $name = trim($this->request->getPost('name'));
        $id   = $this->foodBeverageItemModel->insert([
            'name'       => $name,
            'unit_label' => trim($this->request->getPost('unit_label') ?: '') ?: null,
            'price'      => (float) $this->request->getPost('price'),
            'is_active'  => 1,
        ]);
        $this->logActivity('gaming', 'food_beverage_create', (int) $id, 'Created food/beverage item: ' . $name);
        return redirect()->back()->with('message', 'Item added.');
    }

    public function updateFoodBeverageItem(int $id): RedirectResponse
    {
        $item = $this->foodBeverageItemModel->find($id);
        if (! $item) {
            return redirect()->back()->with('error', 'Item not found.');
        }
        $rules = [
            'name'       => 'required|max_length[100]',
            'unit_label' => 'max_length[50]',
            'price'      => 'required|decimal',
        ];
        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        $name = trim($this->request->getPost('name'));
        $this->foodBeverageItemModel->update($id, [
            'name'       => $name,
            'unit_label' => trim($this->request->getPost('unit_label') ?: '') ?: null,
            'price'      => (float) $this->request->getPost('price'),
        ]);
        $this->logActivity('gaming', 'food_beverage_update', $id, 'Updated food/beverage item: ' . $name);
        return redirect()->back()->with('message', 'Item updated.');
    }

    public function setStatusFoodBeverageItem(int $id): RedirectResponse
    {
        $item = $this->foodBeverageItemModel->find($id);
        if (! $item) {
            return redirect()->back()->with('error', 'Item not found.');
        }
        $status = (int) $this->request->getPost('is_active');
        $status = $status === 1 ? 1 : 0;
        $this->foodBeverageItemModel->update($id, ['is_active' => $status]);
        $action = $status === 1 ? 'food_beverage_activate' : 'food_beverage_deactivate';
        $this->logActivity('gaming', $action, $id, ($status === 1 ? 'Activated' : 'Deactivated') . ' food/beverage: ' . ($item['name'] ?? '#' . $id));
        return redirect()->back()->with('message', $status === 1 ? 'Item marked active.' : 'Item marked inactive.');
    }

    /**
     * Sessions page: start session (modal), ongoing sessions (tiles), ended sessions (table with Generate invoice / View invoice).
     */
    public function sessions(): string
    {
        helper('form');
        $prefix   = $this->visitModel->db->DBPrefix;
        $ongoing  = $this->visitModel->builder()
            ->select('gaming_visits.*, c.name AS customer_name, c.phone AS customer_phone, gc.name AS category_name, gm.name AS mode_name')
            ->join($prefix . 'customers c', 'c.id = gaming_visits.customer_id', 'left')
            ->join($prefix . 'gaming_price_rules gpr', 'gpr.id = gaming_visits.gaming_price_rule_id', 'left')
            ->join($prefix . 'gaming_categories gc', 'gc.id = gpr.gaming_category_id', 'left')
            ->join($prefix . 'gaming_modes gm', 'gm.id = gpr.gaming_mode_id', 'left')
            ->where('gaming_visits.status', 'ONGOING')
            ->orderBy('gaming_visits.start_time', 'desc')
            ->get()
            ->getResultArray();
        $finishedPerPage = 10;
        $finishedPage    = max(1, (int) $this->request->getGet('page'));
        $finishedTotal   = (int) $this->visitModel->builder()->where('status', 'FINISHED')->countAllResults();
        $finished        = $this->visitModel->builder()
            ->select('gaming_visits.*, c.name AS customer_name, c.phone AS customer_phone, gc.name AS category_name, gm.name AS mode_name')
            ->join($prefix . 'customers c', 'c.id = gaming_visits.customer_id', 'left')
            ->join($prefix . 'gaming_price_rules gpr', 'gpr.id = gaming_visits.gaming_price_rule_id', 'left')
            ->join($prefix . 'gaming_categories gc', 'gc.id = gpr.gaming_category_id', 'left')
            ->join($prefix . 'gaming_modes gm', 'gm.id = gpr.gaming_mode_id', 'left')
            ->where('gaming_visits.status', 'FINISHED')
            ->orderBy('gaming_visits.end_time', 'desc')
            ->limit($finishedPerPage, ($finishedPage - 1) * $finishedPerPage)
            ->get()
            ->getResultArray();

        $foodItems   = $this->foodBeverageItemModel->where('is_active', 1)->orderBy('name', 'asc')->findAll();
        $priceRules  = $this->priceRuleModel->where('is_active', 1)->orderBy('id', 'asc')->findAll();
        $rulesWithNames = [];
        foreach ($priceRules as $r) {
            $cat = $this->categoryModel->find($r['gaming_category_id']);
            $mode = $this->modeModel->find($r['gaming_mode_id']);
            $r['category_name'] = $cat['name'] ?? '';
            $r['mode_name']     = $mode['name'] ?? '';
            $rulesWithNames[]   = $r;
        }
        $visitIds    = array_merge(array_column($ongoing, 'id'), array_column($finished, 'id'));
        $foodByVisit = [];
        if (! empty($visitIds)) {
            $rows = $this->visitFoodModel->builder()
                ->select('gaming_visit_food_items.*, fbi.name AS item_name, fbi.unit_label')
                ->join($prefix . 'food_beverage_items fbi', 'fbi.id = gaming_visit_food_items.food_beverage_item_id', 'left')
                ->whereIn('gaming_visit_food_items.gaming_visit_id', $visitIds)
                ->get()
                ->getResultArray();
            foreach ($rows as $row) {
                $vid = $row['gaming_visit_id'];
                if (! isset($foodByVisit[$vid])) {
                    $foodByVisit[$vid] = [];
                }
                $foodByVisit[$vid][] = $row;
            }
        }
        $finishedTotalPages = $finishedTotal > 0 ? (int) ceil($finishedTotal / $finishedPerPage) : 1;

        return view('layout/main', [
            'pageTitle' => 'Sessions - Gaming',
            'content'   => view('gaming/sessions', [
                'ongoing'              => $ongoing,
                'finished'             => $finished,
                'finishedTotal'        => $finishedTotal,
                'finishedPage'         => $finishedPage,
                'finishedPerPage'      => $finishedPerPage,
                'finishedTotalPages'   => $finishedTotalPages,
                'foodByVisit'          => $foodByVisit,
                'foodItems'            => $foodItems,
                'priceRules'           => $rulesWithNames,
            ]),
        ]);
    }

    public function startSession(): RedirectResponse
    {
        $rules = [
            'gaming_price_rule_id' => 'required|integer',
            'no_of_players'        => 'integer|greater_than_equal_to[1]',
            'start_time'           => 'required|valid_date',
        ];
        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        $newName  = trim((string) $this->request->getPost('new_customer_name'));
        $newPhone = trim((string) $this->request->getPost('new_customer_phone'));
        if ($newName !== '' && $newPhone !== '') {
            $customerId = $this->customerModel->insert([
                'customer_type' => 'WALK_IN',
                'name'          => $newName,
                'phone'         => $newPhone,
                'is_active'     => 1,
            ]);
            $customerId = $customerId === false ? 0 : (int) $customerId;
            if ($customerId < 1) {
                return redirect()->back()->withInput()->with('error', 'Could not create customer. Please try again.');
            }
        } else {
            $customerId = (int) $this->request->getPost('customer_id');
            if ($customerId < 1) {
                return redirect()->back()->with('error', 'Search and select an existing customer, or add a new one with name and phone.');
            }
            if (! $this->customerModel->find($customerId)) {
                return redirect()->back()->with('error', 'Customer not found.');
            }
        }
        $ruleId     = (int) $this->request->getPost('gaming_price_rule_id');
        $noOfPlayers = (int) $this->request->getPost('no_of_players') ?: 1;
        $startTime   = $this->request->getPost('start_time');
        if (! $this->priceRuleModel->find($ruleId)) {
            return redirect()->back()->with('error', 'Price rule not found.');
        }
        $id = $this->visitModel->insert([
            'customer_id'          => $customerId,
            'gaming_price_rule_id' => $ruleId,
            'no_of_players'        => $noOfPlayers,
            'start_time'           => $startTime,
            'gaming_amount'        => 0.00,
            'food_amount'          => 0.00,
            'total_amount'         => 0.00,
            'status'               => 'ONGOING',
        ]);
        $this->logActivity('gaming', 'visit_start', (int) $id, 'Started gaming session #' . $id);
        return redirect()->to('gaming/sessions')->with('message', 'Session #' . $id . ' started.');
    }

    public function addFood(): RedirectResponse
    {
        $visitId = (int) $this->request->getPost('gaming_visit_id');
        $itemId  = (int) $this->request->getPost('food_beverage_item_id');
        $qty     = (int) $this->request->getPost('quantity');
        if ($qty < 1) {
            return redirect()->back()->with('error', 'Quantity must be at least 1.');
        }
        $visit = $this->visitModel->find($visitId);
        if (! $visit || ($visit['status'] ?? '') !== 'ONGOING') {
            return redirect()->back()->with('error', 'Session not found or not ongoing.');
        }
        $item = $this->foodBeverageItemModel->find($itemId);
        if (! $item || ! (int) ($item['is_active'] ?? 1)) {
            return redirect()->back()->with('error', 'Item not found.');
        }
        $lineTotal = round((float) $item['price'] * $qty, 2);
        $this->visitFoodModel->insert([
            'gaming_visit_id'       => $visitId,
            'food_beverage_item_id' => $itemId,
            'quantity'             => $qty,
            'line_total'           => $lineTotal,
        ]);
        $this->logActivity('gaming', 'visit_food_add', $visitId, 'Added food/beverage to session #' . $visitId);
        return redirect()->back()->with('message', 'Item added to session.');
    }

    public function endSession(int $id): RedirectResponse
    {
        $visit = $this->visitModel->find($id);
        if (! $visit || ($visit['status'] ?? '') !== 'ONGOING') {
            return redirect()->back()->with('error', 'Session not found or not ongoing.');
        }
        $endTime = date('Y-m-d H:i:s');
        $this->visitModel->update($id, ['end_time' => $endTime]);

        $rule = $this->priceRuleModel->find($visit['gaming_price_rule_id']);
        $start = strtotime($visit['start_time']);
        $end   = strtotime($endTime);
        $minutes = max(0, ($end - $start) / 60);
        $price = (float) $rule['price'];
        $priceType = $rule['price_type'] ?? 'FIXED';
        $gamingAmount = match ($priceType) {
            'PER_MINUTE' => round($price * $minutes, 2),
            'PER_30_MIN' => round($price * ceil($minutes / 30), 2),
            'PER_HOUR'   => round($price * ($minutes / 60), 2),
            default     => round($price, 2),
        };

        $foodRows = $this->visitFoodModel->where('gaming_visit_id', $id)->findAll();
        $foodAmount = 0.00;
        foreach ($foodRows as $row) {
            $foodAmount += (float) $row['line_total'];
        }
        $foodAmount = round($foodAmount, 2);
        $totalAmount = round($gamingAmount + $foodAmount, 2);

        $this->visitModel->update($id, [
            'gaming_amount' => $gamingAmount,
            'food_amount'   => $foodAmount,
            'total_amount' => $totalAmount,
            'status'       => 'FINISHED',
        ]);
        $this->logActivity('gaming', 'visit_end', $id, 'Ended gaming session #' . $id . ', total ₹' . $totalAmount);
        return redirect()->back()->with('message', 'Session ended. Total: ₹' . number_format($totalAmount, 2) . '. Mark as paid when the customer pays.');
    }

    public function generateInvoice(int $id): RedirectResponse
    {
        $visit = $this->visitModel->find($id);
        if (! $visit) {
            return redirect()->back()->with('error', 'Session not found.');
        }
        if (($visit['status'] ?? '') !== 'FINISHED') {
            return redirect()->back()->with('error', 'Session must be ended first.');
        }
        if (! empty($visit['invoice_id'])) {
            return redirect()->back()->with('error', 'Invoice already generated.');
        }
        $customerId = (int) ($visit['customer_id'] ?? 0);
        if (! $customerId || ! $this->customerModel->find($customerId)) {
            return redirect()->back()->with('error', 'Session has no valid customer for invoice.');
        }
        $invoiceNumber = 'GINV-' . date('Ymd') . '-' . strtoupper(bin2hex(random_bytes(3)));
        $totalAmount  = (float) ($visit['total_amount'] ?? 0);
        $invoiceId = $this->invoiceModel->insert([
            'invoice_number'   => $invoiceNumber,
            'order_id'         => null,
            'gaming_visit_id'  => $id,
            'customer_id'      => $customerId,
            'subtotal'         => $totalAmount,
            'discount_amount'  => 0.00,
            'tax_amount'       => 0.00,
            'total_amount'     => $totalAmount,
            'status'           => 'PAID',
            'issued_at'        => date('Y-m-d H:i:s'),
        ]);
        $this->visitModel->update($id, ['invoice_id' => $invoiceId]);
        $this->logActivity('gaming', 'invoice_create', (int) $invoiceId, 'Generated invoice ' . $invoiceNumber . ' for gaming session #' . $id);
        return redirect()->back()->with('message', 'Marked paid. Invoice ' . $invoiceNumber . ' generated. You can view it below.');
    }

    /**
     * API: Search customers by name or phone. GET ?q=
     */
    public function apiCustomerSearch(): ResponseInterface
    {
        $q = $this->request->getGet('q');
        if ($q === null || (string) $q === '') {
            return $this->response->setJSON(['customers' => []]);
        }
        $q = trim((string) $q);
        $customers = $this->customerModel->builder()
            ->select('id, name, phone, email')
            ->where('is_active', 1)
            ->groupStart()
            ->like('name', $q)
            ->orLike('phone', $q)
            ->orLike('email', $q)
            ->groupEnd()
            ->orderBy('name', 'asc')
            ->limit(20)
            ->get()
            ->getResultArray();
        return $this->response->setJSON(['customers' => $customers]);
    }
}
