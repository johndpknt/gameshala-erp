<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AlterInvoicesForGamingVisits extends Migration
{
    public function up(): void
    {
        $prefix  = $this->db->DBPrefix;
        $table   = $prefix . 'invoices';
        $visits  = $prefix . 'gaming_visits';
        $orders  = $prefix . 'orders';

        $this->db->query("ALTER TABLE `{$table}` DROP FOREIGN KEY fk_invoices_order");
        $this->db->query("ALTER TABLE `{$table}` DROP INDEX uq_invoices_order_id");
        $this->db->query("ALTER TABLE `{$table}` MODIFY order_id BIGINT UNSIGNED NULL");
        $this->db->query("ALTER TABLE `{$table}` ADD COLUMN gaming_visit_id BIGINT UNSIGNED NULL AFTER order_id");
        $this->db->query("ALTER TABLE `{$table}` ADD UNIQUE KEY uq_invoices_gaming_visit (gaming_visit_id)");
        $this->db->query("ALTER TABLE `{$table}` ADD CONSTRAINT fk_invoices_gaming_visit FOREIGN KEY (gaming_visit_id) REFERENCES `{$visits}`(id) ON UPDATE CASCADE ON DELETE SET NULL");
        $this->db->query("ALTER TABLE `{$table}` ADD KEY idx_invoices_order_id (order_id)");
        $this->db->query("ALTER TABLE `{$table}` ADD CONSTRAINT fk_invoices_order FOREIGN KEY (order_id) REFERENCES `{$orders}`(id) ON UPDATE CASCADE ON DELETE RESTRICT");
    }

    public function down(): void
    {
        $prefix = $this->db->DBPrefix;
        $table  = $prefix . 'invoices';
        $orders = $prefix . 'orders';

        $this->db->query("ALTER TABLE `{$table}` DROP FOREIGN KEY fk_invoices_order");
        $this->db->query("ALTER TABLE `{$table}` DROP INDEX idx_invoices_order_id");
        $this->db->query("ALTER TABLE `{$table}` DROP FOREIGN KEY fk_invoices_gaming_visit");
        $this->db->query("ALTER TABLE `{$table}` DROP INDEX uq_invoices_gaming_visit");
        $this->db->query("ALTER TABLE `{$table}` DROP COLUMN gaming_visit_id");
        $this->db->query("ALTER TABLE `{$table}` MODIFY order_id BIGINT UNSIGNED NOT NULL");
        $this->db->query("ALTER TABLE `{$table}` ADD UNIQUE KEY uq_invoices_order_id (order_id)");
        $this->db->query("ALTER TABLE `{$table}` ADD CONSTRAINT fk_invoices_order FOREIGN KEY (order_id) REFERENCES `{$orders}`(id) ON UPDATE CASCADE ON DELETE RESTRICT");
    }
}
