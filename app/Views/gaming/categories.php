<?php
helper('form');
$categories = $categories ?? [];
$modes      = $modes ?? [];
?>
<div class="container py-4 px-3 px-sm-4">
    <h1 class="h4 fw-semibold mb-2"><i class="bi bi-tags me-2"></i>Gaming Consols, Packages &amp; Price</h1>
    <p class="text-secondary mb-4">Manage gaming consols and gaming packages.</p>

    <!-- Gaming Consol -->
    <section class="mb-5">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3 mb-3">
            <h2 class="h5 fw-semibold mb-0">Gaming Consol</h2>
            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#categoryModal" id="btnAddCategory"><i class="bi bi-plus-lg me-1"></i>Add Gaming Consol</button>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle table-sm">
                <thead class="table-light">
                    <tr>
                        <th>Name</th>
                        <th>Status</th>
                        <th class="text-end" style="width: 120px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($categories)): ?>
                        <tr><td colspan="3" class="text-center text-secondary py-3">No categories yet.</td></tr>
                    <?php else: ?>
                        <?php foreach ($categories as $c): ?>
                            <?php $active = isset($c['is_active']) ? (int) $c['is_active'] : 1; ?>
                            <tr>
                                <td><?= esc($c['name']) ?></td>
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
                                                <a class="dropdown-item category-edit" href="#" data-bs-toggle="modal" data-bs-target="#categoryModal"
                                                   data-id="<?= (int) $c['id'] ?>"
                                                   data-name="<?= esc($c['name']) ?>"><i class="bi bi-pencil me-2"></i>Edit</a>
                                            </li>
                                            <?php if ($active): ?>
                                                <li>
                                                    <?= form_open(base_url('gaming/categories/set-status/' . (int) $c['id']), ['class' => 'd-inline']) ?>
                                                        <?= csrf_field() ?>
                                                        <input type="hidden" name="is_active" value="0">
                                                        <button type="submit" class="dropdown-item text-warning"><i class="bi bi-pause-circle me-2"></i>Mark inactive</button>
                                                    <?= form_close() ?>
                                                </li>
                                            <?php else: ?>
                                                <li>
                                                    <?= form_open(base_url('gaming/categories/set-status/' . (int) $c['id']), ['class' => 'd-inline']) ?>
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
    </section>

    <!-- Gaming Packages -->
    <section>
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3 mb-3">
            <h2 class="h5 fw-semibold mb-0">Gaming Packages</h2>
            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modeModal" id="btnAddMode"><i class="bi bi-plus-lg me-1"></i>Add gaming package</button>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle table-sm">
                <thead class="table-light">
                    <tr>
                        <th>Name</th>
                        <th>Status</th>
                        <th class="text-end" style="width: 120px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($modes)): ?>
                        <tr><td colspan="3" class="text-center text-secondary py-3">No gaming packages yet.</td></tr>
                    <?php else: ?>
                        <?php foreach ($modes as $m): ?>
                            <?php $active = isset($m['is_active']) ? (int) $m['is_active'] : 1; ?>
                            <tr>
                                <td><?= esc($m['name']) ?></td>
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
                                                <a class="dropdown-item mode-edit" href="#" data-bs-toggle="modal" data-bs-target="#modeModal"
                                                   data-id="<?= (int) $m['id'] ?>"
                                                   data-name="<?= esc($m['name']) ?>"><i class="bi bi-pencil me-2"></i>Edit</a>
                                            </li>
                                            <?php if ($active): ?>
                                                <li>
                                                    <?= form_open(base_url('gaming/modes/set-status/' . (int) $m['id']), ['class' => 'd-inline']) ?>
                                                        <?= csrf_field() ?>
                                                        <input type="hidden" name="is_active" value="0">
                                                        <button type="submit" class="dropdown-item text-warning"><i class="bi bi-pause-circle me-2"></i>Mark inactive</button>
                                                    <?= form_close() ?>
                                                </li>
                                            <?php else: ?>
                                                <li>
                                                    <?= form_open(base_url('gaming/modes/set-status/' . (int) $m['id']), ['class' => 'd-inline']) ?>
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
    </section>
</div>

<!-- Category Modal -->
<div class="modal fade" id="categoryModal" tabindex="-1" aria-labelledby="categoryModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title fs-6" id="categoryModalLabel">Add category</h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <?= form_open(base_url('gaming/categories'), ['id' => 'categoryForm']) ?>
                <?= csrf_field() ?>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="categoryName" class="form-label">Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="categoryName" name="name" required maxlength="100" value="">
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

<!-- Gaming package modal -->
<div class="modal fade" id="modeModal" tabindex="-1" aria-labelledby="modeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title fs-6" id="modeModalLabel">Add gaming package</h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <?= form_open(base_url('gaming/modes'), ['id' => 'modeForm']) ?>
                <?= csrf_field() ?>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="modeName" class="form-label">Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="modeName" name="name" required maxlength="100" value="">
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
    var categoryForm = document.getElementById('categoryForm');
    var categoryModal = document.getElementById('categoryModal');
    var categoryLabel = document.getElementById('categoryModalLabel');
    var categoryName = document.getElementById('categoryName');
    var categoryAddUrl = '<?= base_url('gaming/categories') ?>';

    document.getElementById('btnAddCategory').addEventListener('click', function () {
        categoryLabel.textContent = 'Add category';
        categoryForm.action = categoryAddUrl;
        categoryName.value = '';
    });

    document.querySelectorAll('.category-edit').forEach(function (el) {
        el.addEventListener('click', function (e) {
            e.preventDefault();
            var id = this.getAttribute('data-id');
            categoryLabel.textContent = 'Edit category';
            categoryForm.action = '<?= base_url('gaming/categories/update/') ?>' + id;
            categoryName.value = this.getAttribute('data-name') || '';
        });
    });

    var modeForm = document.getElementById('modeForm');
    var modeLabel = document.getElementById('modeModalLabel');
    var modeName = document.getElementById('modeName');
    var modeAddUrl = '<?= base_url('gaming/modes') ?>';

    document.getElementById('btnAddMode').addEventListener('click', function () {
        modeLabel.textContent = 'Add gaming package';
        modeForm.action = modeAddUrl;
        modeName.value = '';
    });

    document.querySelectorAll('.mode-edit').forEach(function (el) {
        el.addEventListener('click', function (e) {
            e.preventDefault();
            var id = this.getAttribute('data-id');
            modeLabel.textContent = 'Edit gaming package';
            modeForm.action = '<?= base_url('gaming/modes/update/') ?>' + id;
            modeName.value = this.getAttribute('data-name') || '';
        });
    });
})();
</script>
