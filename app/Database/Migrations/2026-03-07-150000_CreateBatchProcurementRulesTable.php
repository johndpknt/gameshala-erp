<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateBatchProcurementRulesTable extends Migration
{
    public function up(): void
    {
        $prefix = $this->db->DBPrefix;
        $sql    = <<<SQL
            CREATE TABLE IF NOT EXISTS {$prefix}batch_procurement_rules (
                id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
                batch_id BIGINT UNSIGNED NOT NULL,
                procurement_rule_id BIGINT UNSIGNED NOT NULL,
                applied_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
                remarks VARCHAR(255) DEFAULT NULL,
                PRIMARY KEY (id),
                KEY idx_batch_rules_batch (batch_id),
                KEY idx_batch_rules_rule (procurement_rule_id),
                CONSTRAINT fk_batch_rules_batch
                    FOREIGN KEY (batch_id) REFERENCES {$prefix}stock_batches(id)
                    ON UPDATE CASCADE ON DELETE CASCADE,
                CONSTRAINT fk_batch_rules_rule
                    FOREIGN KEY (procurement_rule_id) REFERENCES {$prefix}procurement_rules(id)
                    ON UPDATE CASCADE ON DELETE RESTRICT
            ) ENGINE=InnoDB
            DEFAULT CHARSET=utf8mb4
            COLLATE=utf8mb4_unicode_ci
            SQL;
        $this->db->query($sql);
    }

    public function down(): void
    {
        $this->forge->dropTable('batch_procurement_rules', true);
    }
}
