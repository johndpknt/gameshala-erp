<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateOrdersTable extends Migration
{
    public function up(): void
    {
        $sql = <<<'SQL'
            CREATE TABLE IF NOT EXISTS orders (
                id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
                order_number VARCHAR(100) NOT NULL,
                customer_id BIGINT UNSIGNED NOT NULL,
                status ENUM('PENDING', 'CONFIRMED', 'PAID', 'CANCELLED') NOT NULL DEFAULT 'PENDING',
                subtotal DECIMAL(12,2) NOT NULL DEFAULT 0.00,
                discount_amount DECIMAL(12,2) NOT NULL DEFAULT 0.00,
                tax_amount DECIMAL(12,2) NOT NULL DEFAULT 0.00,
                total_amount DECIMAL(12,2) NOT NULL DEFAULT 0.00,
                coupon_id BIGINT UNSIGNED DEFAULT NULL,
                shipping_name VARCHAR(150) DEFAULT NULL,
                shipping_phone VARCHAR(30) DEFAULT NULL,
                shipping_address VARCHAR(255) DEFAULT NULL,
                created_via ENUM('API', 'ADMIN') NOT NULL DEFAULT 'API',
                created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
                updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                PRIMARY KEY (id),
                UNIQUE KEY uq_orders_order_number (order_number),
                KEY idx_orders_customer (customer_id),
                KEY idx_orders_status (status),
                KEY idx_orders_coupon (coupon_id),
                KEY idx_orders_created_at (created_at),
                CONSTRAINT fk_orders_customer
                    FOREIGN KEY (customer_id) REFERENCES customers(id)
                    ON UPDATE CASCADE ON DELETE RESTRICT,
                CONSTRAINT fk_orders_coupon
                    FOREIGN KEY (coupon_id) REFERENCES coupons(id)
                    ON UPDATE CASCADE ON DELETE SET NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
            SQL;
        $this->db->query($sql);
    }

    public function down(): void
    {
        $this->forge->dropTable('orders', true);
    }
}
