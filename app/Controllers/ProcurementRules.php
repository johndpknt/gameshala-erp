<?php

namespace App\Controllers;

use App\Models\ProcurementRuleModel;
use CodeIgniter\HTTP\RedirectResponse;

class ProcurementRules extends BaseController
{
    protected ProcurementRuleModel $procurementRuleModel;

    public function __construct()
    {
        $this->procurementRuleModel = model(ProcurementRuleModel::class);
    }

    /**
     * List procurement rules with optional search by name.
     */
    public function index(): string
    {
        helper('form');
        $q = $this->request->getGet('q');
        $builder = $this->procurementRuleModel->builder();

        if ($q !== null && $q !== '') {
            $builder->like('name', $q);
        }

        $sortCol  = $this->request->getGet('sort');
        $sortOrder = strtolower((string) $this->request->getGet('order')) === 'desc' ? 'desc' : 'asc';
        $allowedSort = ['name', 'discount_type', 'discount_value', 'profit_type', 'profit_value', 'is_active'];
        if ($sortCol !== null && in_array($sortCol, $allowedSort, true)) {
            $builder->orderBy($sortCol, $sortOrder);
        } else {
            $builder->orderBy('name', 'asc');
        }
        $rules = $builder->get()->getResultArray();

        $data = [
            'pageTitle' => 'Procurement Rules - Gameshala ERP',
            'rules'     => $rules,
            'searchQ'   => $q ?? '',
            'sort'      => $sortCol ?? 'name',
            'order'     => $sortCol !== null && in_array($sortCol, $allowedSort, true) ? $sortOrder : 'asc',
        ];

        return view('layout/main', [
            'pageTitle' => $data['pageTitle'],
            'content'   => view('inventory/procurement_rules/index', $data),
        ]);
    }

    /**
     * Add new rule (POST). Rules cannot be edited after creation. is_active defaults to 1.
     */
    public function add(): RedirectResponse
    {
        $rules = [
            'name'           => 'required|max_length[150]',
            'discount_type'  => 'required|in_list[FLAT,PERCENTAGE]',
            'discount_value' => 'required|decimal',
            'profit_type'    => 'required|in_list[FLAT,PERCENTAGE]',
            'profit_value'   => 'required|decimal',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'name'           => $this->request->getPost('name'),
            'discount_type'  => $this->request->getPost('discount_type'),
            'discount_value' => (float) $this->request->getPost('discount_value'),
            'profit_type'    => $this->request->getPost('profit_type'),
            'profit_value'   => (float) $this->request->getPost('profit_value'),
            'is_active'      => 1,
        ];

        $id = $this->procurementRuleModel->insert($data);
        $this->logActivity('inventory', 'procurement_rule_create', (int) $id, 'Created procurement rule: ' . $data['name']);
        return redirect()->back()->with('message', 'Procurement rule added successfully.');
    }

    /**
     * Set rule active (1) or inactive (0). POST.
     * Cannot deactivate if the rule is linked (via batch_procurement_rules) to any stock batch that has remaining_qty > 0.
     */
    public function setStatus(int $id): RedirectResponse
    {
        $rule = $this->procurementRuleModel->find($id);
        if (! $rule) {
            return redirect()->back()->with('error', 'Procurement rule not found.');
        }

        $status = (int) $this->request->getPost('is_active');
        $status = $status === 1 ? 1 : 0;

        if ($status === 0) {
            $db   = $this->procurementRuleModel->db;
            $prefix = $db->DBPrefix;
            $bpr  = $prefix . 'batch_procurement_rules';
            $sb   = $prefix . 'stock_batches';
            $row  = $db->query(
                "SELECT 1 FROM {$bpr} bpr INNER JOIN {$sb} sb ON bpr.batch_id = sb.id WHERE bpr.procurement_rule_id = ? AND bpr.is_active = 1 AND sb.remaining_qty > 0 LIMIT 1",
                [(int) $id]
            )->getRow();

            if ($row !== null) {
                return redirect()->back()->with('error', 'Cannot deactivate this rule: it is linked to one or more stock batches that still have remaining stock.');
            }
        }

        $this->procurementRuleModel->update($id, ['is_active' => $status]);
        $action = $status === 1 ? 'procurement_rule_activate' : 'procurement_rule_deactivate';
        $this->logActivity('inventory', $action, $id, ($status === 1 ? 'Activated' : 'Deactivated') . ' procurement rule: ' . ($rule['name'] ?? '#' . $id));

        $msg = $status === 1 ? 'Rule marked active.' : 'Rule marked inactive.';
        return redirect()->back()->with('message', $msg);
    }
}
