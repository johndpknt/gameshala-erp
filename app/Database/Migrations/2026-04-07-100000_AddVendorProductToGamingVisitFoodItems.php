<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddVendorProductToGamingVisitFoodItems extends Migration
{
    public function up()
    {
        $fields = $this->db->getFieldNames('gaming_visit_food_items');

        if (!in_array('product_id', $fields)) {

            $this->forge->addColumn('gaming_visit_food_items', [
                'product_id' => [
                    'type' => 'BIGINT',
                    'unsigned' => true,
                    'null' => true,
                ]
            ]);

            $this->db->query("
                ALTER TABLE gaming_visit_food_items
                ADD CONSTRAINT fk_gvfi_product
                FOREIGN KEY (product_id) REFERENCES products(id)
                ON DELETE SET NULL ON UPDATE CASCADE
            ");
        }

        if (in_array('food_beverage_item_id', $fields)) {
            $this->forge->modifyColumn('gaming_visit_food_items', [
                'food_beverage_item_id' => [
                    'type' => 'BIGINT',
                    'unsigned' => true,
                    'null' => true
                ]
            ]);
        }
    }

    public function down()
    {
        if ($this->db->fieldExists('product_id', 'gaming_visit_food_items')) {
            $this->db->query("ALTER TABLE gaming_visit_food_items DROP FOREIGN KEY fk_gvfi_product");
            $this->forge->dropColumn('gaming_visit_food_items', 'product_id');
        }
    }
}
