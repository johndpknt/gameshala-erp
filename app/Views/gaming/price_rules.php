<?php
helper('form');
$categories = $categories ?? [];
$modes      = $modes ?? [];
$rules      = $rules ?? [];

$priceTypeLabels = [
    'MIN_15' => '15 min',
    'MIN_25' => '25 min',
    'MIN_45' => '45 min',
    'MIN_60' => '60 min',
];
?>
<div class="container py-4 px-3 px-sm-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3 mb-4">
        <div>
            <h1 class="h4 fw-semibold mb-1"><i class="bi bi-currency-rupee me-2"></i>Price Rules</h1>
            <p class="text-secondary small mb-0">Manage gaming price rules by consol and gaming package.</p>
        </div>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#priceRuleModal" id="btnAddPriceRule"><i class="bi bi-plus-lg me-2"></i>Add price rule</button>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>Category</th>
                    <th>Gaming package</th>
                    <th>Time duration</th>
                    <th>Price (₹)</th>
                    <th>Status</th>
                    <th class="text-end" style="width: 120px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($rules)): ?>
                    <tr>
                        <td colspan="6" class="text-center text-secondary py-4">No price rules yet. Add consols and gaming packages first, then add a price rule.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($rules as $r): ?>
                        <?php $active = isset($r['is_active']) ? (int) $r['is_active'] : 1; ?>
                        <tr>
                            <td><?= esc($r['category_name'] ?? '—') ?></td>
                            <td><?= esc($r['mode_name'] ?? '—') ?></td>
                            <td><?= esc($priceTypeLabels[$r['price_type'] ?? ''] ?? $r['price_type'] ?? '—') ?></td>
                            <td><?= esc(number_format((float) ($r['price'] ?? 0), 2)) ?></td>
                            <td>
                                <span class="badge <?= $active ? 'bg-success' : 'bg-secondary' ?>">
                                    <?= $active ? 'Active' : 'Inactive' ?>
                                </span>
                            </td>
                            <td class="text-end">
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="bi bi-three-dots-vertical me-1"></i>Action</button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li>
                                            <a class="dropdown-item price-rule-edit" href="#" data-bs-toggle="modal" data-bs-target="#priceRuleModal"
                                               data-id="<?= (int) $r['id'] ?>"
                                               data-category-id="<?= (int) ($r['gaming_category_id'] ?? 0) ?>"
                                               data-mode-id="<?= (int) ($r['gaming_mode_id'] ?? 0) ?>"
                                               data-price-type="<?= esc($r['price_type'] ?? '') ?>"
                                               data-price="<?= esc($r['price'] ?? '') ?>"><i class="bi bi-pencil me-2"></i>Edit</a>
                                        </li>
                                        <?php if ($active): ?>
                                            <li>
                                                <?= form_open(base_url('gaming/price-rules/set-status/' . (int) $r['id']), ['class' => 'd-inline']) ?>
                                                    <?= csrf_field() ?>
                                                    <input type="hidden" name="is_active" value="0">
                                                    <button type="submit" class="dropdown-item text-warning"><i class="bi bi-pause-circle me-2"></i>Mark inactive</button>
                                                <?= form_close() ?>
                                            </li>
                                        <?php else: ?>
                                            <li>
                                                <?= form_open(base_url('gaming/price-rules/set-status/' . (int) $r['id']), ['class' => 'd-inline']) ?>
                                                    <?= csrf_field() ?>
                                                    <input type="hidden" name="is_active" value="1">
                                                    <button type="submit" class="dropdown-item text-success"><i class="bi bi-play-circle me-2"></i>Mark active</button>
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

<!-- Add/Edit Price Rule Modal -->
<div class="modal fade" id="priceRuleModal" tabindex="-1" aria-labelledby="priceRuleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title fs-6" id="priceRuleModalLabel">Add price rule</h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <?= form_open(base_url('gaming/price-rules'), ['id' => 'priceRuleForm']) ?>
                <?= csrf_field() ?>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="priceRuleCategory" class="form-label">Category <span class="text-danger">*</span></label>
                        <select class="form-select" id="priceRuleCategory" name="gaming_category_id" required>
                            <option value="">Select category</option>
                            <?php foreach ($categories as $c): ?>
                                <option value="<?= (int) $c['id'] ?>"><?= esc($c['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="priceRuleMode" class="form-label">Gaming package <span class="text-danger">*</span></label>
                        <select class="form-select" id="priceRuleMode" name="gaming_mode_id" required>
                            <option value="">Select gaming package</option>
                            <?php foreach ($modes as $m): ?>
                                <option value="<?= (int) $m['id'] ?>"><?= esc($m['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="priceRuleType" class="form-label">Time duration <span class="text-danger">*</span></label>
                        <select class="form-select" id="priceRuleType" name="price_type" required>
                            <option value="">Select time duration</option>
                            <?php foreach ($priceTypeLabels as $value => $label): ?>
                                <option value="<?= esc($value) ?>"><?= esc($label) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="priceRulePrice" class="form-label">Price (₹) <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" id="priceRulePrice" name="price" required step="0.01" min="0" value="">
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
    var form = document.getElementById('priceRuleForm');
    var modalLabel = document.getElementById('priceRuleModalLabel');
    var addUrl = '<?= base_url('gaming/price-rules') ?>';
    var categorySelect = document.getElementById('priceRuleCategory');
    var modeSelect = document.getElementById('priceRuleMode');
    var typeSelect = document.getElementById('priceRuleType');
    var priceInput = document.getElementById('priceRulePrice');

    document.getElementById('btnAddPriceRule').addEventListener('click', function () {
        modalLabel.textContent = 'Add price rule';
        form.action = addUrl;
        categorySelect.value = '';
        modeSelect.value = '';
        typeSelect.value = '';
        priceInput.value = '';
    });

    document.querySelectorAll('.price-rule-edit').forEach(function (el) {
        el.addEventListener('click', function (e) {
            e.preventDefault();
            var id = this.getAttribute('data-id');
            modalLabel.textContent = 'Edit price rule';
            form.action = '<?= base_url('gaming/price-rules/update/') ?>' + id;
            categorySelect.value = this.getAttribute('data-category-id') || '';
            modeSelect.value = this.getAttribute('data-mode-id') || '';
            typeSelect.value = this.getAttribute('data-price-type') || '';
            priceInput.value = this.getAttribute('data-price') || '';
        });
    });
})();
</script>
