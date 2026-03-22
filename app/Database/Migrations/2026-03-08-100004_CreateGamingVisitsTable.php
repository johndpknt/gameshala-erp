<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateGamingVisitsTable extends Migration
{
    public function up(): void
    {
        $prefix  = $this->db->DBPrefix;
        $table   = $prefix . 'gaming_visits';
        $customers = $prefix . 'customers';
        $priceRules = $prefix . 'gaming_price_rules';
        $invoices = $prefix . 'invoices';

        $sql = <<<SQL
            CREATE TABLE IF NOT EXISTS `{$table}` (
                id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
                customer_id BIGINT UNSIGNED DEFAULT NULL,
                gaming_price_rule_id BIGINT UNSIGNED NOT NULL,
                invoice_id BIGINT UNSIGNED DEFAULT NULL,
                no_of_players INT NOT NULL DEFAULT 1,
                start_time DATETIME NOT NULL,
                end_time DATETIME DEFAULT NULL,
                gaming_amount DECIMAL(12,2) DEFAULT 0.00,
                food_amount DECIMAL(12,2) DEFAULT 0.00,
                total_amount DECIMAL(12,2) DEFAULT 0.00,
                status ENUM('ONGOING', 'FINISHED', 'CANCELLED') NOT NULL DEFAULT 'ONGOING',
                PRIMARY KEY (id),
                KEY idx_gaming_visits_customer (customer_id),
                KEY idx_gaming_visits_rule (gaming_price_rule_id),
                KEY idx_gaming_visits_invoice (invoice_id),
                CONSTRAINT fk_gaming_visits_customer
                    FOREIGN KEY (customer_id) REFERENCES `{$customers}`(id)
                    ON UPDATE CASCADE ON DELETE SET NULL,
                CONSTRAINT fk_gaming_visits_rule
                    FOREIGN KEY (gaming_price_rule_id) REFERENCES `{$priceRules}`(id)
                    ON UPDATE CASCADE ON DELETE RESTRICT,
                CONSTRAINT fk_gaming_visits_invoice
                    FOREIGN KEY (invoice_id) REFERENCES `{$invoices}`(id)
                    ON UPDATE CASCADE ON DELETE SET NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
            SQL;
        $this->db->query($sql);
    }

    public function down(): void
    {
        $this->forge->dropTable('gaming_visits', true);
    }
}
