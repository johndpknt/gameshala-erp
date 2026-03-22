<?php
$movBase = base_url('inventory/stock-movements');
$movSortUrl = function ($col) use ($movBase, $sort, $order) {
    $next = ($sort === $col && $order === 'asc') ? 'desc' : 'asc';
    return $movBase . '?' . http_build_query(['sort' => $col, 'order' => $next]);
};
$movArrow = function ($col) use ($sort, $order) {
    if ($sort !== $col) return '';
    return $order === 'asc' ? ' ↑' : ' ↓';
};
?>
<div class="container py-4 px-3 px-sm-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3 mb-4">
        <h1 class="h4 fw-semibold mb-0">Stock Movements</h1>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th><a href="<?= $movSortUrl('created_at') ?>" class="text-dark text-decoration-none">Date<?= $movArrow('created_at') ?></a></th>
                    <th><a href="<?= $movSortUrl('product_name') ?>" class="text-dark text-decoration-none">Product<?= $movArrow('product_name') ?></a></th>
                    <th><a href="<?= $movSortUrl('batch_code') ?>" class="text-dark text-decoration-none">Batch<?= $movArrow('batch_code') ?></a></th>
                    <th><a href="<?= $movSortUrl('movement_type') ?>" class="text-dark text-decoration-none">Type<?= $movArrow('movement_type') ?></a></th>
                    <th class="text-end"><a href="<?= $movSortUrl('qty_in') ?>" class="text-dark text-decoration-none">Qty in<?= $movArrow('qty_in') ?></a></th>
                    <th class="text-end"><a href="<?= $movSortUrl('qty_out') ?>" class="text-dark text-decoration-none">Qty out<?= $movArrow('qty_out') ?></a></th>
                    <th><a href="<?= $movSortUrl('reference_type') ?>" class="text-dark text-decoration-none">Reference<?= $movArrow('reference_type') ?></a></th>
                    <th><a href="<?= $movSortUrl('note') ?>" class="text-dark text-decoration-none">Note<?= $movArrow('note') ?></a></th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($movements)): ?>
                    <tr>
                        <td colspan="8" class="text-center text-secondary py-4">No stock movements found.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($movements as $m): ?>
                        <tr>
                            <td><?= esc(date('M j, Y H:i', strtotime($m['created_at']))) ?></td>
                            <td><?= esc($m['product_name'] ?? '—') ?></td>
                            <td><?= isset($m['batch_code']) && $m['batch_code'] !== null ? '<code class="small">' . esc($m['batch_code']) . '</code>' : '—' ?></td>
                            <td><span class="badge bg-secondary"><?= esc($m['movement_type'] ?? '—') ?></span></td>
                            <td class="text-end"><?= (int) ($m['qty_in'] ?? 0) ?></td>
                            <td class="text-end"><?= (int) ($m['qty_out'] ?? 0) ?></td>
                            <td><?= esc(($m['reference_type'] ?? '') . ($m['reference_id'] ? ' #' . $m['reference_id'] : '')) ?: '—' ?></td>
                            <td><?= esc($m['note'] ?? '—') ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
