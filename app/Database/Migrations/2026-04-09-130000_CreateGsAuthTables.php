<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateGsAuthTables extends Migration
{
    public function up(): void
    {
        $prefix = $this->db->DBPrefix;

        $this->db->query(<<<SQL
            CREATE TABLE IF NOT EXISTS {$prefix}gs_users (
                id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
                mobile_number VARCHAR(15) NOT NULL,
                created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
                updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                PRIMARY KEY (id),
                UNIQUE KEY uq_gs_users_mobile_number (mobile_number)
            ) ENGINE=InnoDB
            DEFAULT CHARSET=utf8mb4
            COLLATE=utf8mb4_unicode_ci
            SQL);

        $this->db->query(<<<SQL
            CREATE TABLE IF NOT EXISTS {$prefix}gs_auth_tokens (
                id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
                mobile_number VARCHAR(15) NOT NULL,
                otp_code CHAR(4) NOT NULL,
                status ENUM('active', 'used', 'expired') NOT NULL DEFAULT 'active',
                created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
                expires_at DATETIME NOT NULL,
                PRIMARY KEY (id),
                KEY idx_gs_auth_tokens_mobile_number (mobile_number),
                KEY idx_gs_auth_tokens_status (status),
                KEY idx_gs_auth_tokens_expires_at (expires_at)
            ) ENGINE=InnoDB
            DEFAULT CHARSET=utf8mb4
            COLLATE=utf8mb4_unicode_ci
            SQL);
    }

    public function down(): void
    {
        $this->forge->dropTable('gs_auth_tokens', true);
        $this->forge->dropTable('gs_users', true);
    }
}
