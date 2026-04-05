<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * Optional fixed selling price on a batch (used for BEVE-* / FOOD-* SKUs instead of procurement rules).
 */
class AddSellingPriceToStockBatches extends Migration
{
    public function up(): void
    {
        $prefix = $this->db->DBPrefix;
        $table  = $prefix . 'stock_batches';
        $cols   = $this->db->getFieldNames($table);
        if (! in_array('selling_price', $cols, true)) {
            $this->db->query("ALTER TABLE `{$table}` ADD COLUMN selling_price DECIMAL(12,2) NULL DEFAULT NULL AFTER unit_cost");
        }
    }

    public function down(): void
    {
        $prefix = $this->db->DBPrefix;
        $table  = $prefix . 'stock_batches';
        $cols   = $this->db->getFieldNames($table);
        if (in_array('selling_price', $cols, true)) {
            $this->db->query("ALTER TABLE `{$table}` DROP COLUMN selling_price");
        }
    }
}
