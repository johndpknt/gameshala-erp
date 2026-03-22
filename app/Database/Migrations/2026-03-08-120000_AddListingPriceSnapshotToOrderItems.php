<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddListingPriceSnapshotToOrderItems extends Migration
{
    public function up(): void
    {
        $table = $this->db->DBPrefix . 'order_items';
        $fields = $this->db->getFieldNames($table);
        if ($fields && ! in_array('listing_price_snapshot', $fields, true)) {
            $this->forge->addColumn($table, [
                'listing_price_snapshot' => [
                    'type'  => 'DECIMAL(12,2)',
                    'null'  => true,
                    'after' => 'unit_price',
                ],
            ]);
        }
    }

    public function down(): void
    {
        $table  = $this->db->DBPrefix . 'order_items';
        $fields = $this->db->getFieldNames($table);
        if ($fields && in_array('listing_price_snapshot', $fields, true)) {
            $this->forge->dropColumn($table, 'listing_price_snapshot');
        }
    }
}
