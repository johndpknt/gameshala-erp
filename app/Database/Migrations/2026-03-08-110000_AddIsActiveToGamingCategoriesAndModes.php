<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddIsActiveToGamingCategoriesAndModes extends Migration
{
    public function up()
    {
        // gaming_categories
        if (!$this->db->fieldExists('is_active', 'gaming_categories')) {
            $this->forge->addColumn('gaming_categories', [
                'is_active' => [
                    'type' => 'TINYINT',
                    'constraint' => 1,
                    'default' => 1,
                    'after' => 'name',
                ],
            ]);
        }

        // gaming_modes
        if (!$this->db->fieldExists('is_active', 'gaming_modes')) {
            $this->forge->addColumn('gaming_modes', [
                'is_active' => [
                    'type' => 'TINYINT',
                    'constraint' => 1,
                    'default' => 1,
                    'after' => 'name',
                ],
            ]);
        }
    }

    public function down()
    {
        // gaming_categories
        if ($this->db->fieldExists('is_active', 'gaming_categories')) {
            $this->forge->dropColumn('gaming_categories', 'is_active');
        }

        // gaming_modes
        if ($this->db->fieldExists('is_active', 'gaming_modes')) {
            $this->forge->dropColumn('gaming_modes', 'is_active');
        }
    }
}
