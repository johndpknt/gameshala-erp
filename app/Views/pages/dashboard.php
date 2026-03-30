<?php
$staffHideRevenue      = $staffHideRevenue ?? false;
$ongoingSessionsCount  = $ongoingSessionsCount ?? 0;
$sessionsEndedToday    = $sessionsEndedToday ?? 0;
$unpaidSessionsCount   = $unpaidSessionsCount ?? 0;
$unpaidSessionsAmount  = $unpaidSessionsAmount ?? 0.0;
$recentGamingInvoices  = $recentGamingInvoices ?? [];
$revenueLastMonth      = $revenueLastMonth ?? 0;
$revenueChange = $revenueLastMonth > 0 ? (($revenueThisMonth - $revenueLastMonth) / $revenueLastMonth) * 100 : ($revenueThisMonth > 0 ? 100 : 0);
?>
<div class="container py-4 px-3 px-sm-4">
    <ul class="nav nav-pills nav-fill mb-4" role="tablist" style="max-width: 280px;">
        <li class="nav-item" role="presentation">
            <a class="nav-link active" href="<?= base_url() ?>" role="tab"><i class="bi bi-controller me-1"></i>Gaming</a>
        </li>
        <li class="nav-item" role="presentation">
            <a class="nav-link" href="<?= base_url('dashboard/erp') ?>" role="tab"><i class="bi bi-grid-3x3-gap me-1"></i>ERP</a>
        </li>
    </ul>
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3 mb-4">
        <div>
            <h1 class="h4 fw-semibold mb-1">Gaming dashboard</h1>
            <p class="text-secondary small mb-0">Sessions, revenue and activity at a glance.</p>
        </div>
        <a href="<?= base_url('gaming/sessions') ?>" class="btn btn-primary"><i class="bi bi-play-circle-fill me-2"></i>Start session</a>
    </div>

    <!-- Key metrics -->
    <section class="mb-4">
        <h2 class="h6 text-uppercase text-muted fw-semibold mb-3"><i class="bi bi-graph-up me-1"></i>Key metrics</h2>
        <div class="row g-3">
            <?php if (! $staffHideRevenue): ?>
            <div class="col-6 col-lg-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="text-muted small mb-1"><i class="bi bi-cash-coin me-1"></i>Revenue today</div>
                        <div class="fw-bold fs-4 text-primary">₹<?= number_format($revenueToday ?? 0, 2) ?></div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="text-muted small mb-1"><i class="bi bi-calendar-month me-1"></i>Revenue this month</div>
                        <div class="fw-bold fs-4">₹<?= number_format($revenueThisMonth ?? 0, 2) ?></div>
                        <span class="small <?= $revenueChange >= 0 ? 'text-success' : 'text-danger' ?>">
                            <i class="bi bi-<?= $revenueChange >= 0 ? 'arrow-up' : 'arrow-down' ?>"></i> <?= number_format(abs($revenueChange), 1) ?>% vs last month
                        </span>
                    </div>
                </div>
            </div>
            <?php endif; ?>
            <div class="col-6 col-lg-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="text-muted small mb-1"><i class="bi bi-play-circle me-1"></i>Ongoing sessions</div>
                        <div class="fw-bold fs-4"><?= (int) $ongoingSessionsCount ?></div>
                        <a href="<?= base_url('gaming/sessions') ?>" class="small"><i class="bi bi-chevron-right"></i> View sessions</a>
                    </div>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="text-muted small mb-1"><i class="bi bi-exclamation-triangle me-1"></i>Unpaid sessions</div>
                        <div class="fw-bold fs-4"><?= (int) $unpaidSessionsCount ?></div>
                        <div class="small text-warning">₹<?= number_format($unpaidSessionsAmount, 2) ?> outstanding</div>
                        <?php if ($unpaidSessionsCount > 0): ?>
                            <a href="<?= base_url('gaming/sessions') ?>" class="small"><i class="bi bi-check2-circle"></i> Mark paid</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="row g-4">
        <!-- Gaming & recent activity -->
        <div class="col-12 col-lg-8">
    <section class="mb-4">
        <h2 class="h6 text-uppercase text-muted fw-semibold mb-3"><i class="bi bi-controller me-1"></i>Gaming at a glance</h2>
                <div class="row g-3">
                    <div class="col-6 col-md-4">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body text-center py-3">
                                <div class="display-6 fw-bold text-primary"><?= (int) $ongoingSessionsCount ?></div>
                                <div class="small text-muted">Ongoing now</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-md-4">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body text-center py-3">
                                <div class="display-6 fw-bold"><?= (int) $sessionsEndedToday ?></div>
                                <div class="small text-muted">Ended today</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-md-4">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body text-center py-3">
                                <div class="display-6 fw-bold text-warning"><?= (int) $unpaidSessionsCount ?></div>
                                <div class="small text-muted">Awaiting payment</div>
                            </div>
                        </div>
                    </div>
                </div>
                <a href="<?= base_url('gaming/sessions') ?>" class="btn btn-outline-primary btn-sm mt-2">Manage sessions</a>
            </section>

            <section>
                <h2 class="h6 text-uppercase text-muted fw-semibold mb-3"><i class="bi bi-receipt me-1"></i>Recent session payments</h2>
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-0">
                        <?php if (empty($recentGamingInvoices)): ?>
                            <p class="text-secondary mb-0 p-3">No session payments yet. End a session and mark it paid to see invoices here.</p>
                        <?php else: ?>
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Invoice</th>
                                            <th>Customer</th>
                                            <th class="text-end">Amount</th>
                                            <th>Date</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($recentGamingInvoices as $inv): ?>
                                            <tr>
                                                <td><code class="small"><?= esc($inv['invoice_number'] ?? '—') ?></code></td>
                                                <td><?= esc($inv['customer_name'] ?? '—') ?></td>
                                                <td class="text-end">₹<?= number_format((float) ($inv['total_amount'] ?? 0), 2) ?></td>
                                                <td class="small"><?= ! empty($inv['created_at']) ? date('d M H:i', strtotime($inv['created_at'])) : '—' ?></td>
                                                <td><a href="<?= base_url('sales/invoices/view/' . (int) ($inv['id'] ?? 0)) ?>" class="btn btn-sm btn-outline-primary" target="_blank" rel="noopener">View</a></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                <a href="<?= base_url('sales/invoices') ?>" class="btn btn-outline-secondary btn-sm mt-2">All invoices</a>
            </section>
        </div>

        <div class="col-12 col-lg-4">
            <?php if (! $staffHideRevenue): ?>
            <!-- Sales summary -->
            <section class="mb-4">
                <h2 class="h6 text-uppercase text-muted fw-semibold mb-3"><i class="bi bi-credit-card me-1"></i>Sales summary</h2>
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted small">Total revenue (all time)</span>
                            <strong>₹<?= number_format($totalRevenue ?? 0, 2) ?></strong>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted small">Orders (total)</span>
                            <strong><?= (int) ($totalOrders ?? 0) ?></strong>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted small">Orders this month</span>
                            <strong><?= (int) ($ordersThisMonth ?? 0) ?></strong>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted small">Invoices</span>
                            <strong><?= (int) ($totalInvoices ?? 0) ?></strong>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between small">
                            <span class="text-muted">Order profit (this month)</span>
                            <span>₹<?= number_format($profitThisMonth ?? 0, 2) ?></span>
                        </div>
                        <a href="<?= base_url('sales/orders') ?>" class="small d-block mt-2">View orders →</a>
                    </div>
                </div>
            </section>
            <?php endif; ?>

            <!-- Recent orders -->
            <section class="mb-4">
                <h2 class="h6 text-uppercase text-muted fw-semibold mb-3">Recent orders</h2>
                <?php if (empty($recentOrders)): ?>
                    <div class="card border-0 shadow-sm"><div class="card-body py-3"><p class="text-secondary small mb-0">No orders yet.</p></div></div>
                <?php else: ?>
                    <div class="card border-0 shadow-sm">
                        <ul class="list-group list-group-flush">
                            <?php foreach ($recentOrders as $o): ?>
                                <li class="list-group-item d-flex justify-content-between align-items-center py-2">
                                    <span class="small"><?= esc($o['order_number'] ?? '#') ?> · <?= esc($o['customer_name'] ?? '—') ?></span>
                                    <span class="badge bg-<?= ($o['status'] ?? '') === 'PAID' ? 'success' : 'secondary' ?>">₹<?= number_format((float) ($o['total_amount'] ?? 0), 0) ?></span>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                        <div class="card-footer bg-transparent py-2"><a href="<?= base_url('sales/orders') ?>" class="small">All orders →</a></div>
                    </div>
                <?php endif; ?>
            </section>

            <!-- Low stock -->
            <section class="mb-4">
                <h2 class="h6 text-uppercase text-muted fw-semibold mb-3"><i class="bi bi-exclamation-triangle text-warning me-1"></i>Low stock (≤ <?= (int) ($lowStockThreshold ?? 5) ?>)</h2>
                <?php if (empty($lowStockBatches)): ?>
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <p class="text-success small mb-0">No low stock.</p>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="card border-0 shadow-sm">
                        <ul class="list-group list-group-flush">
                            <?php foreach (array_slice($lowStockBatches, 0, 5) as $b): ?>
                                <li class="list-group-item d-flex justify-content-between align-items-center py-2">
                                    <span class="small"><?= esc($b['product_name'] ?? '—') ?></span>
                                    <span class="badge bg-danger"><?= (int) ($b['remaining_qty'] ?? 0) ?></span>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                        <div class="card-footer bg-transparent py-2">
                            <a href="<?= base_url('inventory/stock-batches') ?>" class="small">Stock batches →</a>
                        </div>
                    </div>
                <?php endif; ?>
            </section>

            <!-- Quick links (Gaming only) -->
            <section>
                <h2 class="h6 text-uppercase text-muted fw-semibold mb-3"><i class="bi bi-link-45deg me-1"></i>Gaming quick links</h2>
                <div class="d-flex flex-column gap-2">
                    <a href="<?= base_url('gaming/sessions') ?>" class="btn btn-outline-primary"><i class="bi bi-play-circle me-2"></i>Sessions</a>
                    <a href="<?= base_url('gaming/categories') ?>" class="btn btn-outline-secondary"><i class="bi bi-tags me-2"></i>Categories</a>
                    <a href="<?= base_url('gaming/price-rules') ?>" class="btn btn-outline-secondary"><i class="bi bi-currency-rupee me-2"></i>Price rules</a>
                    <a href="<?= base_url('gaming/food-beverages') ?>" class="btn btn-outline-secondary"><i class="bi bi-cup-straw me-2"></i>Food &amp; beverages</a>
                </div>
            </section>
        </div>
    </div>
</div>
