<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * @deprecated No-op. product_id on gaming_visit_food_items was never part of the stable schema;
 * use RemoveProductIdFromGamingVisitFoodItems if an older branch added the column.
 */
class AddProductIdToGamingVisitFoodItems extends Migration
{
    public function up(): void
    {
        // Intentionally empty — fresh installs use CreateGamingVisitFoodItemsTable without product_id.
    }

    public function down(): void
    {
        // Intentionally empty.
    }
}
