<?php

namespace App\Controllers;

use App\Models\StockMovementModel;
use App\Models\ProductModel;
use App\Models\StockBatchModel;

class StockMovements extends BaseController
{
    protected StockMovementModel $stockMovementModel;
    protected ProductModel $productModel;
    protected StockBatchModel $stockBatchModel;

    public function __construct()
    {
        $this->stockMovementModel = model(StockMovementModel::class);
        $this->productModel       = model(ProductModel::class);
        $this->stockBatchModel    = model(StockBatchModel::class);
    }

    /**
     * List stock movements with optional sort on all columns.
     */
    public function index(): string
    {
        helper('form');
        $db     = $this->stockMovementModel->db;
        $prefix = $db->DBPrefix;
        $sm     = $prefix . 'stock_movements';
        $p      = $prefix . 'products';
        $sb     = $prefix . 'stock_batches';

        $sortCol   = $this->request->getGet('sort');
        $sortOrder = strtolower((string) $this->request->getGet('order')) === 'desc' ? 'desc' : 'asc';
        $allowedSort = ['created_at', 'product_name', 'batch_code', 'movement_type', 'qty_in', 'qty_out', 'reference_type', 'note'];
        $builder = $this->stockMovementModel->builder()
            ->select("{$sm}.*, {$p}.name AS product_name, {$sb}.batch_code AS batch_code")
            ->join($p, "{$sm}.product_id = {$p}.id", 'left')
            ->join($sb, "{$sm}.batch_id = {$sb}.id", 'left');

        if ($sortCol !== null && in_array($sortCol, $allowedSort, true)) {
            $orderCol = match ($sortCol) {
                'product_name' => "{$p}.name",
                'batch_code' => "{$sb}.batch_code",
                default => "{$sm}.{$sortCol}",
            };
            $builder->orderBy($orderCol, $sortOrder);
        } else {
            $builder->orderBy("{$sm}.created_at", 'desc');
        }
        $movements = $builder->get()->getResultArray();

        return view('layout/main', [
            'pageTitle' => 'Stock Movements - Gameshala ERP',
            'content'   => view('inventory/stock_movements/index', [
                'movements' => $movements,
                'sort'      => $sortCol ?? 'created_at',
                'order'     => $sortCol !== null && in_array($sortCol, $allowedSort, true) ? $sortOrder : 'desc',
            ]),
        ]);
    }
}
