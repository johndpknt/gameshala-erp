<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * Drops and recreates the customers table.
 * Run this only when you need to fully recreate the table (e.g. schema change).
 * WARNING: Drops foreign keys from orders and invoices that reference customers, then drops customers.
 */
class RecreateCustomersTable extends Migration
{
    public function up(): void
    {
        $prefix = $this->db->DBPrefix;
        $customers = $prefix . 'customers';
        $orders    = $prefix . 'orders';
        $invoices  = $prefix . 'invoices';

        $this->db->query('SET FOREIGN_KEY_CHECKS = 0');

        if ($this->db->tableExists($orders)) {
            $this->db->query("ALTER TABLE `{$orders}` DROP FOREIGN KEY fk_orders_customer");
        }
        if ($this->db->tableExists($invoices)) {
            $this->db->query("ALTER TABLE `{$invoices}` DROP FOREIGN KEY fk_invoices_customer");
        }

        $this->forge->dropTable($customers, true);

        $this->db->query('SET FOREIGN_KEY_CHECKS = 1');

        $sql = <<<SQL
            CREATE TABLE `{$customers}` (
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
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
            SQL;
        $this->db->query($sql);

        if ($this->db->tableExists($orders)) {
            $this->db->query("ALTER TABLE `{$orders}` ADD CONSTRAINT fk_orders_customer FOREIGN KEY (customer_id) REFERENCES `{$customers}` (id) ON UPDATE CASCADE ON DELETE RESTRICT");
        }
        if ($this->db->tableExists($invoices)) {
            $this->db->query("ALTER TABLE `{$invoices}` ADD CONSTRAINT fk_invoices_customer FOREIGN KEY (customer_id) REFERENCES `{$customers}` (id) ON UPDATE CASCADE ON DELETE RESTRICT");
        }
    }

    public function down(): void
    {
        $prefix = $this->db->DBPrefix;
        $customers = $prefix . 'customers';
        $this->forge->dropTable($customers, true);
    }
}
