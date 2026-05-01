<div class="container py-4 px-3 px-sm-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3 mb-4">
        <h1 class="h4 fw-semibold mb-0">Stock Batches</h1>
        <a href="<?= base_url('inventory/stock-batches/add') ?>" class="btn btn-primary">Add batch</a>
    </div>

    <?php
    $batchBase = base_url('inventory/stock-batches');
    $allowedTabs = ['all', 'toy', 'electronic', 'drone', 'beverage'];
    $activeTab = in_array(($activeTab ?? 'all'), $allowedTabs, true) ? $activeTab : 'all';
    $batchSortUrl = function ($col) use ($batchBase, $searchQ, $sort, $order, $activeTab) {
        $next = ($sort === $col && $order === 'asc') ? 'desc' : 'asc';
        return $batchBase . '?' . http_build_query(array_filter(['tab' => $activeTab, 'q' => $searchQ, 'sort' => $col, 'order' => $next]));
    };
    $batchArrow = function ($col) use ($sort, $order) {
        if ($sort !== $col) return '';
        return $order === 'asc' ? ' ↑' : ' ↓';
    };
    ?>
    <ul class="nav nav-tabs mb-3">
        <li class="nav-item">
            <a class="nav-link <?= $activeTab === 'all' ? 'active' : '' ?>" href="<?= $batchBase ?>">All</a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?= $activeTab === 'toy' ? 'active' : '' ?>" href="<?= $batchBase . '?tab=toy' ?>">TY</a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?= $activeTab === 'electronic' ? 'active' : '' ?>" href="<?= $batchBase . '?tab=electronic' ?>">ELC</a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?= $activeTab === 'drone' ? 'active' : '' ?>" href="<?= $batchBase . '?tab=drone' ?>">DRN</a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?= $activeTab === 'beverage' ? 'active' : '' ?>" href="<?= $batchBase . '?tab=beverage' ?>">Beverage</a>
        </li>
    </ul>

    <form method="get" action="<?= base_url('inventory/stock-batches') ?>" class="mb-4">
        <input type="hidden" name="tab" value="<?= esc($activeTab) ?>">
        <div class="input-group" style="max-width: 400px;">
            <input type="search" name="q" class="form-control" placeholder="Search by batch code, product, vendor..."
                   value="<?= esc($searchQ) ?>">
            <button type="submit" class="btn btn-outline-secondary">Search</button>
        </div>
    </form>

    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th><a href="<?= $batchSortUrl('batch_code') ?>" class="text-dark text-decoration-none">Batch code<?= $batchArrow('batch_code') ?></a></th>
                    <th>SKU</th>
                    <th><a href="<?= $batchSortUrl('product_name') ?>" class="text-dark text-decoration-none">Product<?= $batchArrow('product_name') ?></a></th>
                    <th><a href="<?= $batchSortUrl('vendor_name') ?>" class="text-dark text-decoration-none">Vendor<?= $batchArrow('vendor_name') ?></a></th>
                    <th>Rule</th>
                    <th class="text-end"><a href="<?= $batchSortUrl('purchased_qty') ?>" class="text-dark text-decoration-none">Purchased<?= $batchArrow('purchased_qty') ?></a></th>
                    <th class="text-end"><a href="<?= $batchSortUrl('remaining_qty') ?>" class="text-dark text-decoration-none">Remaining<?= $batchArrow('remaining_qty') ?></a></th>
                    <th class="text-end"><a href="<?= $batchSortUrl('unit_cost') ?>" class="text-dark text-decoration-none">Unit cost<?= $batchArrow('unit_cost') ?></a></th>
                    <th class="text-end"><a href="<?= $batchSortUrl('selling_price') ?>" class="text-dark text-decoration-none">Selling price<?= $batchArrow('selling_price') ?></a></th>
                    <th><a href="<?= $batchSortUrl('received_at') ?>" class="text-dark text-decoration-none">Received at<?= $batchArrow('received_at') ?></a></th>
                    <th class="text-end" style="width: 100px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($batches)): ?>
                    <tr>
                        <td colspan="11" class="text-center text-secondary py-4">No stock batches found.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($batches as $b): ?>
                        <tr>
                            <td><code class="small"><?= esc($b['batch_code']) ?></code></td>
                            <td><code class="small"><?= esc($b['product_sku'] ?? '—') ?></code></td>
                            <td><?= esc($b['product_name'] ?? '—') ?></td>
                            <td><?= esc($b['vendor_name'] ?? '—') ?></td>
                            <td><?php
                                $ruleName = $b['rule_name'] ?? '';
                                if ($ruleName !== ''): ?>
                                    <a href="<?= base_url('inventory/procurement-rules?' . http_build_query(['q' => $ruleName])) ?>"><?= esc($ruleName) ?></a>
                                <?php else: ?>
                                    —
                                <?php endif; ?></td>
                            <td class="text-end"><?= (int) $b['purchased_qty'] ?></td>
                            <td class="text-end"><?= (int) $b['remaining_qty'] ?></td>
                            <td class="text-end"><?= esc(number_format((float) ($b['unit_cost'] ?? 0), 2)) ?></td>
                            <td class="text-end"><?php
                                $sp = $b['selling_price_display'] ?? null;
                                echo ($sp !== null && $sp !== '') ? esc(number_format((float) $sp, 2)) : '—';
                            ?></td>
                            <td><?= esc(date('M j, Y', strtotime($b['received_at']))) ?></td>
                            <td class="text-end">
                                <a href="<?= base_url('inventory/stock-batches/edit/' . (int) $b['id']) ?>" class="btn btn-sm btn-outline-primary">Edit</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
