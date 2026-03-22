<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateUserActivitiesTable extends Migration
{
    public function up(): void
    {
        $sql = <<<'SQL'
            CREATE TABLE IF NOT EXISTS user_activities (
                id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
                user_id BIGINT UNSIGNED NOT NULL,
                module VARCHAR(50) NOT NULL,
                action VARCHAR(50) NOT NULL,
                entity_id BIGINT UNSIGNED DEFAULT NULL,
                description VARCHAR(255) DEFAULT NULL,
                created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY (id),
                KEY idx_user_activities_user (user_id),
                KEY idx_user_activities_module (module),
                KEY idx_user_activities_action (action),
                KEY idx_user_activities_entity (entity_id),
                KEY idx_user_activities_created_at (created_at),
                CONSTRAINT fk_user_activities_user
                    FOREIGN KEY (user_id) REFERENCES users(id)
                    ON UPDATE CASCADE ON DELETE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
            SQL;
        $this->db->query($sql);
    }

    public function down(): void
    {
        $this->forge->dropTable('user_activities', true);
    }
}
