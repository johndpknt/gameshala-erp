<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateGamingTitlesTable extends Migration
{
    public function up(): void
    {
        $prefix = $this->db->DBPrefix;
        $table  = $prefix . 'gaming_titles';
        $controllersTable = $prefix . 'gaming_controllers';

        $sql = <<<SQL
            CREATE TABLE IF NOT EXISTS `{$table}` (
                id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
                name VARCHAR(150) NOT NULL,
                gaming_controller_id BIGINT UNSIGNED NOT NULL,
                is_active TINYINT(1) NOT NULL DEFAULT 1,
                PRIMARY KEY (id),
                UNIQUE KEY uq_gaming_titles_controller_name (gaming_controller_id, name),
                KEY idx_gaming_titles_controller_id (gaming_controller_id),
                CONSTRAINT fk_gaming_titles_controller
                    FOREIGN KEY (gaming_controller_id) REFERENCES `{$controllersTable}` (id)
                    ON UPDATE CASCADE ON DELETE RESTRICT
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
            SQL;
        $this->db->query($sql);
    }

    public function down(): void
    {
        $this->forge->dropTable('gaming_titles', true);
    }
}
