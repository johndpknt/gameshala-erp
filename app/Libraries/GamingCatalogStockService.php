<?php

namespace App\Libraries;

use App\Models\StockBatchModel;
use App\Models\StockMovementModel;
use CodeIgniter\Database\ConnectionInterface;

/**
 * FIFO stock deduction for gaming session catalog (vendor) sales.
 * Kept in sync with ProductCatalogPrice batch ordering (received_at ASC).
 *
 * Pass the same ConnectionInterface as the surrounding transaction (e.g. visit insert)
 * so stock updates participate in that transaction.
 */
class GamingCatalogStockService
{
    public function __construct(
        protected StockBatchModel $stockBatchModel,
        protected StockMovementModel $stockMovementModel,
    ) {
    }

    public static function make(?ConnectionInterface $db = null): self
    {
        $db ??= db_connect();

        return new self(
            new StockBatchModel($db),
            new StockMovementModel($db),
        );
    }

    /**
     * @throws \RuntimeException if remaining_qty is insufficient
     */
    public function deductFifoForGamingVisit(int $productId, int $qty, int $visitId, string $movementNote): void
    {
        if ($qty < 1) {
            return;
        }
        $conn = $this->stockBatchModel->db;
        // Avoid carrying a false transStatus from unrelated queries in the same request.
        $conn->resetTransStatus();

        $remaining = $qty;
        $batches = $this->stockBatchModel
            ->where('product_id', $productId)
            ->where('remaining_qty >', 0)
            ->orderBy('received_at', 'asc')
            ->findAll();
        foreach ($batches as $b) {
            if ($remaining < 1) {
                break;
            }
            $available = (int) $b['remaining_qty'];
            if ($available < 1) {
                continue;
            }
            $take     = min($available, $remaining);
            $batchId  = (int) $b['id'];
            $updated = $this->stockBatchModel
                ->set('remaining_qty', 'remaining_qty - ' . $take, false)
                ->where('id', $batchId)
                ->update();
            if ($updated === false) {
                throw new \RuntimeException('Could not update stock batch #' . $batchId . '.');
            }
            if ($conn->affectedRows() < 1) {
                throw new \RuntimeException(
                    'Stock batch #' . $batchId . ' was not decremented (0 rows updated). Another sale may have taken the stock; refresh and try again.'
                );
            }
            // CI may mark transStatus false on UPDATE even when it succeeded; reset before INSERT.
            $conn->resetTransStatus();

            // Use Query Builder (not Model) so timestamps/callbacks cannot interfere — matches phpMyAdmin behaviour.
            $movementRow = [
                'product_id'     => $productId,
                'batch_id'       => $batchId,
                'movement_type'  => 'SALE',
                'qty_in'         => 0,
                'qty_out'        => $take,
                'reference_type' => 'gaming_visit',
                'reference_id'   => $visitId,
                'note'           => $movementNote,
                'created_at'     => date('Y-m-d H:i:s'),
            ];
            $inserted = $conn->table('stock_movements')->insert($movementRow);
            $err      = $conn->error();
            if ($inserted === false || (int) ($err['code'] ?? 0) !== 0) {
                $errno = (int) ($err['code'] ?? 0);
                $emsg  = (string) ($err['message'] ?? '');
                $hint  = '';
                if ($errno === 1452) {
                    $hint = ' Foreign key check failed (usually missing row): verify '
                        . '`stock_batches`.`id`=' . $batchId . ' and `products`.`id`=' . $productId . ' exist.';
                }
                throw new \RuntimeException(
                    'Could not insert stock movement'
                    . ($emsg !== '' ? ': ' . $emsg . ' (errno ' . $errno . ').' : '.')
                    . $hint
                    . ' Last SQL: ' . $conn->showLastQuery()
                );
            }
            $remaining -= $take;
        }
        if ($remaining > 0) {
            throw new \RuntimeException(
                'Insufficient stock when updating inventory (' . $remaining . ' units short) for product_id ' . $productId . '.'
            );
        }
    }
}
