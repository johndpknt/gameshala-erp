<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * Drops legacy product_id from gaming_visit_food_items if present; session food lines use food_beverage_item_id only.
 */
class RemoveProductIdFromGamingVisitFoodItems extends Migration
{
    public function up(): void
    {
        $prefix = $this->db->DBPrefix;
        $table  = $prefix . 'gaming_visit_food_items';
        $items  = $prefix . 'food_beverage_items';

        if (! $this->db->tableExists('gaming_visit_food_items')) {
            return;
        }
        if (! $this->db->fieldExists('product_id', 'gaming_visit_food_items')) {
            return;
        }

        $this->db->query("ALTER TABLE `{$table}` DROP FOREIGN KEY `fk_gaming_visit_food_items_product`");
        $this->db->query("ALTER TABLE `{$table}` DROP COLUMN `product_id`");

        $this->db->query("ALTER TABLE `{$table}` DROP FOREIGN KEY `fk_gaming_visit_food_items_item`");
        $this->db->query("DELETE FROM `{$table}` WHERE `food_beverage_item_id` IS NULL");
        $this->db->query("ALTER TABLE `{$table}` MODIFY `food_beverage_item_id` BIGINT UNSIGNED NOT NULL");
        $this->db->query("ALTER TABLE `{$table}` ADD CONSTRAINT `fk_gaming_visit_food_items_item` FOREIGN KEY (`food_beverage_item_id`) REFERENCES `{$items}`(`id`) ON UPDATE CASCADE ON DELETE RESTRICT");
    }

    public function down(): void
    {
        // Irreversible without re-adding product_id; leave empty.
    }
}
