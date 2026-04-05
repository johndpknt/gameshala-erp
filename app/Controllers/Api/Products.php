<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\BatchProcurementRuleModel;
use App\Models\ProductModel;
use App\Models\ProcurementRuleModel;
use App\Models\StockBatchModel;
use CodeIgniter\HTTP\ResponseInterface;

/**
 * API: List products with search, sort, filters, pagination.
 * Selling price, list price, discount and stock are derived from stock_batches and procurement rules.
 */
class Products extends BaseController
{
    protected ProductModel $productModel;
    protected StockBatchModel $stockBatchModel;
    protected BatchProcurementRuleModel $batchRuleModel;
    protected ProcurementRuleModel $procurementRuleModel;

    public function __construct()
    {
        $this->productModel        = model(ProductModel::class);
        $this->stockBatchModel     = model(StockBatchModel::class);
        $this->batchRuleModel      = model(BatchProcurementRuleModel::class);
        $this->procurementRuleModel = model(ProcurementRuleModel::class);
    }

    /**
     * GET api/products
     * Query params: q (search), sort, order, page, per_page, is_active, is_public, in_stock, min_price, max_price
     *
     * per_page: number (1–100), "all"/"-1" for every row, or SKU tokens (comma / whitespace / encoded & as %26).
     * Unencoded "beve&food" in the URL becomes per_page=beve plus a separate param "food"; that case is detected
     * so both BEVE- and FOOD- SKUs are included. Prefer per_page=beve,food or per_page=beve%26food when possible.
     */
    public function index(): ResponseInterface
    {
        $q       = $this->request->getGet('q');
        $sort    = $this->request->getGet('sort');
        $order   = strtolower((string) $this->request->getGet('order')) === 'desc' ? 'desc' : 'asc';
        $page    = max(1, (int) $this->request->getGet('page'));
        $perPageParam = (string) ($this->request->getGet('per_page') ?? '');
        $perPageAll   = in_array(strtolower(trim($perPageParam)), ['all', '-1'], true);
        $perPage      = $perPageAll ? 0 : min(100, max(1, (int) $this->request->getGet('per_page') ?: 20));

        $allowedSort = ['id', 'sku', 'name', 'slug', 'unit', 'is_public', 'is_active', 'created_at', 'updated_at'];
        if ($sort === null || ! in_array($sort, $allowedSort, true)) {
            $sort = 'name';
        }

        $builder = $this->productModel->builder();

        if (! $perPageAll) {
            $skuPrefixes = $this->skuPrefixesFromPerPageParam(trim($perPageParam));
            $skuPrefixes = $this->mergeSkuPrefixesForBeveAndFoodQuery(trim($perPageParam), $skuPrefixes);
            if ($skuPrefixes !== []) {
                $builder->groupStart();
                foreach ($skuPrefixes as $i => $prefix) {
                    if ($i === 0) {
                        $builder->like('sku', $prefix, 'after');
                    } else {
                        $builder->orLike('sku', $prefix, 'after');
                    }
                }
                $builder->groupEnd();
            }
        }

        if ($q !== null && $q !== '') {
            $builder->groupStart()
                ->like('name', $q)
                ->orLike('sku', $q)
                ->orLike('slug', $q)
                ->orLike('description', $q)
                ->groupEnd();
        }

        $isActive = $this->request->getGet('is_active');
        if ($isActive !== null && $isActive !== '') {
            $builder->where('is_active', $isActive === '1' || $isActive === 'true' ? 1 : 0);
        }

        $isPublic = $this->request->getGet('is_public');
        if ($isPublic !== null && $isPublic !== '') {
            $builder->where('is_public', $isPublic === '1' || $isPublic === 'true' ? 1 : 0);
        }

        $inStock = $this->request->getGet('in_stock');
        if ($inStock === '1' || $inStock === 'true') {
            $prefix = $this->productModel->db->DBPrefix;
            $sub = $this->productModel->db->table($prefix . 'stock_batches')
                ->select('product_id')
                ->where('remaining_qty >', 0)
                ->getCompiledSelect();
            $builder->where("id IN ({$sub})", null, false);
        }

        $total = $builder->countAllResults(false);
        $builder->orderBy($sort, $order);
        if ($perPageAll) {
            $page    = 1;
            $perPage = max(1, (int) $total);
            $rows    = $builder->get()->getResultArray();
        } else {
            $offset = ($page - 1) * $perPage;
            $rows   = $builder->get($perPage, $offset)->getResultArray();
        }

        $productIds = array_column($rows, 'id');
        $stockPriceMap = $this->getStockAndPriceForProducts($productIds);

        $minPrice = $this->request->getGet('min_price');
        $maxPrice = $this->request->getGet('max_price');
        if ($minPrice !== null && $minPrice !== '') {
            $minPrice = (float) $minPrice;
        } else {
            $minPrice = null;
        }
        if ($maxPrice !== null && $maxPrice !== '') {
            $maxPrice = (float) $maxPrice;
        } else {
            $maxPrice = null;
        }

        $data = [];
        foreach ($rows as $p) {
            $id   = (int) $p['id'];
            $info = $stockPriceMap[$id] ?? ['stock_qty' => 0, 'selling_price' => null, 'list_price' => null, 'discount_percent' => null];
            $sellingPrice = $info['selling_price'];
            if ($minPrice !== null && ($sellingPrice === null || $sellingPrice < $minPrice)) {
                continue;
            }
            if ($maxPrice !== null && ($sellingPrice === null || $sellingPrice > $maxPrice)) {
                continue;
            }
            $data[] = $this->formatProduct($p, $info);
        }

        $totalPages = $perPage > 0 ? (int) ceil($total / $perPage) : 0;

        return $this->response->setJSON([
            'data' => $data,
            'meta' => [
                'total'        => $total,
                'page'         => $page,
                'per_page'     => $perPage,
                'total_pages'  => $totalPages,
            ],
        ]);
    }

    /**
     * When per_page is not numeric and not "all"/"-1", treat it as SKU family tokens (comma, &, or whitespace).
     * Maps beve → BEVE-, food → FOOD- for LIKE 'PREFIX%' on sku.
     *
     * @return list<string>
     */
    protected function skuPrefixesFromPerPageParam(string $perPageParam): array
    {
        if ($perPageParam === '' || ctype_digit($perPageParam)) {
            return [];
        }

        $map = [
            'beve' => 'BEVE-',
            'food' => 'FOOD-',
        ];

        $tokens = preg_split('/[,;&\s]+/', $perPageParam, -1, PREG_SPLIT_NO_EMPTY);
        if ($tokens === false) {
            return [];
        }

        $prefixes = [];
        foreach ($tokens as $token) {
            $key = strtolower(trim($token));
            if ($key !== '' && isset($map[$key])) {
                $prefixes[] = $map[$key];
            }
        }

        return array_values(array_unique($prefixes));
    }

    /**
     * If the client sends an unencoded URL like ?per_page=beve&food, PHP only sees per_page=beve and a separate "food"
     * query key. Treat per_page=beve + presence of food (any value, including empty) as both prefixes; same for food + beve.
     *
     * @param list<string> $prefixes
     * @return list<string>
     */
    protected function mergeSkuPrefixesForBeveAndFoodQuery(string $trimmedPerPage, array $prefixes): array
    {
        $t = strtolower($trimmedPerPage);
        $get = $this->request->getGet();
        if (! is_array($get)) {
            return $prefixes;
        }

        if ($t === 'beve' && array_key_exists('food', $get) && ! in_array('FOOD-', $prefixes, true)) {
            $prefixes[] = 'FOOD-';
        }
        if ($t === 'food' && array_key_exists('beve', $get) && ! in_array('BEVE-', $prefixes, true)) {
            $prefixes[] = 'BEVE-';
        }

        return array_values(array_unique($prefixes));
    }

    /**
     * For given product IDs, return map: product_id => [ stock_qty, selling_price, list_price, discount_type, discount_value, rule_name, ... ]
     */
    protected function getStockAndPriceForProducts(array $productIds): array
    {
        if ($productIds === []) {
            return [];
        }

        $prefix = $this->stockBatchModel->db->DBPrefix;
        $sb     = $prefix . 'stock_batches';

        $batches = $this->stockBatchModel->db->table($sb)
            ->select("{$sb}.id, {$sb}.product_id, {$sb}.remaining_qty, {$sb}.unit_cost, {$sb}.received_at")
            ->whereIn('product_id', $productIds)
            ->where('remaining_qty >', 0)
            ->orderBy('product_id')
            ->orderBy('received_at', 'asc')
            ->get()
            ->getResultArray();

        $productStock = [];
        $batchIds = [];
        $firstBatchByProduct = [];
        foreach ($batches as $b) {
            $pid = (int) $b['product_id'];
            $productStock[$pid] = ($productStock[$pid] ?? 0) + (int) $b['remaining_qty'];
            if (! isset($firstBatchByProduct[$pid])) {
                $firstBatchByProduct[$pid] = $b;
                $batchIds[] = $b['id'];
            }
        }

        $rulesByBatch = [];
        if ($batchIds !== []) {
            $rules = $this->batchRuleModel->builder()
                ->whereIn('batch_id', $batchIds)
                ->where('is_active', 1)
                ->orderBy('id', 'desc')
                ->get()
                ->getResultArray();
            $ruleIds = array_unique(array_column($rules, 'procurement_rule_id'));
            $ruleRows = $ruleIds !== [] ? $this->procurementRuleModel->builder()->whereIn('id', $ruleIds)->get()->getResultArray() : [];
            $ruleMap = [];
            foreach ($ruleRows as $r) {
                $ruleMap[(int) $r['id']] = $r;
            }
            foreach ($rules as $r) {
                $bid = (int) $r['batch_id'];
                if (! isset($rulesByBatch[$bid])) {
                    $rulesByBatch[$bid] = $ruleMap[(int) $r['procurement_rule_id']] ?? null;
                }
            }
        }

        $result = [];
        foreach ($productIds as $pid) {
            $stockQty = $productStock[$pid] ?? 0;
            $sellingPrice = null;
            $listPrice = null;
            $discountType = null;
            $discountValue = null;
            $discountPercent = null;
            $ruleName = null;
            $first = $firstBatchByProduct[$pid] ?? null;
            if ($first) {
                $rule = $rulesByBatch[(int) $first['id']] ?? null;
                if ($rule) {
                    $unitCost = (float) $first['unit_cost'];
                    $sellingPrice = $this->computeSellingPriceFromRule($unitCost, $rule);
                    $listPrice    = $this->computeListPriceFromRule($sellingPrice, $rule);
                    $discountType = $rule['discount_type'] ?? 'FLAT';
                    $discountValue = (float) ($rule['discount_value'] ?? 0);
                    $ruleName = $rule['name'] ?? null;
                    if ($discountType === 'PERCENTAGE' && $discountValue > 0 && $discountValue < 100) {
                        $discountPercent = round($discountValue, 2);
                    }
                } else {
                    $listPrice = $sellingPrice;
                }
            }
            $result[$pid] = [
                'stock_qty'         => $stockQty,
                'selling_price'    => $sellingPrice,
                'list_price'       => $listPrice,
                'discount_type'    => $discountType,
                'discount_value'   => $discountValue,
                'discount_percent' => $discountPercent,
                'rule_name'        => $ruleName,
            ];
        }
        return $result;
    }

    /**
     * Selling price from rule: unit cost + profit.
     * Profit = FLAT amount or PERCENTAGE of unit cost (same as Orders::computeSellingPrice).
     */
    protected function computeSellingPriceFromRule(float $unitCost, array $rule): float
    {
        $profitType  = $rule['profit_type'] ?? 'FLAT';
        $profitValue = (float) ($rule['profit_value'] ?? 0);
        $profitAmount = $profitType === 'PERCENTAGE'
            ? $unitCost * ($profitValue / 100)
            : $profitValue;
        return round(max(0, $unitCost + $profitAmount), 2);
    }

    /**
     * List price from rule: the "original" price such that applying the rule's discount gives selling price.
     * PERCENTAGE: selling = list * (1 - discount_value/100)  =>  list = selling / (1 - discount_value/100).
     * FLAT: selling = list - discount_value  =>  list = selling + discount_value.
     * No discount or zero => list = selling.
     */
    protected function computeListPriceFromRule(?float $sellingPrice, array $rule): ?float
    {
        if ($sellingPrice === null) {
            return null;
        }
        $discountType  = $rule['discount_type'] ?? 'FLAT';
        $discountValue = (float) ($rule['discount_value'] ?? 0);
        if ($discountType === 'PERCENTAGE' && $discountValue > 0 && $discountValue < 100) {
            return round($sellingPrice / (1 - $discountValue / 100), 2);
        }
        if ($discountType === 'FLAT' && $discountValue > 0) {
            return round($sellingPrice + $discountValue, 2);
        }
        return round($sellingPrice, 2);
    }

    protected function formatProduct(array $p, array $info): array
    {
        $sellingPrice = $info['selling_price'];
        $stockQty     = $info['stock_qty'];
        $relativeUrls = ProductModel::imageUrlToArray($p['image_url'] ?? null);
        $imageUrls    = array_map(static fn (string $path): string => base_url($path), $relativeUrls);
        return [
            'id'               => (int) $p['id'],
            'sku'              => $p['sku'] ?? '',
            'name'             => $p['name'] ?? '',
            'slug'             => $p['slug'] ?? '',
            'description'      => $p['description'] ?? null,
            'image_urls'       => $imageUrls,
            'unit'             => $p['unit'] ?? 'PCS',
            'is_public'        => (int) ($p['is_public'] ?? 1),
            'is_active'        => (int) ($p['is_active'] ?? 1),
            'selling_price'    => $sellingPrice,
            'list_price'       => $info['list_price'] ?? $sellingPrice,
            'discount_type'    => $info['discount_type'],
            'discount_value'   => $info['discount_value'],
            'discount_percent' => $info['discount_percent'],
            'rule_name'        => $info['rule_name'],
            'stock_qty'        => $stockQty,
            'in_stock'         => $stockQty > 0,
            'created_at'       => $p['created_at'] ?? null,
            'updated_at'       => $p['updated_at'] ?? null,
        ];
    }
}
