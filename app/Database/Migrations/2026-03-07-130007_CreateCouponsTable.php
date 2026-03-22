<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateCouponsTable extends Migration
{
    public function up(): void
    {
        $sql = <<<'SQL'
            CREATE TABLE IF NOT EXISTS coupons (
                id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
                code VARCHAR(100) NOT NULL,
                discount_type ENUM('FLAT', 'PERCENTAGE') NOT NULL DEFAULT 'FLAT',
                discount_value DECIMAL(12,2) NOT NULL DEFAULT 0.00,
                min_order_amount DECIMAL(12,2) DEFAULT NULL,
                max_discount_amount DECIMAL(12,2) DEFAULT NULL,
                usage_limit INT DEFAULT NULL,
                used_count INT NOT NULL DEFAULT 0,
                valid_from DATETIME NOT NULL,
                valid_to DATETIME NOT NULL,
                is_active TINYINT(1) NOT NULL DEFAULT 1,
                created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
                updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                PRIMARY KEY (id),
                UNIQUE KEY uq_coupons_code (code),
                KEY idx_coupons_active_dates (is_active, valid_from, valid_to)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
            SQL;
        $this->db->query($sql);
    }

    public function down(): void
    {
        $this->forge->dropTable('coupons', true);
    }
}
