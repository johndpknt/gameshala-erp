<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateStockMovementsTable extends Migration
{
    public function up(): void
    {
        $sql = <<<'SQL'
            CREATE TABLE IF NOT EXISTS stock_movements (
                id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
                product_id BIGINT UNSIGNED NOT NULL,
                batch_id BIGINT UNSIGNED DEFAULT NULL,
                movement_type ENUM('PURCHASE', 'SALE', 'ADJUSTMENT', 'RETURN_IN', 'RETURN_OUT') NOT NULL,
                qty_in INT NOT NULL DEFAULT 0,
                qty_out INT NOT NULL DEFAULT 0,
                reference_type VARCHAR(50) DEFAULT NULL,
                reference_id BIGINT UNSIGNED DEFAULT NULL,
                note VARCHAR(255) DEFAULT NULL,
                created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY (id),
                KEY idx_stock_movements_product (product_id),
                KEY idx_stock_movements_batch (batch_id),
                KEY idx_stock_movements_type (movement_type),
                KEY idx_stock_movements_reference (reference_type, reference_id),
                KEY idx_stock_movements_created_at (created_at),
                CONSTRAINT fk_stock_movements_product
                    FOREIGN KEY (product_id) REFERENCES products(id)
                    ON UPDATE CASCADE ON DELETE RESTRICT,
                CONSTRAINT fk_stock_movements_batch
                    FOREIGN KEY (batch_id) REFERENCES stock_batches(id)
                    ON UPDATE CASCADE ON DELETE SET NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
            SQL;
        $this->db->query($sql);
    }

    public function down(): void
    {
        $this->forge->dropTable('stock_movements', true);
    }
}
