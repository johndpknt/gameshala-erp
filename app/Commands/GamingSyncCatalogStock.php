<?php

namespace App\Commands;

use App\Libraries\GamingCatalogStockService;
use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

/**
 * Backfill stock_batches / stock_movements for gaming catalog lines added before inventory deduction existed.
 *
 * Usage:
 *   php spark gaming:sync-catalog-stock --dry-run
 *   php spark gaming:sync-catalog-stock
 */
class GamingSyncCatalogStock extends BaseCommand
{
    protected $group       = 'Gaming';
    protected $name        = 'gaming:sync-catalog-stock';
    protected $description = 'FIFO-deduct stock for gaming_visit_food_items catalog lines missing stock_movements';

    protected $usage = 'gaming:sync-catalog-stock [options]';

    protected $options = [
        '--dry-run' => 'Show deltas without updating the database',
    ];

    public function run(array $params)
    {
        $dryRun = CLI::getOption('dry-run') !== null;

        $db = db_connect();
        $prefix = $db->DBPrefix;
        $gvfi = $prefix . 'gaming_visit_food_items';
        $sm   = $prefix . 'stock_movements';

        $soldRows = $db->query(
            "SELECT gaming_visit_id AS vid, product_id AS pid, SUM(quantity) AS sold_qty
             FROM `{$gvfi}`
             WHERE product_id IS NOT NULL AND product_id > 0 AND quantity > 0
             GROUP BY gaming_visit_id, product_id
             ORDER BY gaming_visit_id, product_id"
        )->getResultArray();

        $movedRows = $db->query(
            "SELECT reference_id AS vid, product_id AS pid, SUM(qty_out) AS moved_qty
             FROM `{$sm}`
             WHERE reference_type = 'gaming_visit'
               AND movement_type = 'SALE'
               AND qty_out > 0
             GROUP BY reference_id, product_id"
        )->getResultArray();

        $movedMap = [];
        foreach ($movedRows as $r) {
            $movedMap[(int) $r['vid'] . ':' . (int) $r['pid']] = (int) $r['moved_qty'];
        }

        $service = GamingCatalogStockService::make();
        $pending = [];

        foreach ($soldRows as $r) {
            $vid = (int) $r['vid'];
            $pid = (int) $r['pid'];
            $sold = (int) $r['sold_qty'];
            $key  = $vid . ':' . $pid;
            $moved = $movedMap[$key] ?? 0;
            $delta = $sold - $moved;
            if ($delta > 0) {
                $pending[] = ['vid' => $vid, 'pid' => $pid, 'delta' => $delta, 'sold' => $sold, 'moved' => $moved];
            }
        }

        if ($pending === []) {
            CLI::write('Nothing to backfill (sold qty matches stock movements for all gaming catalog lines).', 'green');

            return;
        }

        CLI::write('Rows needing backfill: ' . count($pending), 'yellow');
        foreach ($pending as $row) {
            CLI::write(
                sprintf(
                    '  visit #%d product_id %d  sold=%d moved=%d  backfill_qty=%d',
                    $row['vid'],
                    $row['pid'],
                    $row['sold'],
                    $row['moved'],
                    $row['delta']
                )
            );
        }

        if ($dryRun) {
            CLI::write('Dry run: no database changes.', 'green');

            return;
        }

        foreach ($pending as $row) {
            $vid   = $row['vid'];
            $pid   = $row['pid'];
            $delta = $row['delta'];
            $note  = 'Gaming session #' . $vid . ' (catalog backfill)';
            $db->transStart();
            try {
                $service->deductFifoForGamingVisit($pid, $delta, $vid, $note);
                $db->transComplete();
            } catch (\Throwable $e) {
                $db->transRollback();
                CLI::error('Failed visit #' . $vid . ' product ' . $pid . ': ' . $e->getMessage());

                return;
            }
            if ($db->transStatus() === false) {
                CLI::error('Transaction failed for visit #' . $vid . ' product ' . $pid);

                return;
            }
            CLI::write('Backfilled visit #' . $vid . ' product_id ' . $pid . ' qty ' . $delta, 'green');
        }

        CLI::write('Done.', 'green');
    }
}
