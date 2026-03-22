<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class DropGamingTables extends Migration
{
    public function up(): void
    {
        // Drop in dependency order (child tables first)
        $tables = [
            'gaming_visit_food_items',
            'gaming_visits',
            'gaming_prices',
            'gaming_food_beverage_items',
            'gaming_subcategories',
            'gaming_categories',
        ];

        foreach ($tables as $table) {
            if ($this->db->tableExists($table)) {
                $this->forge->dropTable($table, true);
            }
        }
    }

    public function down(): void
    {
        // Tables are not recreated; restore from backup or re-run original gaming migrations if needed.
    }
}
