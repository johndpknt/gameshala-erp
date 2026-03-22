<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateFoodBeverageItemsTable extends Migration
{
    public function up(): void
    {
        $prefix = $this->db->DBPrefix;
        $table  = $prefix . 'food_beverage_items';

        $sql = <<<SQL
            CREATE TABLE IF NOT EXISTS `{$table}` (
                id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
                name VARCHAR(100) NOT NULL,
                unit_label VARCHAR(50) DEFAULT NULL,
                price DECIMAL(10,2) NOT NULL,
                PRIMARY KEY (id)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
            SQL;
        $this->db->query($sql);
    }

    public function down(): void
    {
        $this->forge->dropTable('food_beverage_items', true);
    }
}
