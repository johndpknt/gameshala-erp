<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * Batch code stays required but may repeat across different products (no global uniqueness).
 */
class StockBatchCodeDropGlobalUnique extends Migration
{
    public function up(): void
    {
        $prefix = $this->db->DBPrefix;
        $table  = $prefix . 'stock_batches';

        $indexes = $this->db->getIndexData('stock_batches');
        if (isset($indexes['uq_stock_batches_code'])) {
            $this->db->query("ALTER TABLE `{$table}` DROP INDEX `uq_stock_batches_code`");
        }

        $indexesAfter = $this->db->getIndexData('stock_batches');
        if (! isset($indexesAfter['idx_stock_batches_batch_code'])) {
            $this->db->query("ALTER TABLE `{$table}` ADD INDEX `idx_stock_batches_batch_code` (`batch_code`)");
        }
    }

    public function down(): void
    {
        $prefix = $this->db->DBPrefix;
        $table  = $prefix . 'stock_batches';

        $indexes = $this->db->getIndexData('stock_batches');
        if (isset($indexes['idx_stock_batches_batch_code'])) {
            $this->db->query("ALTER TABLE `{$table}` DROP INDEX `idx_stock_batches_batch_code`");
        }

        // Fails if duplicate batch_code rows exist; only for rollback on empty/test DBs.
        $this->db->query("ALTER TABLE `{$table}` ADD UNIQUE KEY `uq_stock_batches_code` (`batch_code`)");
    }
}
