<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateGamingControllersTable extends Migration
{
    public function up(): void
    {
        $prefix = $this->db->DBPrefix;
        $table  = $prefix . 'gaming_controllers';
        $categoriesTable = $prefix . 'gaming_categories';

        $sql = <<<SQL
            CREATE TABLE IF NOT EXISTS `{$table}` (
                id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
                name VARCHAR(50) NOT NULL,
                gaming_category_id BIGINT UNSIGNED NOT NULL,
                is_active TINYINT(1) NOT NULL DEFAULT 1,
                PRIMARY KEY (id),
                UNIQUE KEY uq_gaming_controllers_name (name),
                KEY idx_gaming_controllers_category_id (gaming_category_id),
                CONSTRAINT fk_gaming_controllers_category
                    FOREIGN KEY (gaming_category_id) REFERENCES `{$categoriesTable}` (id)
                    ON UPDATE CASCADE ON DELETE RESTRICT
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
            SQL;
        $this->db->query($sql);
    }

    public function down(): void
    {
        $this->forge->dropTable('gaming_controllers', true);
    }
}
