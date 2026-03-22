<div class="container py-4 px-3 px-sm-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3 mb-4">
        <h1 class="h4 fw-semibold mb-0">Procurement Rules</h1>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#ruleModal" id="btnAddRule">
            Add rule
        </button>
    </div>

    <?php
    $ruleBase = base_url('inventory/procurement-rules');
    $ruleSortUrl = function ($col) use ($ruleBase, $searchQ, $sort, $order) {
        $next = ($sort === $col && $order === 'asc') ? 'desc' : 'asc';
        return $ruleBase . '?' . http_build_query(array_filter(['q' => $searchQ, 'sort' => $col, 'order' => $next]));
    };
    $ruleArrow = function ($col) use ($sort, $order) {
        if ($sort !== $col) return '';
        return $order === 'asc' ? ' ↑' : ' ↓';
    };
    ?>
    <form method="get" action="<?= base_url('inventory/procurement-rules') ?>" class="mb-4">
        <div class="input-group" style="max-width: 400px;">
            <input type="search" name="q" class="form-control" placeholder="Search by name..."
                   value="<?= esc($searchQ) ?>">
            <button type="submit" class="btn btn-outline-secondary">Search</button>
        </div>
    </form>

    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th><a href="<?= $ruleSortUrl('name') ?>" class="text-dark text-decoration-none">Name<?= $ruleArrow('name') ?></a></th>
                    <th><a href="<?= $ruleSortUrl('discount_type') ?>" class="text-dark text-decoration-none">Discount<?= $ruleArrow('discount_type') ?></a></th>
                    <th><a href="<?= $ruleSortUrl('profit_type') ?>" class="text-dark text-decoration-none">Profit<?= $ruleArrow('profit_type') ?></a></th>
                    <th><a href="<?= $ruleSortUrl('is_active') ?>" class="text-dark text-decoration-none">Status<?= $ruleArrow('is_active') ?></a></th>
                    <th class="text-end" style="width: 120px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($rules)): ?>
                    <tr>
                        <td colspan="5" class="text-center text-secondary py-4">No procurement rules found.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($rules as $r): ?>
                        <tr>
                            <td><?= esc($r['name']) ?></td>
                            <td><?= esc($r['discount_type'] ?? 'FLAT') ?>: <?= esc($r['discount_value'] ?? '0') ?><?= ($r['discount_type'] ?? '') === 'PERCENTAGE' ? '%' : '' ?></td>
                            <td><?= esc($r['profit_type'] ?? 'FLAT') ?>: <?= esc($r['profit_value'] ?? '0') ?><?= ($r['profit_type'] ?? '') === 'PERCENTAGE' ? '%' : '' ?></td>
                            <td>
                                <?php $active = isset($r['is_active']) ? (int) $r['is_active'] : 1; ?>
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
                                        <?php if ($active): ?>
                                            <li>
                                                <?= form_open(base_url('inventory/procurement-rules/set-status/' . (int) $r['id']), ['class' => 'd-inline']) ?>
                                                    <?= csrf_field() ?>
                                                    <input type="hidden" name="is_active" value="0">
                                                    <button type="submit" class="dropdown-item text-warning">Mark inactive</button>
                                                <?= form_close() ?>
                                            </li>
                                        <?php else: ?>
                                            <li>
                                                <?= form_open(base_url('inventory/procurement-rules/set-status/' . (int) $r['id']), ['class' => 'd-inline']) ?>
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

<!-- Add Rule Modal (create only; rules cannot be edited) -->
<div class="modal fade" id="ruleModal" tabindex="-1" aria-labelledby="ruleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title fs-6" id="ruleModalLabel">Add procurement rule</h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <?= form_open(base_url('inventory/procurement-rules'), ['id' => 'ruleForm']) ?>
                <?= csrf_field() ?>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="ruleName" class="form-label">Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="ruleName" name="name" required maxlength="150" value="<?= esc(old('name')) ?>" placeholder="e.g. Standard vendor discount">
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="ruleDiscountType" class="form-label">Discount type <span class="text-danger">*</span></label>
                            <select class="form-select" id="ruleDiscountType" name="discount_type" required>
                                <option value="FLAT" <?= old('discount_type') === 'FLAT' ? 'selected' : '' ?>>FLAT</option>
                                <option value="PERCENTAGE" <?= old('discount_type') === 'PERCENTAGE' ? 'selected' : '' ?>>PERCENTAGE</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="ruleDiscountValue" class="form-label">Discount value <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="ruleDiscountValue" name="discount_value" step="0.01" min="0" required value="<?= esc(old('discount_value', '0')) ?>">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="ruleProfitType" class="form-label">Profit type <span class="text-danger">*</span></label>
                            <select class="form-select" id="ruleProfitType" name="profit_type" required>
                                <option value="FLAT" <?= old('profit_type') === 'FLAT' ? 'selected' : '' ?>>FLAT</option>
                                <option value="PERCENTAGE" <?= old('profit_type') === 'PERCENTAGE' ? 'selected' : '' ?>>PERCENTAGE</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="ruleProfitValue" class="form-label">Profit value <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="ruleProfitValue" name="profit_value" step="0.01" min="0" required value="<?= esc(old('profit_value', '0')) ?>">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add rule</button>
                </div>
            <?= form_close() ?>
        </div>
    </div>
</div>

<script>
(function () {
    document.getElementById('btnAddRule').addEventListener('click', function () {
        document.getElementById('ruleForm').reset();
        document.getElementById('ruleDiscountValue').value = '0';
        document.getElementById('ruleProfitValue').value = '0';
    });
})();
</script>
