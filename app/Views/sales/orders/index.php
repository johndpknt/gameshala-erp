<?php
$orderBase = base_url('sales/orders');
$orderSortUrl = function ($col) use ($orderBase, $sort, $order) {
    $next = ($sort === $col && $order === 'asc') ? 'desc' : 'asc';
    return $orderBase . '?' . http_build_query(['sort' => $col, 'order' => $next]);
};
$orderArrow = function ($col) use ($sort, $order) {
    if ($sort !== $col) return '';
    return $order === 'asc' ? ' ↑' : ' ↓';
};
?>
<div class="container py-4 px-3 px-sm-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3 mb-4">
        <h1 class="h4 fw-semibold mb-0">Orders</h1>
        <a href="<?= base_url('sales/orders/create') ?>" class="btn btn-primary">Create order</a>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th><a href="<?= $orderSortUrl('order_number') ?>" class="text-dark text-decoration-none">Order #<?= $orderArrow('order_number') ?></a></th>
                    <th><a href="<?= $orderSortUrl('customer_name') ?>" class="text-dark text-decoration-none">Customer<?= $orderArrow('customer_name') ?></a></th>
                    <th><a href="<?= $orderSortUrl('customer_phone') ?>" class="text-dark text-decoration-none">Phone<?= $orderArrow('customer_phone') ?></a></th>
                    <th><a href="<?= $orderSortUrl('status') ?>" class="text-dark text-decoration-none">Status<?= $orderArrow('status') ?></a></th>
                    <th class="text-end"><a href="<?= $orderSortUrl('total_amount') ?>" class="text-dark text-decoration-none">Total<?= $orderArrow('total_amount') ?></a></th>
                    <th><a href="<?= $orderSortUrl('created_at') ?>" class="text-dark text-decoration-none">Date<?= $orderArrow('created_at') ?></a></th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($orders)): ?>
                    <tr>
                        <td colspan="7" class="text-center text-secondary py-4">No orders yet.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($orders as $o): ?>
                        <tr>
                            <td><code class="small"><?= esc($o['order_number']) ?></code></td>
                            <td><?= esc($o['customer_name'] ?? '—') ?></td>
                            <td><?= esc($o['customer_phone'] ?? '—') ?></td>
                            <td>
                                <?php if (($o['status'] ?? '') === 'PAID'): ?>
                                    <span class="badge bg-success">PAID</span>
                                <?php else: ?>
                                    <?= form_open(base_url('sales/orders/set-status/' . (int) $o['id']), ['class' => 'd-inline']) ?>
                                        <?= csrf_field() ?>
                                        <select name="status" class="form-select form-select-sm order-status-select" style="width: auto; min-width: 120px;" aria-label="Order status">
                                            <option value="PENDING"   <?= ($o['status'] ?? '') === 'PENDING'   ? 'selected' : '' ?>>PENDING</option>
                                            <option value="CONFIRMED" <?= ($o['status'] ?? '') === 'CONFIRMED' ? 'selected' : '' ?>>CONFIRMED</option>
                                            <option value="PAID"      <?= ($o['status'] ?? '') === 'PAID'      ? 'selected' : '' ?>>PAID</option>
                                            <option value="CANCELLED" <?= ($o['status'] ?? '') === 'CANCELLED' ? 'selected' : '' ?>>CANCELLED</option>
                                        </select>
                                    <?= form_close() ?>
                                <?php endif; ?>
                            </td>
                            <td class="text-end"><?= number_format((float) ($o['total_amount'] ?? 0), 2) ?></td>
                            <td><?= esc($o['created_at'] ?? '') ?></td>
                            <td>
                                <?php $invId = isset($o['invoice_id']) ? (int) $o['invoice_id'] : 0; ?>
                                <?php if ($invId): ?>
                                    <a href="<?= base_url('sales/invoices/view/' . $invId) ?>" class="btn btn-sm btn-outline-primary" target="_blank" rel="noopener">Print invoice</a>
                                <?php else: ?>
                                    <span class="text-muted small">—</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<script>
(function () {
    document.querySelectorAll('.order-status-select').forEach(function (sel) {
        sel.addEventListener('change', function () {
            this.closest('form').submit();
        });
    });
})();
</script>
