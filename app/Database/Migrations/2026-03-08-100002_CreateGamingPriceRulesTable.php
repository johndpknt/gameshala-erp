<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateGamingPriceRulesTable extends Migration
{
    public function up(): void
    {
        $prefix   = $this->db->DBPrefix;
        $table    = $prefix . 'gaming_price_rules';
        $categories = $prefix . 'gaming_categories';
        $modes    = $prefix . 'gaming_modes';

        $sql = <<<SQL
            CREATE TABLE IF NOT EXISTS `{$table}` (
                id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
                gaming_category_id BIGINT UNSIGNED NOT NULL,
                gaming_mode_id BIGINT UNSIGNED NOT NULL,
                price_type ENUM('PER_MINUTE', 'PER_30_MIN', 'PER_HOUR', 'FIXED', 'MIN_15', 'MIN_25', 'MIN_30', 'MIN_45', 'MIN_60') NOT NULL,
                price DECIMAL(10,2) NOT NULL,
                PRIMARY KEY (id),
                KEY idx_gaming_price_rules_category (gaming_category_id),
                KEY idx_gaming_price_rules_mode (gaming_mode_id),
                CONSTRAINT fk_gaming_price_rules_category
                    FOREIGN KEY (gaming_category_id) REFERENCES `{$categories}`(id)
                    ON UPDATE CASCADE ON DELETE RESTRICT,
                CONSTRAINT fk_gaming_price_rules_mode
                    FOREIGN KEY (gaming_mode_id) REFERENCES `{$modes}`(id)
                    ON UPDATE CASCADE ON DELETE RESTRICT
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
            SQL;
        $this->db->query($sql);
    }

    public function down(): void
    {
        $this->forge->dropTable('gaming_price_rules', true);
    }
}
