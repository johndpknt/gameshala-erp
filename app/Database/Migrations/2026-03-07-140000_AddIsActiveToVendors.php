<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddIsActiveToVendors extends Migration
{
    public function up(): void
    {
        $this->db->query('ALTER TABLE vendors ADD COLUMN is_active TINYINT(1) NOT NULL DEFAULT 1 AFTER remarks');
        $this->db->query('ALTER TABLE vendors ADD KEY idx_vendors_active (is_active)');
    }

    public function down(): void
    {
        $this->db->query('ALTER TABLE vendors DROP KEY idx_vendors_active');
        $this->db->query('ALTER TABLE vendors DROP COLUMN is_active');
    }
}
