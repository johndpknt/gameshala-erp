<div class="container py-4 px-3 px-sm-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3 mb-4">
        <h1 class="h4 fw-semibold mb-0">Vendors</h1>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#vendorModal" id="btnAddVendor">
            Add vendor
        </button>
    </div>

    <?php
    $vendorBase = base_url('catalog/vendors');
    $vendorSortUrl = function ($col) use ($vendorBase, $searchQ, $sort, $order) {
        $next = ($sort === $col && $order === 'asc') ? 'desc' : 'asc';
        return $vendorBase . '?' . http_build_query(array_filter(['q' => $searchQ, 'sort' => $col, 'order' => $next]));
    };
    $vendorArrow = function ($col) use ($sort, $order) {
        if ($sort !== $col) return '';
        return $order === 'asc' ? ' ↑' : ' ↓';
    };
    ?>
    <form method="get" action="<?= base_url('catalog/vendors') ?>" class="mb-4">
        <div class="input-group" style="max-width: 400px;">
            <input type="search" name="q" class="form-control" placeholder="Search by name, email, phone, contact person..."
                   value="<?= esc($searchQ) ?>">
            <button type="submit" class="btn btn-outline-secondary">Search</button>
        </div>
    </form>

    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th><a href="<?= $vendorSortUrl('name') ?>" class="text-dark text-decoration-none">Name<?= $vendorArrow('name') ?></a></th>
                    <th><a href="<?= $vendorSortUrl('contact_person') ?>" class="text-dark text-decoration-none">Contact person<?= $vendorArrow('contact_person') ?></a></th>
                    <th><a href="<?= $vendorSortUrl('phone') ?>" class="text-dark text-decoration-none">Phone<?= $vendorArrow('phone') ?></a></th>
                    <th><a href="<?= $vendorSortUrl('email') ?>" class="text-dark text-decoration-none">Email<?= $vendorArrow('email') ?></a></th>
                    <th><a href="<?= $vendorSortUrl('is_active') ?>" class="text-dark text-decoration-none">Status<?= $vendorArrow('is_active') ?></a></th>
                    <th class="text-end" style="width: 120px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($vendors)): ?>
                    <tr>
                        <td colspan="6" class="text-center text-secondary py-4">No vendors found.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($vendors as $v): ?>
                        <tr>
                            <td><?= esc($v['name']) ?></td>
                            <td><?= esc($v['contact_person'] ?? '—') ?></td>
                            <td><?= esc($v['phone'] ?? '—') ?></td>
                            <td><?= esc($v['email'] ?? '—') ?></td>
                            <td>
                                <?php $active = isset($v['is_active']) ? (int) $v['is_active'] : 1; ?>
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
                                            <a class="dropdown-item vendor-edit" href="#"
                                               data-id="<?= (int) $v['id'] ?>"
                                               data-name="<?= esc($v['name']) ?>"
                                               data-contact-person="<?= esc($v['contact_person'] ?? '') ?>"
                                               data-phone="<?= esc($v['phone'] ?? '') ?>"
                                               data-email="<?= esc($v['email'] ?? '') ?>"
                                               data-remarks="<?= esc($v['remarks'] ?? '') ?>">
                                                Edit
                                            </a>
                                        </li>
                                        <?php if ($active): ?>
                                            <li>
                                                <?= form_open(base_url('catalog/vendors/set-status/' . (int) $v['id']), ['class' => 'd-inline']) ?>
                                                    <?= csrf_field() ?>
                                                    <input type="hidden" name="is_active" value="0">
                                                    <button type="submit" class="dropdown-item text-warning">Mark inactive</button>
                                                <?= form_close() ?>
                                            </li>
                                        <?php else: ?>
                                            <li>
                                                <?= form_open(base_url('catalog/vendors/set-status/' . (int) $v['id']), ['class' => 'd-inline']) ?>
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

<!-- Add/Edit Vendor Modal -->
<div class="modal fade" id="vendorModal" tabindex="-1" aria-labelledby="vendorModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title fs-6" id="vendorModalLabel">Add vendor</h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <?= form_open('', ['id' => 'vendorForm']) ?>
                <?= csrf_field() ?>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="vendorName" class="form-label">Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="vendorName" name="name" required maxlength="150" value="">
                    </div>
                    <div class="mb-3">
                        <label for="vendorContactPerson" class="form-label">Contact person</label>
                        <input type="text" class="form-control" id="vendorContactPerson" name="contact_person" maxlength="150" value="">
                    </div>
                    <div class="mb-3">
                        <label for="vendorPhone" class="form-label">Phone</label>
                        <input type="text" class="form-control" id="vendorPhone" name="phone" maxlength="30" value="">
                    </div>
                    <div class="mb-3">
                        <label for="vendorEmail" class="form-label">Email</label>
                        <input type="email" class="form-control" id="vendorEmail" name="email" maxlength="191" value="">
                    </div>
                    <div class="mb-0">
                        <label for="vendorRemarks" class="form-label">Remarks</label>
                        <textarea class="form-control" id="vendorRemarks" name="remarks" rows="2" maxlength="500"></textarea>
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
    var form = document.getElementById('vendorForm');
    var modal = document.getElementById('vendorModal');
    var modalLabel = document.getElementById('vendorModalLabel');
    var addUrl = '<?= base_url('catalog/vendors') ?>';

    document.getElementById('btnAddVendor').addEventListener('click', function () {
        modalLabel.textContent = 'Add vendor';
        form.action = addUrl;
        form.reset();
    });

    document.querySelectorAll('.vendor-edit').forEach(function (el) {
        el.addEventListener('click', function (e) {
            e.preventDefault();
            var id = this.getAttribute('data-id');
            modalLabel.textContent = 'Edit vendor';
            form.action = '<?= base_url('catalog/vendors/update/') ?>' + id;
            document.getElementById('vendorName').value = this.getAttribute('data-name') || '';
            document.getElementById('vendorContactPerson').value = this.getAttribute('data-contact-person') || '';
            document.getElementById('vendorPhone').value = this.getAttribute('data-phone') || '';
            document.getElementById('vendorEmail').value = this.getAttribute('data-email') || '';
            document.getElementById('vendorRemarks').value = this.getAttribute('data-remarks') || '';
            new bootstrap.Modal(modal).show();
        });
    });
})();
</script>
