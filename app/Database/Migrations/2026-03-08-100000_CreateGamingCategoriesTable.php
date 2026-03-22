<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateGamingCategoriesTable extends Migration
{
    public function up(): void
    {
        $prefix = $this->db->DBPrefix;
        $table  = $prefix . 'gaming_categories';

        $sql = <<<SQL
            CREATE TABLE IF NOT EXISTS `{$table}` (
                id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
                name VARCHAR(100) NOT NULL,
                PRIMARY KEY (id),
                UNIQUE KEY uq_gaming_categories_name (name)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
            SQL;
        $this->db->query($sql);
    }

    public function down(): void
    {
        $this->forge->dropTable('gaming_categories', true);
    }
}
