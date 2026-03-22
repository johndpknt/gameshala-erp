<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateCustomersTable extends Migration
{
    public function up(): void
    {
        $prefix = $this->db->DBPrefix;
        $sql    = <<<SQL
            CREATE TABLE IF NOT EXISTS {$prefix}customers (
                id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
                customer_type ENUM('INDIVIDUAL', 'BUSINESS', 'WALK_IN') NOT NULL DEFAULT 'INDIVIDUAL',
                name VARCHAR(150) NOT NULL,
                phone VARCHAR(30) NOT NULL,
                email VARCHAR(191) DEFAULT NULL,
                address_line1 VARCHAR(255) DEFAULT NULL,
                address_line2 VARCHAR(255) DEFAULT NULL,
                city VARCHAR(100) DEFAULT NULL,
                state VARCHAR(100) DEFAULT NULL,
                postal_code VARCHAR(20) DEFAULT NULL,
                tax_number VARCHAR(100) DEFAULT NULL,
                notes VARCHAR(500) DEFAULT NULL,
                is_active TINYINT(1) NOT NULL DEFAULT 1,
                created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
                updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                PRIMARY KEY (id),
                KEY idx_customers_type (customer_type),
                KEY idx_customers_name (name),
                KEY idx_customers_phone (phone),
                KEY idx_customers_email (email)
            ) ENGINE=InnoDB
            DEFAULT CHARSET=utf8mb4
            COLLATE=utf8mb4_unicode_ci
            SQL;
        $this->db->query($sql);
    }

    public function down(): void
    {
        $this->forge->dropTable('customers', true);
    }
}
