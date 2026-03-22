<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateProcurementRulesTable extends Migration
{
    public function up(): void
    {
        $sql = <<<'SQL'
            CREATE TABLE IF NOT EXISTS procurement_rules (
                id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
                name VARCHAR(150) NOT NULL,
                discount_type ENUM('FLAT', 'PERCENTAGE') NOT NULL DEFAULT 'FLAT',
                discount_value DECIMAL(10,2) NOT NULL DEFAULT 0.00,
                profit_type ENUM('FLAT', 'PERCENTAGE') NOT NULL DEFAULT 'FLAT',
                profit_value DECIMAL(10,2) NOT NULL DEFAULT 0.00,
                is_active TINYINT(1) NOT NULL DEFAULT 1,
                created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
                updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                PRIMARY KEY (id)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
            SQL;
        $this->db->query($sql);
    }

    public function down(): void
    {
        $this->forge->dropTable('procurement_rules', true);
    }
}
