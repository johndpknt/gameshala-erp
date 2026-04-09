<?php
helper('form');
$categories = $categories ?? [];
$controllers = $controllers ?? [];
$titles    = $titles ?? [];
$modes      = $modes ?? [];
?>
<div class="container py-4 px-3 px-sm-4">
    <h1 class="h4 fw-semibold mb-2"><i class="bi bi-tags me-2"></i>Categories</h1>
    <p class="text-secondary mb-3">Manage gaming categories, controller names, gaming titles, and gaming modes.</p>

    <ul class="nav nav-tabs mb-3" id="gamingCategoriesTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="tab-master-1" data-bs-toggle="tab" data-bs-target="#pane-master-1" type="button" role="tab" aria-controls="pane-master-1" aria-selected="true">Gaming Categories &amp; Controller Names</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="tab-master-2" data-bs-toggle="tab" data-bs-target="#pane-master-2" type="button" role="tab" aria-controls="pane-master-2" aria-selected="false">Gaming Titles</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="tab-master-3" data-bs-toggle="tab" data-bs-target="#pane-master-3" type="button" role="tab" aria-controls="pane-master-3" aria-selected="false">Gaming Modes</button>
        </li>
    </ul>

    <div class="tab-content" id="gamingCategoriesTabsContent">
        <div class="tab-pane fade show active pt-2" id="pane-master-1" role="tabpanel" aria-labelledby="tab-master-1" tabindex="0">
            <!-- Gaming Categories -->
            <section class="mb-5">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3 mb-3">
            <h2 class="h5 fw-semibold mb-0">Gaming Categories</h2>
            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#categoryModal" id="btnAddCategory"><i class="bi bi-plus-lg me-1"></i>Add category</button>
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

            <!-- Controller Names -->
            <section class="mb-2">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3 mb-3">
            <h2 class="h5 fw-semibold mb-0">Controller Names</h2>
            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#controllerModal" id="btnAddController"><i class="bi bi-plus-lg me-1"></i>Add controller</button>
        </div>
        <p class="text-secondary small">Create controller names like GS1, GS2 and map each to a gaming category.</p>
        <div class="table-responsive">
            <table class="table table-hover align-middle table-sm">
                <thead class="table-light">
                    <tr>
                        <th>Controller Name</th>
                        <th>Gaming Category</th>
                        <th>Status</th>
                        <th class="text-end" style="width: 120px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($controllers)): ?>
                        <tr><td colspan="4" class="text-center text-secondary py-3">No controller names yet.</td></tr>
                    <?php else: ?>
                        <?php foreach ($controllers as $gc): ?>
                            <?php $active = isset($gc['is_active']) ? (int) $gc['is_active'] : 1; ?>
                            <tr>
                                <td><?= esc($gc['name']) ?></td>
                                <td><?= esc($gc['category_name'] ?? '—') ?></td>
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
                                                <a class="dropdown-item controller-edit" href="#" data-bs-toggle="modal" data-bs-target="#controllerModal"
                                                   data-id="<?= (int) $gc['id'] ?>"
                                                   data-name="<?= esc($gc['name']) ?>"
                                                   data-category-id="<?= (int) ($gc['gaming_category_id'] ?? 0) ?>"><i class="bi bi-pencil me-2"></i>Edit</a>
                                            </li>
                                            <?php if ($active): ?>
                                                <li>
                                                    <?= form_open(base_url('gaming/controllers/set-status/' . (int) $gc['id']), ['class' => 'd-inline']) ?>
                                                        <?= csrf_field() ?>
                                                        <input type="hidden" name="is_active" value="0">
                                                        <button type="submit" class="dropdown-item text-warning"><i class="bi bi-pause-circle me-2"></i>Mark inactive</button>
                                                    <?= form_close() ?>
                                                </li>
                                            <?php else: ?>
                                                <li>
                                                    <?= form_open(base_url('gaming/controllers/set-status/' . (int) $gc['id']), ['class' => 'd-inline']) ?>
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

        <div class="tab-pane fade pt-2" id="pane-master-2" role="tabpanel" aria-labelledby="tab-master-2" tabindex="0">
            <!-- Gaming Titles -->
            <section class="mb-2">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3 mb-3">
            <h2 class="h5 fw-semibold mb-0">Gaming Titles</h2>
            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#titleModal" id="btnAddTitle"><i class="bi bi-plus-lg me-1"></i>Add title</button>
        </div>
        <p class="text-secondary small">Create gaming titles and map each one to a controller name.</p>
        <div class="table-responsive">
            <table class="table table-hover align-middle table-sm">
                <thead class="table-light">
                    <tr>
                        <th>Gaming Title</th>
                        <th>Controller Name</th>
                        <th>Gaming Category</th>
                        <th>Status</th>
                        <th class="text-end" style="width: 120px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($titles)): ?>
                        <tr><td colspan="5" class="text-center text-secondary py-3">No gaming titles yet.</td></tr>
                    <?php else: ?>
                        <?php foreach ($titles as $gt): ?>
                            <?php $active = isset($gt['is_active']) ? (int) $gt['is_active'] : 1; ?>
                            <tr>
                                <td><?= esc($gt['name']) ?></td>
                                <td><?= esc($gt['controller_name'] ?? '—') ?></td>
                                <td><?= esc($gt['category_name'] ?? '—') ?></td>
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
                                                <a class="dropdown-item title-edit" href="#" data-bs-toggle="modal" data-bs-target="#titleModal"
                                                   data-id="<?= (int) $gt['id'] ?>"
                                                   data-name="<?= esc($gt['name']) ?>"
                                                   data-controller-id="<?= (int) ($gt['gaming_controller_id'] ?? 0) ?>"><i class="bi bi-pencil me-2"></i>Edit</a>
                                            </li>
                                            <?php if ($active): ?>
                                                <li>
                                                    <?= form_open(base_url('gaming/titles/set-status/' . (int) $gt['id']), ['class' => 'd-inline']) ?>
                                                        <?= csrf_field() ?>
                                                        <input type="hidden" name="is_active" value="0">
                                                        <button type="submit" class="dropdown-item text-warning"><i class="bi bi-pause-circle me-2"></i>Mark inactive</button>
                                                    <?= form_close() ?>
                                                </li>
                                            <?php else: ?>
                                                <li>
                                                    <?= form_open(base_url('gaming/titles/set-status/' . (int) $gt['id']), ['class' => 'd-inline']) ?>
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

        <div class="tab-pane fade pt-2" id="pane-master-3" role="tabpanel" aria-labelledby="tab-master-3" tabindex="0">
            <!-- Gaming Modes -->
            <section>
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3 mb-3">
            <h2 class="h5 fw-semibold mb-0">Gaming Modes</h2>
            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modeModal" id="btnAddMode"><i class="bi bi-plus-lg me-1"></i>Add mode</button>
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
                        <tr><td colspan="3" class="text-center text-secondary py-3">No modes yet.</td></tr>
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
    </div>
</div>

<!-- Title Modal -->
<div class="modal fade" id="titleModal" tabindex="-1" aria-labelledby="titleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title fs-6" id="titleModalLabel">Add gaming title</h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <?= form_open(base_url('gaming/titles'), ['id' => 'titleForm']) ?>
                <?= csrf_field() ?>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="titleName" class="form-label">Gaming Title <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="titleName" name="name" required maxlength="150" value="" placeholder="e.g. FIFA 24">
                    </div>
                    <div class="mb-1">
                        <label for="titleControllerId" class="form-label">Controller Name <span class="text-danger">*</span></label>
                        <select class="form-select" id="titleControllerId" name="gaming_controller_id" required>
                            <option value="">Select controller</option>
                            <?php foreach ($controllers as $gc): ?>
                                <option value="<?= (int) $gc['id'] ?>"><?= esc($gc['name']) ?> (<?= esc($gc['category_name'] ?? '—') ?>)</option>
                            <?php endforeach; ?>
                        </select>
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

<!-- Controller Modal -->
<div class="modal fade" id="controllerModal" tabindex="-1" aria-labelledby="controllerModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title fs-6" id="controllerModalLabel">Add controller</h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <?= form_open(base_url('gaming/controllers'), ['id' => 'controllerForm']) ?>
                <?= csrf_field() ?>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="controllerName" class="form-label">Controller Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control text-uppercase" id="controllerName" name="name" required maxlength="50" value="" placeholder="GS1">
                    </div>
                    <div class="mb-1">
                        <label for="controllerCategoryId" class="form-label">Gaming Category <span class="text-danger">*</span></label>
                        <select class="form-select" id="controllerCategoryId" name="gaming_category_id" required>
                            <option value="">Select category</option>
                            <?php foreach ($categories as $c): ?>
                                <option value="<?= (int) $c['id'] ?>"><?= esc($c['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
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

<!-- Mode Modal -->
<div class="modal fade" id="modeModal" tabindex="-1" aria-labelledby="modeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title fs-6" id="modeModalLabel">Add mode</h2>
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

    var controllerForm = document.getElementById('controllerForm');
    var controllerLabel = document.getElementById('controllerModalLabel');
    var controllerName = document.getElementById('controllerName');
    var controllerCategoryId = document.getElementById('controllerCategoryId');
    var controllerAddUrl = '<?= base_url('gaming/controllers') ?>';

    document.getElementById('btnAddController').addEventListener('click', function () {
        controllerLabel.textContent = 'Add controller';
        controllerForm.action = controllerAddUrl;
        controllerName.value = '';
        controllerCategoryId.value = '';
    });

    document.querySelectorAll('.controller-edit').forEach(function (el) {
        el.addEventListener('click', function (e) {
            e.preventDefault();
            var id = this.getAttribute('data-id');
            controllerLabel.textContent = 'Edit controller';
            controllerForm.action = '<?= base_url('gaming/controllers/update/') ?>' + id;
            controllerName.value = this.getAttribute('data-name') || '';
            controllerCategoryId.value = this.getAttribute('data-category-id') || '';
        });
    });

    var titleForm = document.getElementById('titleForm');
    var titleLabel = document.getElementById('titleModalLabel');
    var titleName = document.getElementById('titleName');
    var titleControllerId = document.getElementById('titleControllerId');
    var titleAddUrl = '<?= base_url('gaming/titles') ?>';

    document.getElementById('btnAddTitle').addEventListener('click', function () {
        titleLabel.textContent = 'Add gaming title';
        titleForm.action = titleAddUrl;
        titleName.value = '';
        titleControllerId.value = '';
    });

    document.querySelectorAll('.title-edit').forEach(function (el) {
        el.addEventListener('click', function (e) {
            e.preventDefault();
            var id = this.getAttribute('data-id');
            titleLabel.textContent = 'Edit gaming title';
            titleForm.action = '<?= base_url('gaming/titles/update/') ?>' + id;
            titleName.value = this.getAttribute('data-name') || '';
            titleControllerId.value = this.getAttribute('data-controller-id') || '';
        });
    });

    var modeForm = document.getElementById('modeForm');
    var modeLabel = document.getElementById('modeModalLabel');
    var modeName = document.getElementById('modeName');
    var modeAddUrl = '<?= base_url('gaming/modes') ?>';

    document.getElementById('btnAddMode').addEventListener('click', function () {
        modeLabel.textContent = 'Add mode';
        modeForm.action = modeAddUrl;
        modeName.value = '';
    });

    document.querySelectorAll('.mode-edit').forEach(function (el) {
        el.addEventListener('click', function (e) {
            e.preventDefault();
            var id = this.getAttribute('data-id');
            modeLabel.textContent = 'Edit mode';
            modeForm.action = '<?= base_url('gaming/modes/update/') ?>' + id;
            modeName.value = this.getAttribute('data-name') || '';
        });
    });
})();
</script>
