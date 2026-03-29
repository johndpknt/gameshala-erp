<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AlterGamingPriceRulesTimeDurationEnum extends Migration
{
    public function up(): void
    {
        $prefix = $this->db->getPrefix();
        $table  = $prefix . 'gaming_price_rules';

        $this->db->query("UPDATE `{$table}` SET price_type = 'MIN_15' WHERE price_type = 'PER_MINUTE'");
        $this->db->query("UPDATE `{$table}` SET price_type = 'MIN_25' WHERE price_type = 'PER_30_MIN'");
        $this->db->query("UPDATE `{$table}` SET price_type = 'MIN_60' WHERE price_type = 'PER_HOUR'");
        $this->db->query("UPDATE `{$table}` SET price_type = 'MIN_45' WHERE price_type = 'FIXED'");

        $this->db->query("ALTER TABLE `{$table}` MODIFY COLUMN price_type ENUM('MIN_15','MIN_25','MIN_45','MIN_60') NOT NULL");
    }

    public function down(): void
    {
        $prefix = $this->db->getPrefix();
        $table  = $prefix . 'gaming_price_rules';

        $this->db->query("ALTER TABLE `{$table}` MODIFY COLUMN price_type ENUM('PER_MINUTE','PER_30_MIN','PER_HOUR','FIXED') NOT NULL");

        $this->db->query("UPDATE `{$table}` SET price_type = 'PER_MINUTE' WHERE price_type = 'MIN_15'");
        $this->db->query("UPDATE `{$table}` SET price_type = 'PER_30_MIN' WHERE price_type = 'MIN_25'");
        $this->db->query("UPDATE `{$table}` SET price_type = 'PER_HOUR' WHERE price_type = 'MIN_60'");
        $this->db->query("UPDATE `{$table}` SET price_type = 'FIXED' WHERE price_type = 'MIN_45'");
    }
}
