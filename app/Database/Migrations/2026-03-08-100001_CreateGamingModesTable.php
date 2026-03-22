<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateGamingModesTable extends Migration
{
    public function up(): void
    {
        $prefix = $this->db->DBPrefix;
        $table  = $prefix . 'gaming_modes';

        $sql = <<<SQL
            CREATE TABLE IF NOT EXISTS `{$table}` (
                id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
                name VARCHAR(100) NOT NULL,
                PRIMARY KEY (id),
                UNIQUE KEY uq_gaming_modes_name (name)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
            SQL;
        $this->db->query($sql);
    }

    public function down(): void
    {
        $this->forge->dropTable('gaming_modes', true);
    }
}
