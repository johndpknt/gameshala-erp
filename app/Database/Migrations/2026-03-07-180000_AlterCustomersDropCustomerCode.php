<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * For databases that already have the customers table with customer_code.
 * Drops customer_code column and unique key; makes phone NOT NULL.
 * No-op if the table was created by the updated CreateCustomersTable (no customer_code).
 */
class AlterCustomersDropCustomerCode extends Migration
{
    public function up(): void
    {
        $prefix = $this->db->DBPrefix;
        $table  = $prefix . 'customers';

        if (! $this->db->tableExists($table)) {
            return;
        }

        $columns = $this->db->getFieldNames($table);
        if (! in_array('customer_code', $columns, true)) {
            return;
        }

        $this->db->query("ALTER TABLE `{$table}` DROP INDEX uq_customers_code");
        $this->forge->dropColumn($table, 'customer_code');

        $this->db->query("ALTER TABLE `{$table}` MODIFY phone VARCHAR(30) NOT NULL");
    }

    public function down(): void
    {
        $prefix = $this->db->DBPrefix;
        $table  = $prefix . 'customers';

        if (! $this->db->tableExists($table)) {
            return;
        }

        $this->db->query("ALTER TABLE `{$table}` MODIFY phone VARCHAR(30) DEFAULT NULL");
        $this->db->query("ALTER TABLE `{$table}` ADD COLUMN customer_code VARCHAR(50) NOT NULL DEFAULT '' AFTER id");
        $this->db->query("ALTER TABLE `{$table}` ADD UNIQUE KEY uq_customers_code (customer_code)");
    }
}
