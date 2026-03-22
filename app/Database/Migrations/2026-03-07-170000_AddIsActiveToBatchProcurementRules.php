<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddIsActiveToBatchProcurementRules extends Migration
{
    public function up(): void
    {
        $prefix = $this->db->DBPrefix;
        $table  = $prefix . 'batch_procurement_rules';
        $cols   = $this->db->getFieldNames($table);
        if (in_array('is_active', $cols, true)) {
            return;
        }
        $this->db->query("ALTER TABLE `{$table}` ADD COLUMN is_active TINYINT(1) NOT NULL DEFAULT 1 AFTER remarks");
        $this->db->query("ALTER TABLE `{$table}` ADD KEY idx_batch_rules_active (is_active)");
    }

    public function down(): void
    {
        $prefix = $this->db->DBPrefix;
        $table  = $prefix . 'batch_procurement_rules';
        $this->db->query("ALTER TABLE `{$table}` DROP KEY idx_batch_rules_active");
        $this->db->query("ALTER TABLE `{$table}` DROP COLUMN is_active");
    }
}
