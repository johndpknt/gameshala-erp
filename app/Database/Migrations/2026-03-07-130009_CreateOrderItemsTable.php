<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateOrderItemsTable extends Migration
{
    public function up(): void
    {
        $sql = <<<'SQL'
            CREATE TABLE IF NOT EXISTS order_items (
                id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
                order_id BIGINT UNSIGNED NOT NULL,
                product_id BIGINT UNSIGNED NOT NULL,
                stock_batch_id BIGINT UNSIGNED DEFAULT NULL,
                qty INT NOT NULL,
                unit_price DECIMAL(12,2) NOT NULL DEFAULT 0.00,
                unit_cost_snapshot DECIMAL(12,2) NOT NULL DEFAULT 0.00,
                discount_amount DECIMAL(12,2) NOT NULL DEFAULT 0.00,
                line_total DECIMAL(12,2) NOT NULL DEFAULT 0.00,
                created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY (id),
                KEY idx_order_items_order (order_id),
                KEY idx_order_items_product (product_id),
                KEY idx_order_items_batch (stock_batch_id),
                CONSTRAINT fk_order_items_order
                    FOREIGN KEY (order_id) REFERENCES orders(id)
                    ON UPDATE CASCADE ON DELETE CASCADE,
                CONSTRAINT fk_order_items_product
                    FOREIGN KEY (product_id) REFERENCES products(id)
                    ON UPDATE CASCADE ON DELETE RESTRICT,
                CONSTRAINT fk_order_items_batch
                    FOREIGN KEY (stock_batch_id) REFERENCES stock_batches(id)
                    ON UPDATE CASCADE ON DELETE SET NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
            SQL;
        $this->db->query($sql);
    }

    public function down(): void
    {
        $this->forge->dropTable('order_items', true);
    }
}
