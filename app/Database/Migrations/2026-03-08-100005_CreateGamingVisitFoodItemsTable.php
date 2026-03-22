<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateGamingVisitFoodItemsTable extends Migration
{
    public function up(): void
    {
        $prefix = $this->db->DBPrefix;
        $table  = $prefix . 'gaming_visit_food_items';
        $visits = $prefix . 'gaming_visits';
        $items  = $prefix . 'food_beverage_items';

        $sql = <<<SQL
            CREATE TABLE IF NOT EXISTS `{$table}` (
                id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
                gaming_visit_id BIGINT UNSIGNED NOT NULL,
                food_beverage_item_id BIGINT UNSIGNED NOT NULL,
                quantity INT NOT NULL,
                line_total DECIMAL(12,2) NOT NULL,
                PRIMARY KEY (id),
                KEY idx_gaming_visit_food_items_visit (gaming_visit_id),
                CONSTRAINT fk_gaming_visit_food_items_visit
                    FOREIGN KEY (gaming_visit_id) REFERENCES `{$visits}`(id)
                    ON UPDATE CASCADE ON DELETE CASCADE,
                CONSTRAINT fk_gaming_visit_food_items_item
                    FOREIGN KEY (food_beverage_item_id) REFERENCES `{$items}`(id)
                    ON UPDATE CASCADE ON DELETE RESTRICT
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
            SQL;
        $this->db->query($sql);
    }

    public function down(): void
    {
        $this->forge->dropTable('gaming_visit_food_items', true);
    }
}
