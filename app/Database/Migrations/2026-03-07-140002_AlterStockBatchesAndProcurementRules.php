<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * Alters stock_batches and procurement_rules for databases created with the old schema.
 * stock_batches: drop procurement_rule_id and its FK; vendor_id NOT NULL, vendor FK RESTRICT.
 * procurement_rules: drop vendor_id, product_id, effective_from, effective_to, remarks; decimal 10,2.
 */
class AlterStockBatchesAndProcurementRules extends Migration
{
    public function up(): void
    {
        $prefix = $this->db->DBPrefix;
        $sb    = $prefix . 'stock_batches';
        $pr    = $prefix . 'procurement_rules';

        // stock_batches: drop FK and column for procurement_rule_id if they exist
        $cols = $this->db->getFieldNames($sb);
        if (in_array('procurement_rule_id', $cols, true)) {
            $this->db->query("ALTER TABLE `{$sb}` DROP FOREIGN KEY fk_stock_batches_rule");
            $this->db->query("ALTER TABLE `{$sb}` DROP KEY idx_stock_batches_rule");
            $this->db->query("ALTER TABLE `{$sb}` DROP COLUMN procurement_rule_id");
        }

        // stock_batches: vendor_id NOT NULL, and change FK to RESTRICT (drop and re-add)
        $this->db->query("ALTER TABLE `{$sb}` DROP FOREIGN KEY fk_stock_batches_vendor");
        $this->db->query("ALTER TABLE `{$sb}` MODIFY COLUMN vendor_id BIGINT UNSIGNED NOT NULL");
        $this->db->query("ALTER TABLE `{$sb}` ADD CONSTRAINT fk_stock_batches_vendor FOREIGN KEY (vendor_id) REFERENCES `{$prefix}vendors`(id) ON UPDATE CASCADE ON DELETE RESTRICT");

        // procurement_rules: drop vendor_id, product_id, effective_from, effective_to, remarks if they exist
        $prCols = $this->db->getFieldNames($pr);
        if (in_array('vendor_id', $prCols, true)) {
            $this->db->query("ALTER TABLE `{$pr}` DROP FOREIGN KEY fk_procurement_rules_vendor");
            $this->db->query("ALTER TABLE `{$pr}` DROP KEY idx_procurement_rules_vendor");
            $this->db->query("ALTER TABLE `{$pr}` DROP COLUMN vendor_id");
        }
        if (in_array('product_id', $prCols, true)) {
            $this->db->query("ALTER TABLE `{$pr}` DROP FOREIGN KEY fk_procurement_rules_product");
            $this->db->query("ALTER TABLE `{$pr}` DROP KEY idx_procurement_rules_product");
            $this->db->query("ALTER TABLE `{$pr}` DROP COLUMN product_id");
        }
        if (in_array('effective_from', $prCols, true)) {
            $this->db->query("ALTER TABLE `{$pr}` DROP COLUMN effective_from");
        }
        if (in_array('effective_to', $prCols, true)) {
            $this->db->query("ALTER TABLE `{$pr}` DROP COLUMN effective_to");
        }
        if (in_array('remarks', $prCols, true)) {
            $this->db->query("ALTER TABLE `{$pr}` DROP COLUMN remarks");
        }

        $this->db->query("ALTER TABLE `{$pr}` MODIFY COLUMN discount_value DECIMAL(10,2) NOT NULL DEFAULT 0.00");
        $this->db->query("ALTER TABLE `{$pr}` MODIFY COLUMN profit_value DECIMAL(10,2) NOT NULL DEFAULT 0.00");
    }

    public function down(): void
    {
        // Reverting would require re-adding columns and FKs; left as no-op for safety
    }
}
