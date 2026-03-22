<div class="container py-4 px-3 px-sm-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3 mb-4">
        <h1 class="h4 fw-semibold mb-0">Coupons</h1>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#couponModal" id="btnAddCoupon">
            Add coupon
        </button>
    </div>

    <?php
    $couponBase = base_url('catalog/coupons');
    $couponSortUrl = function ($col) use ($couponBase, $searchQ, $sort, $order) {
        $next = ($sort === $col && $order === 'asc') ? 'desc' : 'asc';
        return $couponBase . '?' . http_build_query(array_filter(['q' => $searchQ, 'sort' => $col, 'order' => $next]));
    };
    $couponArrow = function ($col) use ($sort, $order) {
        if ($sort !== $col) return '';
        return $order === 'asc' ? ' ↑' : ' ↓';
    };
    ?>
    <form method="get" action="<?= base_url('catalog/coupons') ?>" class="mb-4">
        <div class="input-group" style="max-width: 400px;">
            <input type="search" name="q" class="form-control" placeholder="Search by code..."
                   value="<?= esc($searchQ) ?>">
            <button type="submit" class="btn btn-outline-secondary">Search</button>
        </div>
    </form>

    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th><a href="<?= $couponSortUrl('code') ?>" class="text-dark text-decoration-none">Code<?= $couponArrow('code') ?></a></th>
                    <th><a href="<?= $couponSortUrl('discount_type') ?>" class="text-dark text-decoration-none">Discount<?= $couponArrow('discount_type') ?></a></th>
                    <th><a href="<?= $couponSortUrl('min_order_amount') ?>" class="text-dark text-decoration-none">Min order<?= $couponArrow('min_order_amount') ?></a></th>
                    <th><a href="<?= $couponSortUrl('max_discount_amount') ?>" class="text-dark text-decoration-none">Max discount<?= $couponArrow('max_discount_amount') ?></a></th>
                    <th><a href="<?= $couponSortUrl('valid_from') ?>" class="text-dark text-decoration-none">Valid from<?= $couponArrow('valid_from') ?></a></th>
                    <th><a href="<?= $couponSortUrl('valid_to') ?>" class="text-dark text-decoration-none">Valid to<?= $couponArrow('valid_to') ?></a></th>
                    <th><a href="<?= $couponSortUrl('used_count') ?>" class="text-dark text-decoration-none">Used<?= $couponArrow('used_count') ?></a></th>
                    <th><a href="<?= $couponSortUrl('is_active') ?>" class="text-dark text-decoration-none">Status<?= $couponArrow('is_active') ?></a></th>
                    <th class="text-end" style="width: 120px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($coupons)): ?>
                    <tr>
                        <td colspan="9" class="text-center text-secondary py-4">No coupons found.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($coupons as $c): ?>
                        <tr>
                            <td><code class="small"><?= esc($c['code']) ?></code></td>
                            <td><?= esc($c['discount_type']) ?>: <?= esc($c['discount_value']) ?><?= ($c['discount_type'] ?? '') === 'PERCENTAGE' ? '%' : '' ?></td>
                            <td><?= isset($c['min_order_amount']) && $c['min_order_amount'] !== null ? esc(number_format((float) $c['min_order_amount'], 2)) : '—' ?></td>
                            <td><?= isset($c['max_discount_amount']) && $c['max_discount_amount'] !== null ? esc(number_format((float) $c['max_discount_amount'], 2)) : '—' ?></td>
                            <td><?= esc(date('M j, Y', strtotime($c['valid_from']))) ?></td>
                            <td><?= esc(date('M j, Y', strtotime($c['valid_to']))) ?></td>
                            <td><?= (int) ($c['used_count'] ?? 0) ?><?= isset($c['usage_limit']) && $c['usage_limit'] !== null ? ' / ' . (int) $c['usage_limit'] : '' ?></td>
                            <td>
                                <?php $active = isset($c['is_active']) ? (int) $c['is_active'] : 1; ?>
                                <span class="badge <?= $active ? 'bg-success' : 'bg-secondary' ?>">
                                    <?= $active ? 'Active' : 'Inactive' ?>
                                </span>
                            </td>
                            <td class="text-end">
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        Action
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li>
                                            <a class="dropdown-item coupon-edit" href="#"
                                               data-id="<?= (int) $c['id'] ?>"
                                               data-code="<?= esc($c['code']) ?>"
                                               data-discount-type="<?= esc($c['discount_type'] ?? 'FLAT') ?>"
                                               data-discount-value="<?= esc($c['discount_value'] ?? '0') ?>"
                                               data-min-order-amount="<?= isset($c['min_order_amount']) && $c['min_order_amount'] !== null ? esc($c['min_order_amount']) : '' ?>"
                                               data-max-discount-amount="<?= isset($c['max_discount_amount']) && $c['max_discount_amount'] !== null ? esc($c['max_discount_amount']) : '' ?>"
                                               data-usage-limit="<?= isset($c['usage_limit']) && $c['usage_limit'] !== null ? (int) $c['usage_limit'] : '' ?>"
                                               data-valid-from="<?= esc(date('Y-m-d\TH:i', strtotime($c['valid_from']))) ?>"
                                               data-valid-to="<?= esc(date('Y-m-d\TH:i', strtotime($c['valid_to']))) ?>">
                                                Edit
                                            </a>
                                        </li>
                                        <?php if ($active): ?>
                                            <li>
                                                <?= form_open(base_url('catalog/coupons/set-status/' . (int) $c['id']), ['class' => 'd-inline']) ?>
                                                    <?= csrf_field() ?>
                                                    <input type="hidden" name="is_active" value="0">
                                                    <button type="submit" class="dropdown-item text-warning">Mark inactive</button>
                                                <?= form_close() ?>
                                            </li>
                                        <?php else: ?>
                                            <li>
                                                <?= form_open(base_url('catalog/coupons/set-status/' . (int) $c['id']), ['class' => 'd-inline']) ?>
                                                    <?= csrf_field() ?>
                                                    <input type="hidden" name="is_active" value="1">
                                                    <button type="submit" class="dropdown-item text-success">Mark active</button>
                                                <?= form_close() ?>
                                            </li>
                                        <?php endif; ?>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Add/Edit Coupon Modal -->
<div class="modal fade" id="couponModal" tabindex="-1" aria-labelledby="couponModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title fs-6" id="couponModalLabel">Add coupon</h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <?= form_open('', ['id' => 'couponForm']) ?>
                <?= csrf_field() ?>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="couponCode" class="form-label">Code <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="couponCode" name="code" required maxlength="100" value="" placeholder="e.g. SAVE10">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="couponDiscountType" class="form-label">Discount type <span class="text-danger">*</span></label>
                            <select class="form-select" id="couponDiscountType" name="discount_type" required>
                                <option value="FLAT">FLAT</option>
                                <option value="PERCENTAGE">PERCENTAGE</option>
                            </select>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="couponDiscountValue" class="form-label">Discount value <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="couponDiscountValue" name="discount_value" step="0.01" min="0" required value="0">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="couponMinOrderAmount" class="form-label">Min order amount</label>
                            <input type="number" class="form-control" id="couponMinOrderAmount" name="min_order_amount" step="0.01" min="0" value="" placeholder="Optional">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="couponMaxDiscountAmount" class="form-label">Max discount amount</label>
                            <input type="number" class="form-control" id="couponMaxDiscountAmount" name="max_discount_amount" step="0.01" min="0" value="" placeholder="Optional (for %)">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="couponUsageLimit" class="form-label">Usage limit</label>
                            <input type="number" class="form-control" id="couponUsageLimit" name="usage_limit" min="0" value="" placeholder="Optional">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="couponValidFrom" class="form-label">Valid from <span class="text-danger">*</span></label>
                            <input type="datetime-local" class="form-control" id="couponValidFrom" name="valid_from" required value="">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="couponValidTo" class="form-label">Valid to <span class="text-danger">*</span></label>
                            <input type="datetime-local" class="form-control" id="couponValidTo" name="valid_to" required value="">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            <?= form_close() ?>
        </div>
    </div>
</div>

<script>
(function () {
    var form = document.getElementById('couponForm');
    var modal = document.getElementById('couponModal');
    var modalLabel = document.getElementById('couponModalLabel');
    var addUrl = '<?= base_url('catalog/coupons') ?>';

    document.getElementById('btnAddCoupon').addEventListener('click', function () {
        modalLabel.textContent = 'Add coupon';
        form.action = addUrl;
        form.reset();
        document.getElementById('couponDiscountValue').value = '0';
    });

    document.querySelectorAll('.coupon-edit').forEach(function (el) {
        el.addEventListener('click', function (e) {
            e.preventDefault();
            var id = this.getAttribute('data-id');
            modalLabel.textContent = 'Edit coupon';
            form.action = '<?= base_url('catalog/coupons/update/') ?>' + id;
            document.getElementById('couponCode').value = this.getAttribute('data-code') || '';
            document.getElementById('couponDiscountType').value = this.getAttribute('data-discount-type') || 'FLAT';
            document.getElementById('couponDiscountValue').value = this.getAttribute('data-discount-value') || '0';
            document.getElementById('couponMinOrderAmount').value = this.getAttribute('data-min-order-amount') || '';
            document.getElementById('couponMaxDiscountAmount').value = this.getAttribute('data-max-discount-amount') || '';
            document.getElementById('couponUsageLimit').value = this.getAttribute('data-usage-limit') || '';
            document.getElementById('couponValidFrom').value = this.getAttribute('data-valid-from') || '';
            document.getElementById('couponValidTo').value = this.getAttribute('data-valid-to') || '';
            new bootstrap.Modal(modal).show();
        });
    });
})();
</script>
