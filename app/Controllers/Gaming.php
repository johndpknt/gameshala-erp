<?php

namespace App\Controllers;

use App\Libraries\ProductCatalogPrice;
use App\Models\CustomerModel;
use App\Models\FoodBeverageItemModel;
use App\Models\GamingCategoryModel;
use App\Models\GamingControllerModel;
use App\Models\GamingModeModel;
use App\Models\GamingPriceRuleModel;
use App\Models\GamingTitleModel;
use App\Models\GamingVisitFoodItemModel;
use App\Models\GamingVisitModel;
use App\Models\InvoiceModel;
use CodeIgniter\HTTP\RedirectResponse;
use CodeIgniter\HTTP\ResponseInterface;

class Gaming extends BaseController
{
    protected GamingCategoryModel $categoryModel;
    protected GamingControllerModel $controllerModel;
    protected GamingModeModel $modeModel;
    protected GamingPriceRuleModel $priceRuleModel;
    protected GamingTitleModel $titleModel;
    protected FoodBeverageItemModel $foodBeverageItemModel;
    protected GamingVisitModel $visitModel;
    protected GamingVisitFoodItemModel $visitFoodModel;
    protected CustomerModel $customerModel;
    protected InvoiceModel $invoiceModel;

    public function __construct()
    {
        $this->categoryModel        = model(GamingCategoryModel::class);
        $this->controllerModel      = model(GamingControllerModel::class);
        $this->modeModel            = model(GamingModeModel::class);
        $this->priceRuleModel       = model(GamingPriceRuleModel::class);
        $this->titleModel           = model(GamingTitleModel::class);
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
        $prefix     = $this->controllerModel->db->DBPrefix;
        $controllers = $this->controllerModel->builder()
            ->select('gaming_controllers.*, gc.name AS category_name')
            ->join($prefix . 'gaming_categories gc', 'gc.id = gaming_controllers.gaming_category_id', 'left')
            ->orderBy('gaming_controllers.name', 'asc')
            ->get()
            ->getResultArray();
        $titles = $this->titleModel->builder()
            ->select('gaming_titles.*, gct.name AS controller_name, gc.name AS category_name')
            ->join($prefix . 'gaming_controllers gct', 'gct.id = gaming_titles.gaming_controller_id', 'left')
            ->join($prefix . 'gaming_categories gc', 'gc.id = gct.gaming_category_id', 'left')
            ->orderBy('gct.name', 'asc')
            ->orderBy('gaming_titles.name', 'asc')
            ->get()
            ->getResultArray();

        return view('layout/main', [
            'pageTitle' => 'Categories - Gaming',
            'content'   => view('gaming/categories', [
                'categories' => $categories,
                'controllers' => $controllers,
                'titles'    => $titles,
                'modes'     => $modes,
            ]),
        ]);
    }

    public function addController(): RedirectResponse
    {
        $rules = [
            'name'               => 'required|max_length[50]',
            'gaming_category_id' => 'required|integer',
        ];
        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $name = strtoupper(trim((string) $this->request->getPost('name')));
        $categoryId = (int) $this->request->getPost('gaming_category_id');
        if (! $this->categoryModel->find($categoryId)) {
            return redirect()->back()->with('error', 'Gaming category not found.');
        }

        $id = $this->controllerModel->insert([
            'name'               => $name,
            'gaming_category_id' => $categoryId,
            'is_active'          => 1,
        ]);
        if ($id === false) {
            $dbError = $this->controllerModel->db->error();
            if ((int) ($dbError['code'] ?? 0) === 1062) {
                return redirect()->back()->withInput()->with('error', 'Controller name already exists.');
            }
            return redirect()->back()->withInput()->with('error', 'Could not add controller name.');
        }

        $this->logActivity('gaming', 'controller_create', (int) $id, 'Created gaming controller: ' . $name);
        return redirect()->back()->with('message', 'Controller name added.');
    }

    public function updateController(int $id): RedirectResponse
    {
        $controller = $this->controllerModel->find($id);
        if (! $controller) {
            return redirect()->back()->with('error', 'Controller name not found.');
        }

        $rules = [
            'name'               => 'required|max_length[50]',
            'gaming_category_id' => 'required|integer',
        ];
        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $name = strtoupper(trim((string) $this->request->getPost('name')));
        $categoryId = (int) $this->request->getPost('gaming_category_id');
        if (! $this->categoryModel->find($categoryId)) {
            return redirect()->back()->with('error', 'Gaming category not found.');
        }

        $ok = $this->controllerModel->update($id, [
            'name'               => $name,
            'gaming_category_id' => $categoryId,
        ]);
        if ($ok === false) {
            $dbError = $this->controllerModel->db->error();
            if ((int) ($dbError['code'] ?? 0) === 1062) {
                return redirect()->back()->withInput()->with('error', 'Controller name already exists.');
            }
            return redirect()->back()->withInput()->with('error', 'Could not update controller name.');
        }

        $this->logActivity('gaming', 'controller_update', $id, 'Updated gaming controller: ' . $name);
        return redirect()->back()->with('message', 'Controller name updated.');
    }

    public function setStatusController(int $id): RedirectResponse
    {
        $controller = $this->controllerModel->find($id);
        if (! $controller) {
            return redirect()->back()->with('error', 'Controller name not found.');
        }

        $status = (int) $this->request->getPost('is_active');
        $status = $status === 1 ? 1 : 0;
        $this->controllerModel->update($id, ['is_active' => $status]);
        $action = $status === 1 ? 'controller_activate' : 'controller_deactivate';
        $this->logActivity(
            'gaming',
            $action,
            $id,
            ($status === 1 ? 'Activated' : 'Deactivated') . ' gaming controller: ' . ($controller['name'] ?? '#' . $id)
        );
        return redirect()->back()->with('message', $status === 1 ? 'Controller marked active.' : 'Controller marked inactive.');
    }

    public function addTitle(): RedirectResponse
    {
        $rules = [
            'name'                 => 'required|max_length[150]',
            'gaming_controller_id' => 'required|integer',
        ];
        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $name = trim((string) $this->request->getPost('name'));
        $controllerId = (int) $this->request->getPost('gaming_controller_id');
        if (! $this->controllerModel->find($controllerId)) {
            return redirect()->back()->with('error', 'Controller name not found.');
        }

        $id = $this->titleModel->insert([
            'name'                 => $name,
            'gaming_controller_id' => $controllerId,
            'is_active'            => 1,
        ]);
        if ($id === false) {
            $dbError = $this->titleModel->db->error();
            if ((int) ($dbError['code'] ?? 0) === 1062) {
                return redirect()->back()->withInput()->with('error', 'Gaming title already exists for this controller.');
            }
            return redirect()->back()->withInput()->with('error', 'Could not add gaming title.');
        }

        $this->logActivity('gaming', 'title_create', (int) $id, 'Created gaming title: ' . $name);
        return redirect()->back()->with('message', 'Gaming title added.');
    }

    public function updateTitle(int $id): RedirectResponse
    {
        $title = $this->titleModel->find($id);
        if (! $title) {
            return redirect()->back()->with('error', 'Gaming title not found.');
        }

        $rules = [
            'name'                 => 'required|max_length[150]',
            'gaming_controller_id' => 'required|integer',
        ];
        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $name = trim((string) $this->request->getPost('name'));
        $controllerId = (int) $this->request->getPost('gaming_controller_id');
        if (! $this->controllerModel->find($controllerId)) {
            return redirect()->back()->with('error', 'Controller name not found.');
        }

        $ok = $this->titleModel->update($id, [
            'name'                 => $name,
            'gaming_controller_id' => $controllerId,
        ]);
        if ($ok === false) {
            $dbError = $this->titleModel->db->error();
            if ((int) ($dbError['code'] ?? 0) === 1062) {
                return redirect()->back()->withInput()->with('error', 'Gaming title already exists for this controller.');
            }
            return redirect()->back()->withInput()->with('error', 'Could not update gaming title.');
        }

        $this->logActivity('gaming', 'title_update', $id, 'Updated gaming title: ' . $name);
        return redirect()->back()->with('message', 'Gaming title updated.');
    }

    public function setStatusTitle(int $id): RedirectResponse
    {
        $title = $this->titleModel->find($id);
        if (! $title) {
            return redirect()->back()->with('error', 'Gaming title not found.');
        }

        $status = (int) $this->request->getPost('is_active');
        $status = $status === 1 ? 1 : 0;
        $this->titleModel->update($id, ['is_active' => $status]);
        $action = $status === 1 ? 'title_activate' : 'title_deactivate';
        $this->logActivity(
            'gaming',
            $action,
            $id,
            ($status === 1 ? 'Activated' : 'Deactivated') . ' gaming title: ' . ($title['name'] ?? '#' . $id)
        );
        return redirect()->back()->with('message', $status === 1 ? 'Gaming title marked active.' : 'Gaming title marked inactive.');
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
                'categories'       => $categories,
                'modes'            => $modes,
                'rules'            => $rules,
                'priceTypeLabels'  => GamingPriceRuleModel::priceTypeLabels(),
            ]),
        ]);
    }

    public function addPriceRule(): RedirectResponse
    {
        $rules = [
            'gaming_category_id' => 'required|integer',
            'gaming_mode_id'    => 'required|integer',
            'price_type'        => 'required|in_list[' . GamingPriceRuleModel::priceTypeValidationList() . ']',
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
            'price_type'         => 'required|in_list[' . GamingPriceRuleModel::priceTypeValidationList() . ']',
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
                ->select('gaming_visit_food_items.*, fbi.name AS fbi_name, fbi.unit_label, p.name AS product_name, COALESCE(fbi.name, p.name) AS item_name')
                ->join($prefix . 'food_beverage_items fbi', 'fbi.id = gaming_visit_food_items.food_beverage_item_id', 'left')
                ->join($prefix . 'products p', 'p.id = gaming_visit_food_items.product_id', 'left')
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
                'priceTypeLabels'      => GamingPriceRuleModel::priceTypeLabels(),
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
            $newPhone = trim($newPhone);
            if ($this->customerModel->findOtherByPhoneComparable($newPhone, null) !== null) {
                return redirect()->back()->withInput()->with(
                    'error',
                    'This phone number is already registered. Search and select that customer, or use a different phone.'
                );
            }
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
        $visitId   = (int) $this->request->getPost('gaming_visit_id');
        $itemId    = (int) $this->request->getPost('food_beverage_item_id');
        $productId = (int) $this->request->getPost('product_id');
        $qtyOwn    = max(1, (int) $this->request->getPost('quantity_own'));
        $qtyVendor = max(1, (int) $this->request->getPost('quantity_vendor'));
        $visit = $this->visitModel->find($visitId);
        if (! $visit || ($visit['status'] ?? '') !== 'ONGOING') {
            return redirect()->back()->with('error', 'Session not found or not ongoing.');
        }
        if ($itemId < 1 && $productId < 1) {
            return redirect()->back()->with('error', 'Select an own menu item and/or a vendor catalog product.');
        }
        $ownItem = null;
        if ($itemId > 0) {
            $ownItem = $this->foodBeverageItemModel->find($itemId);
            if (! $ownItem || ! (int) ($ownItem['is_active'] ?? 1)) {
                return redirect()->back()->with('error', 'Own menu item not found.');
            }
        }
        $pricedCatalog = null;
        if ($productId > 0) {
            $pricedCatalog = ProductCatalogPrice::make()->unitPriceAndStockForProduct($productId);
            if (! $pricedCatalog['found']) {
                return redirect()->back()->with('error', $pricedCatalog['message'] ?? 'Could not price catalog product.');
            }
            if ($qtyVendor > $pricedCatalog['total_stock']) {
                return redirect()->back()->with('error', 'Quantity exceeds available stock (' . $pricedCatalog['total_stock'] . ').');
            }
        }
        if ($productId > 0) {
            $lineTotal = round($pricedCatalog['unit_price'] * $qtyVendor, 2);
            $vendorLine = [
                'gaming_visit_id'       => $visitId,
                'food_beverage_item_id' => null,
                'product_id'            => $productId,
                'quantity'              => $qtyVendor,
                'line_total'            => $lineTotal,
            ];
            try {
                $this->visitFoodModel->insert($vendorLine);
            } catch (\Throwable $e) {
                // Backward-compatible fallback for environments where food_beverage_item_id is still NOT NULL.
                $fallbackItemId = $this->resolveCatalogFoodItemId(
                    $productId,
                    (string) ($pricedCatalog['product_name'] ?? ('Product #' . $productId)),
                    (float) $pricedCatalog['unit_price']
                );
                if ($fallbackItemId < 1) {
                    return redirect()->back()->with('error', 'Could not map catalog product for session item.');
                }

                $vendorLine['food_beverage_item_id'] = $fallbackItemId;
                $ok = $this->visitFoodModel->insert($vendorLine);
                if ($ok === false) {
                    return redirect()->back()->with('error', 'Could not add catalog item to session.');
                }
            }
            $this->logActivity('gaming', 'visit_food_add', $visitId, 'Added catalog food/beverage to session #' . $visitId);
        }
        if ($itemId > 0 && $ownItem !== null) {
            $lineTotal = round((float) $ownItem['price'] * $qtyOwn, 2);
            $this->visitFoodModel->insert([
                'gaming_visit_id'       => $visitId,
                'food_beverage_item_id' => $itemId,
                'product_id'            => null,
                'quantity'              => $qtyOwn,
                'line_total'            => $lineTotal,
            ]);
            $this->logActivity('gaming', 'visit_food_add', $visitId, 'Added food/beverage to session #' . $visitId);
        }
        $msg = ($productId > 0 && $itemId > 0) ? 'Both items added to session.' : 'Item added to session.';
        return redirect()->back()->with('message', $msg);
    }

    /**
     * Find or create a synthetic food item representing a vendor catalog product.
     * Needed for deployments where gaming_visit_food_items.food_beverage_item_id is NOT NULL.
     */
    protected function resolveCatalogFoodItemId(int $productId, string $productName, float $unitPrice): int
    {
        $syntheticName = '[Catalog] ' . trim($productName) . ' (#' . $productId . ')';
        $existing = $this->foodBeverageItemModel->where('name', $syntheticName)->first();
        if ($existing) {
            return (int) ($existing['id'] ?? 0);
        }

        $id = $this->foodBeverageItemModel->insert([
            'name'       => $syntheticName,
            'unit_label' => 'unit',
            'price'      => $unitPrice,
            'is_active'  => 1,
        ]);

        return $id === false ? 0 : (int) $id;
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
        $price       = (float) $rule['price'];
        $priceType   = (string) ($rule['price_type'] ?? 'FIXED');
        $gamingAmount = GamingPriceRuleModel::computeGamingAmountFromDuration($minutes, $price, $priceType);

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
