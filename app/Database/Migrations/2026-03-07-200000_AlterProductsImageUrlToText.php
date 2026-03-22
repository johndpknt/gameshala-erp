<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AlterProductsImageUrlToText extends Migration
{
    public function up(): void
    {
        $this->db->query('ALTER TABLE products MODIFY COLUMN image_url TEXT DEFAULT NULL');
    }

    public function down(): void
    {
        $this->db->query('ALTER TABLE products MODIFY COLUMN image_url VARCHAR(500) DEFAULT NULL');
    }
}
