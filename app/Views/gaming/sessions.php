<?php
helper(['form', 'gaming']);
$ongoing             = $ongoing ?? [];
$finished            = $finished ?? [];
$finishedTotal       = (int) ($finishedTotal ?? 0);
$finishedPage        = max(1, (int) ($finishedPage ?? 1));
$finishedPerPage     = (int) ($finishedPerPage ?? 10);
$finishedTotalPages  = max(1, (int) ($finishedTotalPages ?? 1));
$foodByVisit         = $foodByVisit ?? [];
$foodItems           = $foodItems ?? [];
$priceRules          = $priceRules ?? [];
$sessionsBaseUrl     = base_url('gaming/sessions');
?>
<div class="container py-4 px-3 px-sm-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3 mb-4">
        <div>
            <h1 class="h4 fw-semibold mb-1">Sessions</h1>
            <p class="text-secondary small mb-0">Start a session when a customer begins playing. Add food, end session, then generate invoice when paid. Invoices appear in Sales → Invoices.</p>
        </div>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#startSessionModal"><i class="bi bi-play-circle-fill me-2"></i>Start session</button>
    </div>

    <h2 class="h5 fw-semibold mb-3"><i class="bi bi-hourglass-split text-primary me-2"></i>Ongoing sessions</h2>
    <?php if (empty($ongoing)): ?>
        <p class="text-secondary">No ongoing sessions. Click <strong>Start session</strong> above when a customer begins playing.</p>
    <?php else: ?>
        <div class="row g-3 mb-5">
            <?php foreach ($ongoing as $v): ?>
                <?php
                $foodRows = $foodByVisit[$v['id']] ?? [];
                $foodTotal = array_sum(array_column($foodRows, 'line_total'));
                ?>
                <div class="col-12 col-lg-6">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div>
                                    <h3 class="h6 mb-0">Session #<?= (int) $v['id'] ?></h3>
                                    <span class="badge bg-primary mt-1">Ongoing</span>
                                </div>
                                <div class="text-end">
                                    <div class="text-muted small text-uppercase"><i class="bi bi-clock me-1"></i>Duration</div>
                                    <div class="session-timer display-6 fw-bold text-primary lh-1" style="font-variant-numeric: tabular-nums;" data-start="<?= $v['start_time'] ? date('c', strtotime($v['start_time'])) : '' ?>">—</div>
                                </div>
                            </div>
                            <p class="mb-1"><strong>Customer:</strong> <?= esc($v['customer_name'] ?? '—') ?> <?php if (! empty($v['customer_phone'])): ?>(<?= esc($v['customer_phone']) ?>)<?php endif; ?></p>
                            <p class="mb-1"><strong>Game:</strong> <?= esc($v['category_name'] ?? '—') ?> / <?= esc($v['mode_name'] ?? '—') ?></p>
                            <p class="mb-1"><strong>Started:</strong> <?= $v['start_time'] ? date('d M Y H:i', strtotime($v['start_time'])) : '—' ?></p>
                            <p class="mb-2"><strong>Players:</strong> <?= (int) ($v['no_of_players'] ?? 1) ?></p>
                            <?php if (! empty($foodRows)): ?>
                                <p class="mb-1 small"><strong>Food & beverage:</strong></p>
                                <ul class="list-unstyled small mb-2">
                                    <?php foreach ($foodRows as $f): ?>
                                        <li><?= esc($f['item_name'] ?? 'Item') ?> × <?= (int) $f['quantity'] ?> = ₹<?= number_format((float) $f['line_total'], 2) ?></li>
                                    <?php endforeach; ?>
                                </ul>
                                <p class="mb-2"><strong>Food total:</strong> ₹<?= number_format($foodTotal, 2) ?></p>
                            <?php endif; ?>
                            <div class="d-flex flex-wrap gap-2">
                                <button type="button" class="btn btn-sm btn-outline-primary add-food-btn" data-visit-id="<?= (int) $v['id'] ?>"><i class="bi bi-cup-straw me-1"></i>Add food</button>
                                <?= form_open(base_url('gaming/sessions/end/' . (int) $v['id']), ['class' => 'd-inline']) ?>
                                    <?= csrf_field() ?>
                                    <button type="submit" class="btn btn-sm btn-warning"><i class="bi bi-stop-circle me-1"></i>End session</button>
                                <?= form_close() ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <h2 class="h5 fw-semibold mb-3"><i class="bi bi-check2-square text-success me-2"></i>Ended sessions</h2>
    <?php if ($finishedTotal === 0): ?>
        <p class="text-secondary">No ended sessions yet.</p>
    <?php else: ?>
        <p class="text-secondary small mb-2">Showing <?= count($finished) ?> of <?= $finishedTotal ?> ended sessions.</p>
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Customer</th>
                        <th>Game</th>
                        <th>Started</th>
                        <th>Ended</th>
                        <th>Gaming</th>
                        <th>Food</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($finished as $v): ?>
                        <tr>
                            <td><?= (int) $v['id'] ?></td>
                            <td><?= esc($v['customer_name'] ?? '—') ?></td>
                            <td><?= esc($v['category_name'] ?? '—') ?> / <?= esc($v['mode_name'] ?? '—') ?></td>
                            <td><?= $v['start_time'] ? date('d M H:i', strtotime($v['start_time'])) : '—' ?></td>
                            <td><?= $v['end_time'] ? date('d M H:i', strtotime($v['end_time'])) : '—' ?></td>
                            <td>₹<?= number_format((float) ($v['gaming_amount'] ?? 0), 2) ?></td>
                            <td>₹<?= number_format((float) ($v['food_amount'] ?? 0), 2) ?></td>
                            <td><strong>₹<?= number_format((float) ($v['total_amount'] ?? 0), 2) ?></strong></td>
                            <td>
                                <?php if (! empty($v['invoice_id'])): ?>
                                    <span class="badge bg-success">Paid</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary">Unpaid</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-end">
                                <?php if (empty($v['invoice_id'])): ?>
                                    <?= form_open(base_url('gaming/sessions/generate-invoice/' . (int) $v['id']), ['class' => 'd-inline']) ?>
                                        <?= csrf_field() ?>
                                        <button type="submit" class="btn btn-sm btn-success"><i class="bi bi-currency-rupee me-1"></i>Mark paid</button>
                                    <?= form_close() ?>
                                <?php else: ?>
                                    <a href="<?= base_url('sales/invoices/view/' . (int) $v['invoice_id']) ?>" class="btn btn-sm btn-outline-primary" target="_blank" rel="noopener"><i class="bi bi-receipt me-1"></i>View invoice</a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php if ($finishedTotalPages > 1): ?>
            <nav aria-label="Ended sessions pagination" class="mt-3">
                <ul class="pagination pagination-sm justify-content-center flex-wrap">
                    <li class="page-item <?= $finishedPage <= 1 ? 'disabled' : '' ?>">
                        <a class="page-link" href="<?= $finishedPage <= 1 ? '#' : ($finishedPage === 2 ? $sessionsBaseUrl : $sessionsBaseUrl . '?page=' . ($finishedPage - 1)) ?>" aria-label="Previous">Previous</a>
                    </li>
                    <?php for ($p = 1; $p <= $finishedTotalPages; $p++): ?>
                        <li class="page-item <?= $p === $finishedPage ? 'active' : '' ?>">
                            <a class="page-link" href="<?= $p === 1 ? $sessionsBaseUrl : $sessionsBaseUrl . '?page=' . $p ?>"><?= $p ?></a>
                        </li>
                    <?php endfor; ?>
                    <li class="page-item <?= $finishedPage >= $finishedTotalPages ? 'disabled' : '' ?>">
                        <a class="page-link" href="<?= $finishedPage >= $finishedTotalPages ? '#' : $sessionsBaseUrl . '?page=' . ($finishedPage + 1) ?>" aria-label="Next">Next</a>
                    </li>
                </ul>
            </nav>
        <?php endif; ?>
    <?php endif; ?>
</div>

<!-- Start Session Modal -->
<div class="modal fade" id="startSessionModal" tabindex="-1" aria-labelledby="startSessionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title fs-6" id="startSessionModalLabel"><i class="bi bi-play-circle me-2"></i>Start session</h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <?= form_open(base_url('gaming/sessions/start'), ['id' => 'startSessionForm']) ?>
                <?= csrf_field() ?>
                <input type="hidden" name="customer_id" id="customerId" value="">
                <div class="modal-body">
                    <div class="mb-4">
                        <label class="form-label fw-semibold">Customer</label>
                        <p class="text-muted small mb-2">Search by name or mobile, or add a new walk-in below.</p>
                        <input type="text" class="form-control" id="customerSearch" placeholder="Type name or mobile to search..." maxlength="100" autocomplete="off">
                        <div id="customerResults" class="list-group mt-1 border rounded" style="max-height: 160px; overflow-y: auto; display: none;"></div>
                        <div id="customerDisplay" class="mt-2 py-2 px-2 rounded bg-success bg-opacity-10 text-success small" style="display: none;"></div>
                        <div id="customerError" class="mt-2 text-danger small" style="display: none;"></div>

                        <div class="mt-3 pt-3 border-top">
                            <button type="button" class="btn btn-link btn-sm p-0 text-decoration-none" id="toggleNewCustomer" aria-expanded="false">
                                <span id="toggleNewCustomerText">+ New customer? Add name &amp; phone</span>
                            </button>
                            <div id="newCustomerFields" class="mt-2" style="display: none;">
                                <div class="row g-2">
                                    <div class="col-6">
                                        <label for="newCustomerName" class="form-label small">Name</label>
                                        <input type="text" class="form-control form-control-sm" id="newCustomerName" name="new_customer_name" placeholder="Full name" maxlength="150">
                                    </div>
                                    <div class="col-6">
                                        <label for="newCustomerPhone" class="form-label small">Mobile</label>
                                        <input type="text" class="form-control form-control-sm" id="newCustomerPhone" name="new_customer_phone" placeholder="10-digit number" maxlength="30">
                                    </div>
                                </div>
                                <p class="small text-muted mt-1 mb-0">Leave search blank and fill these to add a new walk-in. Then click Start session.</p>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="startPriceRule" class="form-label">Game (price rule) <span class="text-danger">*</span></label>
                        <select class="form-select" id="startPriceRule" name="gaming_price_rule_id" required>
                            <option value="">Select consol / gaming package</option>
                            <?php foreach ($priceRules as $r): ?>
                                <option value="<?= (int) $r['id'] ?>"><?= esc($r['category_name']) ?> / <?= esc($r['mode_name']) ?> (<?= esc(gaming_time_duration_label((string) ($r['price_type'] ?? ''))) ?> ₹<?= number_format((float) $r['price'], 2) ?>)</option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="row g-2">
                        <div class="col-6">
                            <label for="noOfPlayers" class="form-label">No. of players</label>
                            <input type="number" class="form-control" id="noOfPlayers" name="no_of_players" value="1" min="1">
                        </div>
                        <div class="col-6">
                            <label for="startTime" class="form-label">Start time <span class="text-danger">*</span></label>
                            <input type="datetime-local" class="form-control" id="startTime" name="start_time" value="<?= date('Y-m-d\TH:i') ?>" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary"><i class="bi bi-play-fill me-2"></i>Start session</button>
                </div>
            <?= form_close() ?>
        </div>
    </div>
</div>

<!-- Add Food Modal -->
<div class="modal fade" id="addFoodModal" tabindex="-1" aria-labelledby="addFoodModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title fs-6" id="addFoodModalLabel"><i class="bi bi-cup-straw me-2"></i>Add food / beverage</h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <?= form_open(base_url('gaming/sessions/add-food')) ?>
                <?= csrf_field() ?>
                <input type="hidden" name="gaming_visit_id" id="addFoodVisitId" value="">
                <input type="hidden" name="food_beverage_item_id" id="addFoodItemId" value="">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="addFoodSearch" class="form-label">Item <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="addFoodSearch" placeholder="Search by name..." autocomplete="off">
                        <div id="addFoodResults" class="list-group mt-1 border rounded" style="max-height: 180px; overflow-y: auto; display: none;"></div>
                        <div id="addFoodSelected" class="mt-2 py-2 px-2 rounded bg-success bg-opacity-10 text-success small" style="display: none;"></div>
                        <div id="addFoodItemError" class="mt-2 text-danger small" style="display: none;"></div>
                    </div>
                    <div class="mb-3">
                        <label for="addFoodQty" class="form-label">Quantity <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" id="addFoodQty" name="quantity" value="1" min="1" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add</button>
                </div>
            <?= form_close() ?>
        </div>
    </div>
</div>

<script>
(function () {
    var baseUrl = '<?= base_url() ?>';
    var csrfName = '<?= csrf_token() ?>';
    var csrfVal = '<?= csrf_hash() ?>';

    function formatDuration(seconds) {
        if (seconds < 0) return '—';
        var h = Math.floor(seconds / 3600);
        var m = Math.floor((seconds % 3600) / 60);
        var s = Math.floor(seconds % 60);
        var parts = [];
        if (h > 0) parts.push(h + 'h');
        if (m > 0) parts.push(m + 'm');
        parts.push(s + 's');
        return parts.join(' ');
    }
    function updateSessionTimers() {
        var now = Date.now() / 1000;
        document.querySelectorAll('.session-timer').forEach(function (el) {
            var start = el.getAttribute('data-start');
            if (!start) { el.textContent = '—'; return; }
            var startSec = new Date(start).getTime() / 1000;
            if (isNaN(startSec)) { el.textContent = '—'; return; }
            el.textContent = formatDuration(now - startSec);
        });
    }
    updateSessionTimers();
    setInterval(updateSessionTimers, 1000);

    var foodItemsData = <?= json_encode(array_map(function ($f) {
        return ['id' => (int) $f['id'], 'name' => $f['name'] ?? '', 'price' => (float) ($f['price'] ?? 0), 'unit_label' => $f['unit_label'] ?? ''];
    }, $foodItems)) ?>;
    var startSessionModal = document.getElementById('startSessionModal');
    var startSessionForm = document.getElementById('startSessionForm');
    var customerIdEl = document.getElementById('customerId');
    var customerSearch = document.getElementById('customerSearch');
    var customerResults = document.getElementById('customerResults');
    var customerDisplay = document.getElementById('customerDisplay');
    var customerError = document.getElementById('customerError');
    var searchTimeout = null;

    function showCustomerFound(name, phone, id) {
        customerIdEl.value = id;
        customerDisplay.textContent = 'Customer: ' + name + (phone ? ' (' + phone + ')' : '');
        customerDisplay.style.display = 'block';
        customerError.style.display = 'none';
        customerResults.style.display = 'none';
        customerResults.innerHTML = '';
    }
    function showCustomerError(msg) {
        customerIdEl.value = '';
        customerDisplay.style.display = 'none';
        customerError.textContent = msg;
        customerError.style.display = 'block';
    }
    function clearCustomerMsg() {
        customerDisplay.style.display = 'none';
        customerError.style.display = 'none';
    }
    function resetStartSessionForm() {
        customerIdEl.value = '';
        customerSearch.value = '';
        customerResults.style.display = 'none';
        customerResults.innerHTML = '';
        clearCustomerMsg();
        var newFields = document.getElementById('newCustomerFields');
        var newName = document.getElementById('newCustomerName');
        var newPhone = document.getElementById('newCustomerPhone');
        var toggleText = document.getElementById('toggleNewCustomerText');
        if (newFields) { newFields.style.display = 'none'; }
        if (newName) newName.value = '';
        if (newPhone) newPhone.value = '';
        if (toggleText) toggleText.textContent = '+ New customer? Add name & phone';
        if (startSessionForm) startSessionForm.reset();
        document.getElementById('noOfPlayers').value = '1';
        document.getElementById('startTime').value = '<?= date('Y-m-d\TH:i') ?>';
    }

    if (startSessionModal) {
        startSessionModal.addEventListener('show.bs.modal', function () { resetStartSessionForm(); });
    }

    var toggleBtn = document.getElementById('toggleNewCustomer');
    var newCustomerFields = document.getElementById('newCustomerFields');
    var toggleText = document.getElementById('toggleNewCustomerText');
    if (toggleBtn && newCustomerFields) {
        toggleBtn.addEventListener('click', function () {
            var isShown = newCustomerFields.style.display !== 'none';
            newCustomerFields.style.display = isShown ? 'none' : 'block';
            toggleText.textContent = isShown ? '+ New customer? Add name & phone' : '− Hide new customer';
            toggleBtn.setAttribute('aria-expanded', isShown ? 'false' : 'true');
            if (!isShown) {
                customerIdEl.value = '';
                customerDisplay.style.display = 'none';
                customerSearch.value = '';
                customerResults.style.display = 'none';
            }
        });
    }

    if (customerSearch) {
        customerSearch.addEventListener('input', function () {
            var q = (this.value || '').trim();
            if (q.length < 2) {
                customerResults.style.display = 'none';
                customerResults.innerHTML = '';
                return;
            }
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(function () {
                fetch(baseUrl + 'gaming/sessions/api/customer-search?q=' + encodeURIComponent(q))
                    .then(function (r) { return r.json(); })
                    .then(function (data) {
                        customerResults.innerHTML = '';
                        if (data.customers && data.customers.length) {
                            data.customers.forEach(function (c) {
                                var a = document.createElement('a');
                                a.href = '#';
                                a.className = 'list-group-item list-group-item-action';
                                a.textContent = (c.name || '') + (c.phone ? ' — ' + c.phone : '');
                                a.dataset.id = c.id;
                                a.addEventListener('click', function (e) {
                                    e.preventDefault();
                                    showCustomerFound(c.name || '', c.phone || '', c.id);
                                    customerSearch.value = (c.name || '') + (c.phone ? ' — ' + c.phone : '');
                                    newCustomerFields.style.display = 'none';
                                    document.getElementById('newCustomerName').value = '';
                                    document.getElementById('newCustomerPhone').value = '';
                                    if (toggleText) toggleText.textContent = '+ New customer? Add name & phone';
                                });
                                customerResults.appendChild(a);
                            });
                            customerResults.style.display = 'block';
                        } else {
                            customerResults.style.display = 'none';
                        }
                    });
            }, 300);
        });
        customerSearch.addEventListener('blur', function () {
            setTimeout(function () { customerResults.style.display = 'none'; }, 200);
        });
    }

    document.querySelectorAll('.add-food-btn').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var visitId = this.getAttribute('data-visit-id');
            document.getElementById('addFoodVisitId').value = visitId;
            document.getElementById('addFoodModalLabel').textContent = 'Add food / beverage — Session #' + visitId;
            var searchEl = document.getElementById('addFoodSearch');
            var idEl = document.getElementById('addFoodItemId');
            var resultsEl = document.getElementById('addFoodResults');
            var selectedEl = document.getElementById('addFoodSelected');
            var errEl = document.getElementById('addFoodItemError');
            if (searchEl) searchEl.value = '';
            if (idEl) idEl.value = '';
            if (resultsEl) { resultsEl.innerHTML = ''; resultsEl.style.display = 'none'; }
            if (selectedEl) selectedEl.style.display = 'none';
            if (errEl) { errEl.style.display = 'none'; }
            document.getElementById('addFoodQty').value = '1';
            new bootstrap.Modal(document.getElementById('addFoodModal')).show();
        });
    });

    var addFoodSearch = document.getElementById('addFoodSearch');
    var addFoodItemId = document.getElementById('addFoodItemId');
    var addFoodResults = document.getElementById('addFoodResults');
    var addFoodSelected = document.getElementById('addFoodSelected');
    var addFoodItemError = document.getElementById('addFoodItemError');
    var addFoodForm = document.getElementById('addFoodModal') ? document.getElementById('addFoodModal').querySelector('form') : null;

    if (addFoodSearch && addFoodResults) {
        function renderFoodResults(matches) {
            addFoodResults.innerHTML = '';
            if (matches.length === 0) {
                var empty = document.createElement('div');
                empty.className = 'list-group-item text-muted small';
                empty.textContent = 'No items match.';
                addFoodResults.appendChild(empty);
            } else {
                matches.forEach(function (f) {
                    var label = f.name + ' — ₹' + (f.price.toFixed(2)) + (f.unit_label ? ' / ' + f.unit_label : '');
                    var a = document.createElement('a');
                    a.href = '#';
                    a.className = 'list-group-item list-group-item-action';
                    a.textContent = label;
                    a.addEventListener('click', function (e) {
                        e.preventDefault();
                        addFoodItemId.value = f.id;
                        addFoodSelected.textContent = 'Selected: ' + label;
                        addFoodSelected.style.display = 'block';
                        addFoodSearch.value = f.name;
                        addFoodResults.style.display = 'none';
                        addFoodResults.innerHTML = '';
                        addFoodItemError.style.display = 'none';
                    });
                    addFoodResults.appendChild(a);
                });
            }
            addFoodResults.style.display = 'block';
        }
        function refreshFoodResults() {
            if (addFoodItemId.value) return;
            addFoodItemId.value = '';
            addFoodSelected.style.display = 'none';
            addFoodItemError.style.display = 'none';
            var q = (addFoodSearch.value || '').trim().toLowerCase();
            var matches = q.length < 1
                ? foodItemsData.slice()
                : foodItemsData.filter(function (f) { return (f.name || '').toLowerCase().indexOf(q) !== -1; });
            renderFoodResults(matches);
        }
        addFoodSearch.addEventListener('input', refreshFoodResults);
        addFoodSearch.addEventListener('focus', function () {
            if (addFoodItemId.value) return;
            refreshFoodResults();
        });
        addFoodSearch.addEventListener('blur', function () {
            setTimeout(function () { addFoodResults.style.display = 'none'; }, 200);
        });
    }

    if (addFoodForm) {
        addFoodForm.addEventListener('submit', function (e) {
            if (!addFoodItemId.value || addFoodItemId.value === '') {
                e.preventDefault();
                addFoodItemError.textContent = 'Please search and select an item.';
                addFoodItemError.style.display = 'block';
                return false;
            }
        });
    }
})();
</script>
