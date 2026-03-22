<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateProductsTable extends Migration
{
    public function up(): void
    {
        $sql = <<<'SQL'
            CREATE TABLE IF NOT EXISTS products (
                id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
                sku VARCHAR(100) NOT NULL,
                name VARCHAR(200) NOT NULL,
                slug VARCHAR(200) NOT NULL,
                description TEXT DEFAULT NULL,
                image_url VARCHAR(500) DEFAULT NULL,
                unit VARCHAR(50) NOT NULL DEFAULT 'PCS',
                is_public TINYINT(1) NOT NULL DEFAULT 1,
                is_active TINYINT(1) NOT NULL DEFAULT 1,
                created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
                updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                PRIMARY KEY (id),
                UNIQUE KEY uq_products_sku (sku),
                UNIQUE KEY uq_products_slug (slug),
                KEY idx_products_name (name),
                KEY idx_products_public_active (is_public, is_active)
            ) ENGINE=InnoDB
            DEFAULT CHARSET=utf8mb4
            COLLATE=utf8mb4_unicode_ci
            SQL;
        $this->db->query($sql);
    }

    public function down(): void
    {
        $this->forge->dropTable('products', true);
    }
}
