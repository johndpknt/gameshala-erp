<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * Adds MIN_15, MIN_25, MIN_30, MIN_45, MIN_60 block billing options to gaming_price_rules.price_type.
 */
class AlterGamingPriceRulesAddBlockPriceTypes extends Migration
{
    public function up(): void
    {
        $prefix = $this->db->DBPrefix;
        $table  = $prefix . 'gaming_price_rules';
        if (! $this->db->tableExists('gaming_price_rules')) {
            return;
        }
        $this->db->query("ALTER TABLE `{$table}` MODIFY COLUMN `price_type` ENUM(
            'PER_MINUTE','PER_30_MIN','PER_HOUR','FIXED',
            'MIN_15','MIN_25','MIN_30','MIN_45','MIN_60'
        ) NOT NULL");
    }

    public function down(): void
    {
        $prefix = $this->db->DBPrefix;
        $table  = $prefix . 'gaming_price_rules';
        if (! $this->db->tableExists('gaming_price_rules')) {
            return;
        }
        $this->db->query("ALTER TABLE `{$table}` MODIFY COLUMN `price_type` ENUM(
            'PER_MINUTE','PER_30_MIN','PER_HOUR','FIXED'
        ) NOT NULL");
    }
}
