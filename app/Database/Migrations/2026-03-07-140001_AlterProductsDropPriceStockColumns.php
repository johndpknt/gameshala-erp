<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * Drops selling_price, unit_cost, stock_qty from products
 * for databases that were created with the old schema.
 */
class AlterProductsDropPriceStockColumns extends Migration
{
    public function up(): void
    {
        $driver = $this->db->DBDriver;
        if ($driver !== 'MySQLi' && $driver !== 'MySQL') {
            return;
        }
        $prefix = $this->db->DBPrefix;
        $table  = $prefix . 'products';

        // Only alter if columns exist (old schema)
        $row = $this->db->query("SHOW COLUMNS FROM `{$table}` LIKE 'selling_price'")->getRow();
        if ($row !== null) {
            $this->db->query("ALTER TABLE `{$table}` DROP COLUMN selling_price");
            $this->db->query("ALTER TABLE `{$table}` DROP COLUMN unit_cost");
            $this->db->query("ALTER TABLE `{$table}` DROP COLUMN stock_qty");
        }
    }

    public function down(): void
    {
        $prefix = $this->db->DBPrefix;
        $table  = $prefix . 'products';
        $this->db->query("ALTER TABLE `{$table}` ADD COLUMN selling_price DECIMAL(12,2) NOT NULL DEFAULT 0.00 AFTER image_url");
        $this->db->query("ALTER TABLE `{$table}` ADD COLUMN unit_cost DECIMAL(12,2) NOT NULL DEFAULT 0.00 AFTER selling_price");
        $this->db->query("ALTER TABLE `{$table}` ADD COLUMN stock_qty INT NOT NULL DEFAULT 0 AFTER unit");
    }
}
