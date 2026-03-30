<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * Optional catalog product line on gaming visit food (SKU BEVE-/FOOD); food_beverage lines unchanged.
 */
class AlterGamingVisitFoodItemsAddProductId extends Migration
{
    public function up(): void
    {
        $prefix = $this->db->DBPrefix;
        $table  = $prefix . 'gaming_visit_food_items';
        $items  = $prefix . 'food_beverage_items';
        $prods  = $prefix . 'products';
        $batch  = $prefix . 'stock_batches';

        $this->db->query("ALTER TABLE `{$table}` DROP FOREIGN KEY `fk_gaming_visit_food_items_item`");
        $this->db->query("ALTER TABLE `{$table}` MODIFY `food_beverage_item_id` BIGINT UNSIGNED NULL");
        $this->db->query("ALTER TABLE `{$table}` ADD COLUMN `product_id` BIGINT UNSIGNED NULL AFTER `food_beverage_item_id`");
        $this->db->query("ALTER TABLE `{$table}` ADD COLUMN `stock_batch_id` BIGINT UNSIGNED NULL AFTER `product_id`");
        $this->db->query("ALTER TABLE `{$table}` ADD CONSTRAINT `fk_gaming_visit_food_items_item` FOREIGN KEY (`food_beverage_item_id`) REFERENCES `{$items}`(`id`) ON UPDATE CASCADE ON DELETE RESTRICT");
        $this->db->query("ALTER TABLE `{$table}` ADD CONSTRAINT `fk_gaming_visit_food_items_product` FOREIGN KEY (`product_id`) REFERENCES `{$prods}`(`id`) ON UPDATE CASCADE ON DELETE RESTRICT");
        $this->db->query("ALTER TABLE `{$table}` ADD CONSTRAINT `fk_gaming_visit_food_items_batch` FOREIGN KEY (`stock_batch_id`) REFERENCES `{$batch}`(`id`) ON UPDATE CASCADE ON DELETE RESTRICT");
    }

    public function down(): void
    {
        $prefix = $this->db->DBPrefix;
        $table  = $prefix . 'gaming_visit_food_items';
        $items  = $prefix . 'food_beverage_items';

        $this->db->query("ALTER TABLE `{$table}` DROP FOREIGN KEY `fk_gaming_visit_food_items_batch`");
        $this->db->query("ALTER TABLE `{$table}` DROP FOREIGN KEY `fk_gaming_visit_food_items_product`");
        $this->db->query("ALTER TABLE `{$table}` DROP FOREIGN KEY `fk_gaming_visit_food_items_item`");

        $this->db->query("DELETE FROM `{$table}` WHERE `food_beverage_item_id` IS NULL");

        $this->db->query("ALTER TABLE `{$table}` DROP COLUMN `stock_batch_id`");
        $this->db->query("ALTER TABLE `{$table}` DROP COLUMN `product_id`");
        $this->db->query("ALTER TABLE `{$table}` MODIFY `food_beverage_item_id` BIGINT UNSIGNED NOT NULL");
        $this->db->query("ALTER TABLE `{$table}` ADD CONSTRAINT `fk_gaming_visit_food_items_item` FOREIGN KEY (`food_beverage_item_id`) REFERENCES `{$items}`(`id`) ON UPDATE CASCADE ON DELETE RESTRICT");
    }
}
