<?php
$revenueChange = ($revenueLastMonth ?? 0) > 0 ? ((($revenueThisMonth ?? 0) - $revenueLastMonth) / $revenueLastMonth) * 100 : (($revenueThisMonth ?? 0) > 0 ? 100 : 0);
?>
<div class="container py-4 px-3 px-sm-4">
    <ul class="nav nav-pills nav-fill mb-4" role="tablist" style="max-width: 280px;">
        <li class="nav-item" role="presentation">
            <a class="nav-link" href="<?= base_url() ?>" role="tab"><i class="bi bi-controller me-1"></i>Gaming</a>
        </li>
        <li class="nav-item" role="presentation">
            <a class="nav-link active" href="<?= base_url('dashboard/erp') ?>" role="tab"><i class="bi bi-grid-3x3-gap me-1"></i>ERP</a>
        </li>
    </ul>
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3 mb-4">
        <div>
            <h1 class="h4 fw-semibold mb-1">ERP dashboard</h1>
            <p class="text-secondary small mb-0">Catalog, inventory, sales (orders), profit &amp; invoices.</p>
        </div>
    </div>

    <section class="mb-4">
        <h2 class="h6 text-uppercase text-muted fw-semibold mb-3"><i class="bi bi-bar-chart me-1"></i>Overview</h2>
        <div class="row g-3">
            <div class="col-6 col-md-4 col-lg-2">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="text-muted small mb-1">Total revenue (paid)</div>
                        <div class="fw-bold fs-5">₹<?= number_format($totalRevenue ?? 0, 2) ?></div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-4 col-lg-2">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="text-muted small mb-1">Orders (total)</div>
                        <div class="fw-bold fs-5"><?= (int) ($totalOrders ?? 0) ?></div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-4 col-lg-2">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="text-muted small mb-1">Revenue this month</div>
                        <div class="fw-bold fs-5">₹<?= number_format($revenueThisMonth ?? 0, 2) ?></div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-4 col-lg-2">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="text-muted small mb-1">Orders this month</div>
                        <div class="fw-bold fs-5"><?= (int) ($ordersThisMonth ?? 0) ?></div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-4 col-lg-2">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="text-muted small mb-1">Customers</div>
                        <div class="fw-bold fs-5"><?= (int) ($totalCustomers ?? 0) ?></div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-4 col-lg-2">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="text-muted small mb-1">Products</div>
                        <div class="fw-bold fs-5"><?= (int) ($totalProducts ?? 0) ?></div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-4 col-lg-2">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="text-muted small mb-1">Revenue today</div>
                        <div class="fw-bold fs-5">₹<?= number_format($revenueToday ?? 0, 2) ?></div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-4 col-lg-2">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="text-muted small mb-1">Total profit (paid)</div>
                        <div class="fw-bold fs-5">₹<?= number_format($totalProfit ?? 0, 2) ?></div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-4 col-lg-2">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="text-muted small mb-1">Profit this month</div>
                        <div class="fw-bold fs-5">₹<?= number_format($profitThisMonth ?? 0, 2) ?></div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-4 col-lg-2">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="text-muted small mb-1">Profit today</div>
                        <div class="fw-bold fs-5">₹<?= number_format($profitToday ?? 0, 2) ?></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="mb-4">
        <h2 class="h6 text-uppercase text-muted fw-semibold mb-3"><i class="bi bi-receipt me-1"></i>Sales report</h2>
        <div class="row g-3">
            <div class="col-12 col-md-6 col-lg-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-muted small">Invoices</span>
                            <span class="badge bg-secondary"><?= (int) ($totalInvoices ?? 0) ?></span>
                        </div>
                        <div class="fw-bold mt-1">₹<?= number_format($invoicedAmount ?? 0, 2) ?></div>
                        <a href="<?= base_url('sales/invoices') ?>" class="small">View invoices →</a>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="text-muted small">Revenue last month</div>
                        <div class="fw-bold mt-1">₹<?= number_format($revenueLastMonth ?? 0, 2) ?></div>
                        <span class="small <?= $revenueChange >= 0 ? 'text-success' : 'text-danger' ?>">
                            <?= $revenueChange >= 0 ? '↑' : '↓' ?> <?= number_format(abs($revenueChange), 1) ?>% vs last month
                        </span>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="text-muted small mb-2">Orders by status</div>
                        <div class="d-flex flex-wrap gap-2">
                            <span class="badge bg-warning text-dark">PENDING <?= (int) ($ordersByStatus['PENDING'] ?? 0) ?></span>
                            <span class="badge bg-info">CONFIRMED <?= (int) ($ordersByStatus['CONFIRMED'] ?? 0) ?></span>
                            <span class="badge bg-success">PAID <?= (int) ($ordersByStatus['PAID'] ?? 0) ?></span>
                            <span class="badge bg-secondary">CANCELLED <?= (int) ($ordersByStatus['CANCELLED'] ?? 0) ?></span>
                        </div>
                        <a href="<?= base_url('sales/orders') ?>" class="small d-block mt-2">View all orders →</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="row g-4">
        <div class="col-12 col-lg-7">
            <section>
                <h2 class="h6 text-uppercase text-muted fw-semibold mb-3">Recent orders</h2>
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-0">
                        <?php if (empty($recentOrders)): ?>
                            <p class="text-secondary mb-0 p-3">No orders yet.</p>
                        <?php else: ?>
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Order #</th>
                                            <th>Customer</th>
                                            <th class="text-end">Total</th>
                                            <th>Status</th>
                                            <th>Date</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($recentOrders as $o): ?>
                                            <tr>
                                                <td><code class="small"><?= esc($o['order_number'] ?? '') ?></code></td>
                                                <td><?= esc($o['customer_name'] ?? '—') ?></td>
                                                <td class="text-end">₹<?= number_format((float) ($o['total_amount'] ?? 0), 2) ?></td>
                                                <td><span class="badge bg-<?= ($o['status'] ?? '') === 'PAID' ? 'success' : (($o['status'] ?? '') === 'CANCELLED' ? 'secondary' : 'warning') ?>"><?= esc($o['status'] ?? '') ?></span></td>
                                                <td class="small"><?= esc($o['created_at'] ?? '') ?></td>
                                                <td><a href="<?= base_url('sales/orders') ?>" class="btn btn-sm btn-outline-primary">View</a></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                <a href="<?= base_url('sales/orders') ?>" class="btn btn-outline-secondary btn-sm mt-2">View all orders</a>
            </section>
        </div>
        <div class="col-12 col-lg-5">
            <section>
                <h2 class="h6 text-uppercase text-muted fw-semibold mb-3">Low stock (≤ <?= (int) ($lowStockThreshold ?? 5) ?> units)</h2>
                <?php if (empty($lowStockBatches)): ?>
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <p class="text-success mb-0">No low stock at the moment.</p>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="card border-0 shadow-sm">
                        <ul class="list-group list-group-flush">
                            <?php foreach ($lowStockBatches as $b): ?>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <span><?= esc($b['product_name'] ?? '—') ?> <code class="small"><?= esc($b['batch_code'] ?? '') ?></code></span>
                                    <span class="badge bg-danger"><?= (int) ($b['remaining_qty'] ?? 0) ?> left</span>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                        <div class="card-footer bg-transparent">
                            <a href="<?= base_url('inventory/stock-batches') ?>" class="small">Manage stock batches →</a>
                        </div>
                    </div>
                <?php endif; ?>
            </section>

            <section class="mt-4">
                <h2 class="h6 text-uppercase text-muted fw-semibold mb-3"><i class="bi bi-link-45deg me-1"></i>ERP quick links</h2>
                <div class="row g-2">
                    <div class="col-6">
                        <a href="<?= base_url('sales/orders/create') ?>" class="btn btn-outline-primary w-100"><i class="bi bi-cart-plus me-2"></i>Create order</a>
                    </div>
                    <div class="col-6">
                        <a href="<?= base_url('sales/customers') ?>" class="btn btn-outline-secondary w-100"><i class="bi bi-people me-2"></i>Customers</a>
                    </div>
                    <div class="col-6">
                        <a href="<?= base_url('catalog/products') ?>" class="btn btn-outline-secondary w-100"><i class="bi bi-box-seam me-2"></i>Products</a>
                    </div>
                    <div class="col-6">
                        <a href="<?= base_url('inventory/stock-batches') ?>" class="btn btn-outline-secondary w-100"><i class="bi bi-boxes me-2"></i>Stock batches</a>
                    </div>
                    <div class="col-6">
                        <a href="<?= base_url('sales/invoices') ?>" class="btn btn-outline-secondary w-100"><i class="bi bi-receipt me-2"></i>Invoices</a>
                    </div>
                    <div class="col-6">
                        <a href="<?= base_url('admin/activity-log') ?>" class="btn btn-outline-secondary w-100"><i class="bi bi-clipboard-data me-2"></i>Activity log</a>
                    </div>
                </div>
            </section>
        </div>
    </div>
</div>
