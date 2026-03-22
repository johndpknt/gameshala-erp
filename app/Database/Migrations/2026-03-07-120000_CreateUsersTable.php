<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateUsersTable extends Migration
{
    public function up(): void
    {
        $sql = <<<'SQL'
            CREATE TABLE IF NOT EXISTS users (
                id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
                name VARCHAR(150) NOT NULL,
                email VARCHAR(191) NOT NULL,
                phone VARCHAR(30) DEFAULT NULL,
                password_hash VARCHAR(255) NOT NULL,
                role ENUM('ADMIN', 'STAFF') NOT NULL DEFAULT 'STAFF',
                is_active TINYINT(1) NOT NULL DEFAULT 1,
                last_login_at DATETIME NULL,
                created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
                updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                PRIMARY KEY (id),
                UNIQUE KEY uq_users_email (email),
                KEY idx_users_role (role),
                KEY idx_users_active (is_active)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
            SQL;
        $this->db->query($sql);
    }

    public function down(): void
    {
        $this->forge->dropTable('users', true);
    }
}
