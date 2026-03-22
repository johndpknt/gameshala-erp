<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * Drops selling_price_snapshot from stock_batches if present (schema now matches DDL without that column).
 */
class AlterStockBatchesDropSellingPriceSnapshot extends Migration
{
    public function up(): void
    {
        $prefix = $this->db->DBPrefix;
        $table  = $prefix . 'stock_batches';
        $cols   = $this->db->getFieldNames($table);
        if (in_array('selling_price_snapshot', $cols, true)) {
            $this->db->query("ALTER TABLE `{$table}` DROP COLUMN selling_price_snapshot");
        }
    }

    public function down(): void
    {
        $prefix = $this->db->DBPrefix;
        $table  = $prefix . 'stock_batches';
        $cols   = $this->db->getFieldNames($table);
        if (! in_array('selling_price_snapshot', $cols, true)) {
            $this->db->query("ALTER TABLE `{$table}` ADD COLUMN selling_price_snapshot DECIMAL(12,2) NOT NULL DEFAULT 0.00 AFTER unit_cost");
        }
    }
}
