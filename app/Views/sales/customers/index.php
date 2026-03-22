<div class="container py-4 px-3 px-sm-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3 mb-4">
        <h1 class="h4 fw-semibold mb-0">Customers</h1>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#customerModal" id="btnAddCustomer">
            Add customer
        </button>
    </div>

    <?php
    $customerBase = base_url('sales/customers');
    $customerSortUrl = function ($col) use ($customerBase, $searchQ, $sort, $order) {
        $next = ($sort === $col && $order === 'asc') ? 'desc' : 'asc';
        return $customerBase . '?' . http_build_query(array_filter(['q' => $searchQ, 'sort' => $col, 'order' => $next]));
    };
    $customerArrow = function ($col) use ($sort, $order) {
        if ($sort !== $col) return '';
        return $order === 'asc' ? ' ↑' : ' ↓';
    };
    ?>
    <form method="get" action="<?= base_url('sales/customers') ?>" class="mb-4">
        <div class="input-group" style="max-width: 400px;">
            <input type="search" name="q" class="form-control" placeholder="Search by name, phone, email..."
                   value="<?= esc($searchQ) ?>">
            <button type="submit" class="btn btn-outline-secondary">Search</button>
        </div>
    </form>

    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th><a href="<?= $customerSortUrl('customer_type') ?>" class="text-dark text-decoration-none">Type<?= $customerArrow('customer_type') ?></a></th>
                    <th><a href="<?= $customerSortUrl('name') ?>" class="text-dark text-decoration-none">Name<?= $customerArrow('name') ?></a></th>
                    <th><a href="<?= $customerSortUrl('phone') ?>" class="text-dark text-decoration-none">Phone<?= $customerArrow('phone') ?></a></th>
                    <th><a href="<?= $customerSortUrl('email') ?>" class="text-dark text-decoration-none">Email<?= $customerArrow('email') ?></a></th>
                    <th><a href="<?= $customerSortUrl('city') ?>" class="text-dark text-decoration-none">City<?= $customerArrow('city') ?></a></th>
                    <th><a href="<?= $customerSortUrl('is_active') ?>" class="text-dark text-decoration-none">Status<?= $customerArrow('is_active') ?></a></th>
                    <th class="text-end" style="width: 120px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($customers)): ?>
                    <tr>
                        <td colspan="7" class="text-center text-secondary py-4">No customers found.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($customers as $c): ?>
                        <tr>
                            <td><?= esc($c['customer_type'] ?? 'INDIVIDUAL') ?></td>
                            <td><?= esc($c['name']) ?></td>
                            <td><?= esc($c['phone'] ?? '—') ?></td>
                            <td><?= esc($c['email'] ?? '—') ?></td>
                            <td><?= esc($c['city'] ?? '—') ?></td>
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
                                            <a class="dropdown-item customer-edit" href="#"
                                               data-id="<?= (int) $c['id'] ?>"
                                               data-customer-type="<?= esc($c['customer_type'] ?? 'INDIVIDUAL') ?>"
                                               data-name="<?= esc($c['name']) ?>"
                                               data-phone="<?= esc($c['phone'] ?? '') ?>"
                                               data-email="<?= esc($c['email'] ?? '') ?>"
                                               data-address-line1="<?= esc($c['address_line1'] ?? '') ?>"
                                               data-address-line2="<?= esc($c['address_line2'] ?? '') ?>"
                                               data-city="<?= esc($c['city'] ?? '') ?>"
                                               data-state="<?= esc($c['state'] ?? '') ?>"
                                               data-postal-code="<?= esc($c['postal_code'] ?? '') ?>"
                                               data-tax-number="<?= esc($c['tax_number'] ?? '') ?>"
                                               data-notes="<?= esc($c['notes'] ?? '') ?>">
                                                Edit
                                            </a>
                                        </li>
                                        <?php if ($active): ?>
                                            <li>
                                                <?= form_open(base_url('sales/customers/set-status/' . (int) $c['id']), ['class' => 'd-inline']) ?>
                                                    <?= csrf_field() ?>
                                                    <input type="hidden" name="is_active" value="0">
                                                    <button type="submit" class="dropdown-item text-warning">Mark inactive</button>
                                                <?= form_close() ?>
                                            </li>
                                        <?php else: ?>
                                            <li>
                                                <?= form_open(base_url('sales/customers/set-status/' . (int) $c['id']), ['class' => 'd-inline']) ?>
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

<!-- Add/Edit Customer Modal -->
<div class="modal fade" id="customerModal" tabindex="-1" aria-labelledby="customerModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title fs-6" id="customerModalLabel">Add customer</h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <?= form_open('', ['id' => 'customerForm']) ?>
                <?= csrf_field() ?>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="customerType" class="form-label">Type <span class="text-danger">*</span></label>
                            <select class="form-select" id="customerType" name="customer_type" required>
                                <option value="INDIVIDUAL">INDIVIDUAL</option>
                                <option value="BUSINESS">BUSINESS</option>
                                <option value="WALK_IN">WALK_IN</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="customerName" class="form-label">Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="customerName" name="name" required maxlength="150" value="">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="customerPhone" class="form-label">Phone <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="customerPhone" name="phone" required maxlength="30" value="">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="customerEmail" class="form-label">Email</label>
                            <input type="email" class="form-control" id="customerEmail" name="email" maxlength="191" value="">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="customerAddress1" class="form-label">Address line 1</label>
                        <input type="text" class="form-control" id="customerAddress1" name="address_line1" maxlength="255" value="">
                    </div>
                    <div class="mb-3">
                        <label for="customerAddress2" class="form-label">Address line 2</label>
                        <input type="text" class="form-control" id="customerAddress2" name="address_line2" maxlength="255" value="">
                    </div>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="customerCity" class="form-label">City</label>
                            <input type="text" class="form-control" id="customerCity" name="city" maxlength="100" value="">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="customerState" class="form-label">State</label>
                            <input type="text" class="form-control" id="customerState" name="state" maxlength="100" value="">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="customerPostalCode" class="form-label">Postal code</label>
                            <input type="text" class="form-control" id="customerPostalCode" name="postal_code" maxlength="20" value="">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="customerTaxNumber" class="form-label">Tax number</label>
                            <input type="text" class="form-control" id="customerTaxNumber" name="tax_number" maxlength="100" value="">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="customerNotes" class="form-label">Notes</label>
                        <textarea class="form-control" id="customerNotes" name="notes" rows="2" maxlength="500"></textarea>
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
    var form = document.getElementById('customerForm');
    var modal = document.getElementById('customerModal');
    var modalLabel = document.getElementById('customerModalLabel');
    var addUrl = '<?= base_url('sales/customers') ?>';

    document.getElementById('btnAddCustomer').addEventListener('click', function () {
        modalLabel.textContent = 'Add customer';
        form.action = addUrl;
        form.reset();
    });

    document.querySelectorAll('.customer-edit').forEach(function (el) {
        el.addEventListener('click', function (e) {
            e.preventDefault();
            var id = this.getAttribute('data-id');
            modalLabel.textContent = 'Edit customer';
            form.action = '<?= base_url('sales/customers/update/') ?>' + id;
            document.getElementById('customerType').value = this.getAttribute('data-customer-type') || 'INDIVIDUAL';
            document.getElementById('customerName').value = this.getAttribute('data-name') || '';
            document.getElementById('customerPhone').value = this.getAttribute('data-phone') || '';
            document.getElementById('customerEmail').value = this.getAttribute('data-email') || '';
            document.getElementById('customerAddress1').value = this.getAttribute('data-address-line1') || '';
            document.getElementById('customerAddress2').value = this.getAttribute('data-address-line2') || '';
            document.getElementById('customerCity').value = this.getAttribute('data-city') || '';
            document.getElementById('customerState').value = this.getAttribute('data-state') || '';
            document.getElementById('customerPostalCode').value = this.getAttribute('data-postal-code') || '';
            document.getElementById('customerTaxNumber').value = this.getAttribute('data-tax-number') || '';
            document.getElementById('customerNotes').value = this.getAttribute('data-notes') || '';
            new bootstrap.Modal(modal).show();
        });
    });
})();
</script>
