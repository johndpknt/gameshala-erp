<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddIsActiveToFoodBeverageItems extends Migration
{
    public function up(): void
    {
        $prefix = $this->db->DBPrefix;
        $this->db->query("ALTER TABLE `{$prefix}food_beverage_items` ADD COLUMN is_active TINYINT(1) NOT NULL DEFAULT 1 AFTER price");
    }

    public function down(): void
    {
        $prefix = $this->db->DBPrefix;
        $this->db->query("ALTER TABLE `{$prefix}food_beverage_items` DROP COLUMN is_active");
    }
}
