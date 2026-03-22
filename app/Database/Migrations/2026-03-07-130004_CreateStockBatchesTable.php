<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateStockBatchesTable extends Migration
{
    public function up(): void
    {
        $prefix = $this->db->DBPrefix;
        $sql    = <<<SQL
            CREATE TABLE IF NOT EXISTS {$prefix}stock_batches (
                id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
                batch_code VARCHAR(100) NOT NULL,
                product_id BIGINT UNSIGNED NOT NULL,
                vendor_id BIGINT UNSIGNED NOT NULL,
                purchased_qty INT NOT NULL,
                remaining_qty INT NOT NULL,
                unit_cost DECIMAL(12,2) NOT NULL,
                received_at DATETIME NOT NULL,
                remarks VARCHAR(500) DEFAULT NULL,
                created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
                updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                PRIMARY KEY (id),
                UNIQUE KEY uq_stock_batches_code (batch_code),
                KEY idx_stock_batches_product (product_id),
                KEY idx_stock_batches_vendor (vendor_id),
                KEY idx_stock_batches_remaining (remaining_qty),
                CONSTRAINT fk_stock_batches_product
                    FOREIGN KEY (product_id) REFERENCES {$prefix}products(id)
                    ON UPDATE CASCADE ON DELETE RESTRICT,
                CONSTRAINT fk_stock_batches_vendor
                    FOREIGN KEY (vendor_id) REFERENCES {$prefix}vendors(id)
                    ON UPDATE CASCADE ON DELETE RESTRICT
            ) ENGINE=InnoDB
            DEFAULT CHARSET=utf8mb4
            COLLATE=utf8mb4_unicode_ci
            SQL;
        $this->db->query($sql);
    }

    public function down(): void
    {
        $this->forge->dropTable('stock_batches', true);
    }
}
