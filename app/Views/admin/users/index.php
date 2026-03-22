<div class="container py-4 px-3 px-sm-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3 mb-4">
        <h1 class="h4 fw-semibold mb-0">Users</h1>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#userModal" id="btnAddUser">
            Add user
        </button>
    </div>

    <?php
    $userBase = base_url('admin/users');
    $userSortUrl = function ($col) use ($userBase, $searchQ, $sort, $order) {
        $next = ($sort === $col && $order === 'asc') ? 'desc' : 'asc';
        return $userBase . '?' . http_build_query(array_filter(['q' => $searchQ, 'sort' => $col, 'order' => $next]));
    };
    $userArrow = function ($col) use ($sort, $order) {
        if ($sort !== $col) return '';
        return $order === 'asc' ? ' ↑' : ' ↓';
    };
    ?>
    <form method="get" action="<?= base_url('admin/users') ?>" class="mb-4">
        <div class="input-group" style="max-width: 400px;">
            <input type="search" name="q" class="form-control" placeholder="Search by name, email, phone..."
                   value="<?= esc($searchQ) ?>">
            <button type="submit" class="btn btn-outline-secondary">Search</button>
        </div>
    </form>

    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th><a href="<?= $userSortUrl('name') ?>" class="text-dark text-decoration-none">Name<?= $userArrow('name') ?></a></th>
                    <th><a href="<?= $userSortUrl('email') ?>" class="text-dark text-decoration-none">Email<?= $userArrow('email') ?></a></th>
                    <th>Phone</th>
                    <th><a href="<?= $userSortUrl('role') ?>" class="text-dark text-decoration-none">Role<?= $userArrow('role') ?></a></th>
                    <th><a href="<?= $userSortUrl('is_active') ?>" class="text-dark text-decoration-none">Status<?= $userArrow('is_active') ?></a></th>
                    <th><a href="<?= $userSortUrl('last_login_at') ?>" class="text-dark text-decoration-none">Last login<?= $userArrow('last_login_at') ?></a></th>
                    <th class="text-end" style="width: 120px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($users)): ?>
                    <tr>
                        <td colspan="7" class="text-center text-secondary py-4">No users found.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($users as $u): ?>
                        <tr>
                            <td><?= esc($u['name']) ?></td>
                            <td><?= esc($u['email']) ?></td>
                            <td><?= esc($u['phone'] ?? '—') ?></td>
                            <td><span class="badge <?= ($u['role'] ?? '') === 'ADMIN' ? 'bg-danger' : 'bg-secondary' ?>"><?= esc($u['role'] ?? 'STAFF') ?></span></td>
                            <td>
                                <?php $active = isset($u['is_active']) ? (int) $u['is_active'] : 1; ?>
                                <span class="badge <?= $active ? 'bg-success' : 'bg-secondary' ?>">
                                    <?= $active ? 'Active' : 'Inactive' ?>
                                </span>
                            </td>
                            <td class="small"><?= ! empty($u['last_login_at']) ? esc($u['last_login_at']) : '—' ?></td>
                            <td class="text-end">
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        Action
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li>
                                            <a class="dropdown-item user-edit" href="#"
                                               data-id="<?= (int) $u['id'] ?>"
                                               data-name="<?= esc($u['name']) ?>"
                                               data-email="<?= esc($u['email']) ?>"
                                               data-phone="<?= esc($u['phone'] ?? '') ?>"
                                               data-role="<?= esc($u['role'] ?? 'STAFF') ?>">
                                                Edit
                                            </a>
                                        </li>
                                        <?php if ($active): ?>
                                            <li>
                                                <?= form_open(base_url('admin/users/set-status/' . (int) $u['id']), ['class' => 'd-inline']) ?>
                                                    <?= csrf_field() ?>
                                                    <input type="hidden" name="is_active" value="0">
                                                    <button type="submit" class="dropdown-item text-warning">Mark inactive</button>
                                                <?= form_close() ?>
                                            </li>
                                        <?php else: ?>
                                            <li>
                                                <?= form_open(base_url('admin/users/set-status/' . (int) $u['id']), ['class' => 'd-inline']) ?>
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

<!-- Add/Edit User Modal -->
<div class="modal fade" id="userModal" tabindex="-1" aria-labelledby="userModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title fs-6" id="userModalLabel">Add user</h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <?= form_open('', ['id' => 'userForm']) ?>
                <?= csrf_field() ?>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="userName" class="form-label">Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="userName" name="name" required maxlength="150" value="">
                    </div>
                    <div class="mb-3">
                        <label for="userEmail" class="form-label">Email <span class="text-danger">*</span></label>
                        <input type="email" class="form-control" id="userEmail" name="email" required maxlength="191" value="">
                    </div>
                    <div class="mb-3">
                        <label for="userPhone" class="form-label">Phone</label>
                        <input type="text" class="form-control" id="userPhone" name="phone" maxlength="30" value="">
                    </div>
                    <div class="mb-3">
                        <label for="userRole" class="form-label">Role <span class="text-danger">*</span></label>
                        <select class="form-select" id="userRole" name="role" required>
                            <option value="STAFF">STAFF</option>
                            <option value="ADMIN">ADMIN</option>
                        </select>
                    </div>
                    <div class="mb-3" id="userPasswordWrap">
                        <label for="userPassword" class="form-label"><span id="userPasswordLabel">Password</span> <span class="text-danger" id="userPasswordReq">*</span></label>
                        <input type="password" class="form-control" id="userPassword" name="password" maxlength="72" autocomplete="new-password">
                        <div class="form-text" id="userPasswordHint">Leave blank to keep current password (edit mode)</div>
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
    var form = document.getElementById('userForm');
    var modal = document.getElementById('userModal');
    var modalLabel = document.getElementById('userModalLabel');
    var passwordInput = document.getElementById('userPassword');
    var passwordLabel = document.getElementById('userPasswordLabel');
    var passwordReq = document.getElementById('userPasswordReq');
    var passwordHint = document.getElementById('userPasswordHint');
    var addUrl = '<?= base_url('admin/users') ?>';

    document.getElementById('btnAddUser').addEventListener('click', function () {
        modalLabel.textContent = 'Add user';
        form.action = addUrl;
        form.reset();
        passwordReq.style.display = 'inline';
        passwordInput.required = true;
        passwordHint.style.display = 'none';
    });

    document.querySelectorAll('.user-edit').forEach(function (el) {
        el.addEventListener('click', function (e) {
            e.preventDefault();
            var id = this.getAttribute('data-id');
            modalLabel.textContent = 'Edit user';
            form.action = '<?= base_url('admin/users/update/') ?>' + id;
            document.getElementById('userName').value = this.getAttribute('data-name') || '';
            document.getElementById('userEmail').value = this.getAttribute('data-email') || '';
            document.getElementById('userPhone').value = this.getAttribute('data-phone') || '';
            document.getElementById('userRole').value = this.getAttribute('data-role') || 'STAFF';
            passwordInput.value = '';
            passwordInput.required = false;
            passwordReq.style.display = 'none';
            passwordHint.style.display = 'block';
            new bootstrap.Modal(modal).show();
        });
    });
})();
</script>
