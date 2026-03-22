<div class="container py-4 px-3 px-sm-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3 mb-4">
        <h1 class="h4 fw-semibold mb-0"><i class="bi bi-receipt me-2"></i>Invoices</h1>
    </div>

    <?php
    $invBase = base_url('sales/invoices');
    $invSortUrl = function ($col) use ($invBase, $searchQ, $sort, $order) {
        $next = ($sort === $col && $order === 'asc') ? 'desc' : 'asc';
        return $invBase . '?' . http_build_query(array_filter(['q' => $searchQ ?? '', 'sort' => $col, 'order' => $next]));
    };
    $invArrow = function ($col) use ($sort, $order) {
        if ($sort !== $col) return '';
        return $order === 'asc' ? ' ↑' : ' ↓';
    };
    ?>
    <form method="get" action="<?= base_url('sales/invoices') ?>" class="mb-4">
        <div class="input-group" style="max-width: 400px;">
            <input type="search" name="q" class="form-control" placeholder="Search by invoice #, order #, customer name, phone..."
                   value="<?= esc($searchQ ?? '') ?>">
            <button type="submit" class="btn btn-outline-secondary"><i class="bi bi-search me-1"></i>Search</button>
        </div>
    </form>

    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th><a href="<?= $invSortUrl('invoice_number') ?>" class="text-dark text-decoration-none">Invoice #<?= $invArrow('invoice_number') ?></a></th>
                    <th><a href="<?= $invSortUrl('order_number') ?>" class="text-dark text-decoration-none">Order / Session<?= $invArrow('order_number') ?></a></th>
                    <th><a href="<?= $invSortUrl('customer_name') ?>" class="text-dark text-decoration-none">Customer<?= $invArrow('customer_name') ?></a></th>
                    <th><a href="<?= $invSortUrl('issued_at') ?>" class="text-dark text-decoration-none">Date<?= $invArrow('issued_at') ?></a></th>
                    <th class="text-end"><a href="<?= $invSortUrl('total_amount') ?>" class="text-dark text-decoration-none">Total<?= $invArrow('total_amount') ?></a></th>
                    <th><a href="<?= $invSortUrl('status') ?>" class="text-dark text-decoration-none">Status<?= $invArrow('status') ?></a></th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($invoices)): ?>
                    <tr>
                        <td colspan="7" class="text-center text-secondary py-4">No invoices yet. Invoices are created when an order is marked as PAID.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($invoices as $inv): ?>
                        <tr>
                            <td><code class="small"><?= esc($inv['invoice_number']) ?></code></td>
                            <td><code class="small"><?= ! empty($inv['gaming_visit_id']) ? 'Session #' . (int) $inv['gaming_visit_id'] : esc($inv['order_number'] ?? '—') ?></code></td>
                            <td><?= esc($inv['customer_name'] ?? '—') ?></td>
                            <td><?= $inv['issued_at'] ? date('d M Y', strtotime($inv['issued_at'])) : '—' ?></td>
                            <td class="text-end"><?= number_format((float) ($inv['total_amount'] ?? 0), 2) ?></td>
                            <td><span class="badge bg-secondary"><?= esc($inv['status'] ?? '') ?></span></td>
                            <td>
                                <a href="<?= base_url('sales/invoices/view/' . (int) $inv['id']) ?>" class="btn btn-sm btn-outline-primary" target="_blank" rel="noopener"><i class="bi bi-printer me-1"></i>Print / PDF</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
