<?php
helper('form');
$items = $items ?? [];
?>
<div class="container py-4 px-3 px-sm-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3 mb-4">
        <div>
            <h1 class="h4 fw-semibold mb-1"><i class="bi bi-cup-straw me-2"></i>Food & Beverages</h1>
            <p class="text-secondary small mb-0">Manage food and beverage items for gaming.</p>
        </div>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#itemModal" id="btnAddItem"><i class="bi bi-plus-lg me-2"></i>Add item</button>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>Name</th>
                    <th>Unit</th>
                    <th>Price (₹)</th>
                    <th>Status</th>
                    <th class="text-end" style="width: 120px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($items)): ?>
                    <tr>
                        <td colspan="5" class="text-center text-secondary py-4">No items yet.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($items as $row): ?>
                        <?php $active = isset($row['is_active']) ? (int) $row['is_active'] : 1; ?>
                        <tr>
                            <td><?= esc($row['name']) ?></td>
                            <td><?= esc($row['unit_label'] ?? '—') ?></td>
                            <td><?= esc(number_format((float) ($row['price'] ?? 0), 2)) ?></td>
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
                                            <a class="dropdown-item item-edit" href="#" data-bs-toggle="modal" data-bs-target="#itemModal"
                                               data-id="<?= (int) $row['id'] ?>"
                                               data-name="<?= esc($row['name']) ?>"
                                               data-unit-label="<?= esc($row['unit_label'] ?? '') ?>"
                                               data-price="<?= esc($row['price'] ?? '') ?>"><i class="bi bi-pencil me-2"></i>Edit</a>
                                        </li>
                                        <?php if ($active): ?>
                                            <li>
                                                <?= form_open(base_url('gaming/food-beverages/set-status/' . (int) $row['id']), ['class' => 'd-inline']) ?>
                                                    <?= csrf_field() ?>
                                                    <input type="hidden" name="is_active" value="0">
                                                    <button type="submit" class="dropdown-item text-warning"><i class="bi bi-pause-circle me-2"></i>Mark inactive</button>
                                                <?= form_close() ?>
                                            </li>
                                        <?php else: ?>
                                            <li>
                                                <?= form_open(base_url('gaming/food-beverages/set-status/' . (int) $row['id']), ['class' => 'd-inline']) ?>
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

<!-- Add/Edit Item Modal -->
<div class="modal fade" id="itemModal" tabindex="-1" aria-labelledby="itemModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title fs-6" id="itemModalLabel">Add item</h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <?= form_open(base_url('gaming/food-beverages'), ['id' => 'itemForm']) ?>
                <?= csrf_field() ?>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="itemName" class="form-label">Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="itemName" name="name" required maxlength="100" value="">
                    </div>
                    <div class="mb-3">
                        <label for="itemUnitLabel" class="form-label">Unit (e.g. plate, glass, piece)</label>
                        <input type="text" class="form-control" id="itemUnitLabel" name="unit_label" maxlength="50" value="" placeholder="Optional">
                    </div>
                    <div class="mb-3">
                        <label for="itemPrice" class="form-label">Price (₹) <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" id="itemPrice" name="price" required step="0.01" min="0" value="">
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
    var form = document.getElementById('itemForm');
    var modalLabel = document.getElementById('itemModalLabel');
    var addUrl = '<?= base_url('gaming/food-beverages') ?>';
    var nameInput = document.getElementById('itemName');
    var unitInput = document.getElementById('itemUnitLabel');
    var priceInput = document.getElementById('itemPrice');

    document.getElementById('btnAddItem').addEventListener('click', function () {
        modalLabel.textContent = 'Add item';
        form.action = addUrl;
        nameInput.value = '';
        unitInput.value = '';
        priceInput.value = '';
    });

    document.querySelectorAll('.item-edit').forEach(function (el) {
        el.addEventListener('click', function (e) {
            e.preventDefault();
            var id = this.getAttribute('data-id');
            modalLabel.textContent = 'Edit item';
            form.action = '<?= base_url('gaming/food-beverages/update/') ?>' + id;
            nameInput.value = this.getAttribute('data-name') || '';
            unitInput.value = this.getAttribute('data-unit-label') || '';
            priceInput.value = this.getAttribute('data-price') || '';
        });
    });
})();
</script>
