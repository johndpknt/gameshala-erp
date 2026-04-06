<?php

namespace App\Libraries;

use App\Models\BatchProcurementRuleModel;
use App\Models\ProductModel;
use App\Models\ProcurementRuleModel;
use App\Models\StockBatchModel;

/**
 * FIFO batch selling unit price for FOOD-/BEVE- catalog products (aligned with sales orders).
 */
class ProductCatalogPrice
{
    public function __construct(
        private readonly ProductModel $productModel,
        private readonly StockBatchModel $stockBatchModel,
        private readonly BatchProcurementRuleModel $batchRuleModel,
        private readonly ProcurementRuleModel $procurementRuleModel,
    ) {
    }

    public static function make(): self
    {
        return new self(
            model(ProductModel::class),
            model(StockBatchModel::class),
            model(BatchProcurementRuleModel::class),
            model(ProcurementRuleModel::class),
        );
    }

    /**
     * @return array{found:true, unit_price:float, total_stock:int, product_name:string}|array{found:false, message:string}
     */
    public function unitPriceAndStockForProduct(int $productId): array
    {
        if ($productId <= 0) {
            return ['found' => false, 'message' => 'Invalid product.'];
        }
        $product = $this->productModel->find($productId);
        if (! $product || ! (int) ($product['is_active'] ?? 0)) {
            return ['found' => false, 'message' => 'Product not found.'];
        }
        $sku = (string) ($product['sku'] ?? '');
        if (! ProductModel::skuIsFoodOrBeverage($sku)) {
            return ['found' => false, 'message' => 'Product is not a FOOD-/BEVE- catalog item.'];
        }

        $sumRow = $this->stockBatchModel->builder()
            ->selectSum('remaining_qty', 'qty')
            ->where('product_id', $productId)
            ->get()
            ->getRowArray();
        $totalStock = (int) ($sumRow['qty'] ?? 0);
        if ($totalStock < 1) {
            return ['found' => false, 'message' => 'No stock available.'];
        }

        $batch = $this->stockBatchModel
            ->where('product_id', $productId)
            ->where('remaining_qty >', 0)
            ->orderBy('received_at', 'asc')
            ->first();
        if (! $batch) {
            return ['found' => false, 'message' => 'No stock available.'];
        }

        $unitCost = (float) $batch['unit_cost'];
        if (ProductModel::skuIsFoodOrBeverage($sku)) {
            $rawSp = $batch['selling_price'] ?? null;
            if ($rawSp !== null && $rawSp !== '') {
                return [
                    'found'        => true,
                    'unit_price'   => round((float) $rawSp, 2),
                    'total_stock'  => $totalStock,
                    'product_name' => (string) ($product['name'] ?? ''),
                ];
            }
        }

        $ruleRow = $this->batchRuleModel
            ->where('batch_id', $batch['id'])
            ->where('is_active', 1)
            ->orderBy('id', 'desc')
            ->first();
        if (! $ruleRow) {
            return ['found' => false, 'message' => 'No pricing rule for available batch.'];
        }
        $rule = $this->procurementRuleModel->find($ruleRow['procurement_rule_id']);
        if (! $rule) {
            return ['found' => false, 'message' => 'Pricing rule not found.'];
        }

        return [
            'found'        => true,
            'unit_price'   => $this->computeSellingPrice($unitCost, $rule),
            'total_stock'  => $totalStock,
            'product_name' => (string) ($product['name'] ?? ''),
        ];
    }

    protected function computeSellingPrice(float $unitCost, array $rule): float
    {
        $profitType  = $rule['profit_type'] ?? 'FLAT';
        $profitValue = (float) ($rule['profit_value'] ?? 0);
        $profitAmount = $profitType === 'PERCENTAGE'
            ? $unitCost * ($profitValue / 100)
            : $profitValue;

        return round(max(0, $unitCost + $profitAmount), 2);
    }
}
