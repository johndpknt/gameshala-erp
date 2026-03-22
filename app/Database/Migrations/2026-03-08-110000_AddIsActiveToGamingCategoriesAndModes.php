<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddIsActiveToGamingCategoriesAndModes extends Migration
{
    public function up(): void
    {
        $prefix = $this->db->DBPrefix;
        $this->db->query("ALTER TABLE `{$prefix}gaming_categories` ADD COLUMN is_active TINYINT(1) NOT NULL DEFAULT 1 AFTER name");
        $this->db->query("ALTER TABLE `{$prefix}gaming_modes` ADD COLUMN is_active TINYINT(1) NOT NULL DEFAULT 1 AFTER name");
    }

    public function down(): void
    {
        $prefix = $this->db->DBPrefix;
        $this->db->query("ALTER TABLE `{$prefix}gaming_categories` DROP COLUMN is_active");
        $this->db->query("ALTER TABLE `{$prefix}gaming_modes` DROP COLUMN is_active");
    }
}
