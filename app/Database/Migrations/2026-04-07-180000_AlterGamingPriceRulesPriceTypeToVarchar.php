<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * ENUM price_type can silently store '' when the value is not in the ENUM list.
 * VARCHAR stores all application-defined billing types reliably.
 */
class AlterGamingPriceRulesPriceTypeToVarchar extends Migration
{
    public function up(): void
    {
        $prefix = $this->db->DBPrefix;
        $table  = $prefix . 'gaming_price_rules';
        if (! $this->db->tableExists('gaming_price_rules')) {
            return;
        }

        $this->db->query("UPDATE `{$table}` SET `price_type` = 'FIXED' WHERE `price_type` = '' OR `price_type` IS NULL");
        $this->db->query("ALTER TABLE `{$table}` MODIFY COLUMN `price_type` VARCHAR(32) NOT NULL DEFAULT 'FIXED'");
    }

    public function down(): void
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
}
