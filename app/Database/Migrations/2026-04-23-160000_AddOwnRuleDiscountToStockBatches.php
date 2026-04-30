<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * Optional per-unit manual discount for own-rule batch pricing.
 */
class AddOwnRuleDiscountToStockBatches extends Migration
{
    public function up(): void
    {
        $prefix = $this->db->DBPrefix;
        $table  = $prefix . 'stock_batches';
        $cols   = $this->db->getFieldNames($table);
        if (! in_array('own_rule_discount', $cols, true)) {
            $this->db->query("ALTER TABLE `{$table}` ADD COLUMN own_rule_discount DECIMAL(12,2) NULL DEFAULT NULL AFTER selling_price");
        }
    }

    public function down(): void
    {
        $prefix = $this->db->DBPrefix;
        $table  = $prefix . 'stock_batches';
        $cols   = $this->db->getFieldNames($table);
        if (in_array('own_rule_discount', $cols, true)) {
            $this->db->query("ALTER TABLE `{$table}` DROP COLUMN own_rule_discount");
        }
    }
}
