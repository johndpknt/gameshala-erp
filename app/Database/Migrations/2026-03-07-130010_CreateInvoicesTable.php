<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateInvoicesTable extends Migration
{
    public function up(): void
    {
        $sql = <<<'SQL'
            CREATE TABLE IF NOT EXISTS invoices (
                id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
                invoice_number VARCHAR(100) NOT NULL,
                order_id BIGINT UNSIGNED NOT NULL,
                customer_id BIGINT UNSIGNED NOT NULL,
                subtotal DECIMAL(12,2) NOT NULL DEFAULT 0.00,
                discount_amount DECIMAL(12,2) NOT NULL DEFAULT 0.00,
                tax_amount DECIMAL(12,2) NOT NULL DEFAULT 0.00,
                total_amount DECIMAL(12,2) NOT NULL DEFAULT 0.00,
                status ENUM('DRAFT', 'ISSUED', 'PAID') NOT NULL DEFAULT 'DRAFT',
                issued_at DATETIME DEFAULT NULL,
                created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
                updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                PRIMARY KEY (id),
                UNIQUE KEY uq_invoices_invoice_number (invoice_number),
                UNIQUE KEY uq_invoices_order_id (order_id),
                KEY idx_invoices_customer (customer_id),
                KEY idx_invoices_status (status),
                CONSTRAINT fk_invoices_order
                    FOREIGN KEY (order_id) REFERENCES orders(id)
                    ON UPDATE CASCADE ON DELETE RESTRICT,
                CONSTRAINT fk_invoices_customer
                    FOREIGN KEY (customer_id) REFERENCES customers(id)
                    ON UPDATE CASCADE ON DELETE RESTRICT
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
            SQL;
        $this->db->query($sql);
    }

    public function down(): void
    {
        $this->forge->dropTable('invoices', true);
    }
}
