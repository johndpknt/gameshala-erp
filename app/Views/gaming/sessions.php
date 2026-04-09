<?php
helper('form');
$ongoing             = $ongoing ?? [];
$finished            = $finished ?? [];
$finishedTotal       = (int) ($finishedTotal ?? 0);
$finishedPage        = max(1, (int) ($finishedPage ?? 1));
$finishedPerPage     = (int) ($finishedPerPage ?? 10);
$finishedTotalPages  = max(1, (int) ($finishedTotalPages ?? 1));
$foodByVisit         = $foodByVisit ?? [];
$foodItems           = $foodItems ?? [];
$priceRules          = $priceRules ?? [];
$priceTypeLabels     = $priceTypeLabels ?? \App\Models\GamingPriceRuleModel::priceTypeLabels();
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
                        <th>Date</th>
                        <th>Start Time</th>
                        <th>End Time</th>
                        <th>Total Time</th>
                        <th>Gaming</th>
                        <th>Food</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($finished as $v): ?>
                        <?php
                            $startTs = ! empty($v['start_time']) ? strtotime($v['start_time']) : false;
                            $endTs = ! empty($v['end_time']) ? strtotime($v['end_time']) : false;
                            $dateTs = $startTs ?: $endTs;
                            $durationLabel = '—';
                            if ($startTs !== false && $endTs !== false && $endTs >= $startTs) {
                                $durationSeconds = $endTs - $startTs;
                                $hours = intdiv($durationSeconds, 3600);
                                $minutes = intdiv($durationSeconds % 3600, 60);
                                $seconds = $durationSeconds % 60;
                                $durationLabel = sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);
                            }
                        ?>
                        <tr>
                            <td><?= (int) $v['id'] ?></td>
                            <td><?= esc($v['customer_name'] ?? '—') ?></td>
                            <td><?= esc($v['category_name'] ?? '—') ?> / <?= esc($v['mode_name'] ?? '—') ?></td>
                            <td><?= $dateTs ? date('d M Y', $dateTs) : '—' ?></td>
                            <td><?= $startTs ? date('h:i:s A', $startTs) : '—' ?></td>
                            <td><?= $endTs ? date('h:i:s A', $endTs) : '—' ?></td>
                            <td><?= esc($durationLabel) ?></td>
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
                            <option value="">Select category / mode</option>
                            <?php foreach ($priceRules as $r): ?>
                                <?php
                                $pt = (string) ($r['price_type'] ?? '');
                                $ptLabel = $priceTypeLabels[$pt] ?? $pt;
                                ?>
                                <option value="<?= (int) $r['id'] ?>"><?= esc($r['category_name']) ?> / <?= esc($r['mode_name']) ?> (<?= esc($ptLabel) ?> ₹<?= number_format((float) $r['price'], 2) ?>)</option>
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
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title fs-6" id="addFoodModalLabel"><i class="bi bi-cup-straw me-2"></i>Add food / beverage</h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <?= form_open(base_url('gaming/sessions/add-food'), ['id' => 'addFoodForm']) ?>
                <?= csrf_field() ?>
                <input type="hidden" name="gaming_visit_id" id="addFoodVisitId" value="">
                <input type="hidden" name="food_beverage_item_id" id="addFoodItemId" value="">
                <input type="hidden" name="product_id" id="addFoodProductId" value="">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="addFoodSearch" class="form-label">Beverage &amp; Food (own)</label>
                        <p class="text-muted small mb-2 mb-md-1">In-house menu from Gaming → Food &amp; beverages.</p>
                        <input type="text" class="form-control" id="addFoodSearch" placeholder="Search by name..." autocomplete="off">
                        <div id="addFoodResults" class="list-group mt-1 border rounded" style="max-height: 180px; overflow-y: auto; display: none;"></div>
                        <div id="addFoodSelected" class="mt-2 py-2 px-2 rounded bg-success bg-opacity-10 text-success small" style="display: none;"></div>
                    </div>
                    <div class="mb-3">
                        <label for="addFoodQtyOwn" class="form-label">Quantity (own)</label>
                        <input type="number" class="form-control" id="addFoodQtyOwn" name="quantity_own" value="1" min="1">
                    </div>
                    <div class="mb-2 pt-2 border-top">
                        <label for="addVendorProductSearch" class="form-label">Beverage &amp; Food (from vendor)</label>
                        <p class="text-muted small mb-2 mb-md-1">Catalog <code class="small">FOOD-</code> / <code class="small">BEVE-</code> products (same as <code class="small">api/products?per_page=beve&amp;food=</code>). Stock and price are enforced when you add.</p>
                        <input type="text" class="form-control" id="addVendorProductSearch" placeholder="Search by name or SKU..." autocomplete="off" maxlength="120">
                        <div id="addVendorProductResults" class="list-group mt-1 border rounded" style="max-height: 180px; overflow-y: auto; display: none;"></div>
                        <div id="addVendorProductSelected" class="mt-2 py-2 px-2 rounded bg-success bg-opacity-10 text-success small" style="display: none;"></div>
                    </div>
                    <div class="mb-2">
                        <label for="addFoodQtyVendor" class="form-label">Quantity (from vendor)</label>
                        <input type="number" class="form-control" id="addFoodQtyVendor" name="quantity_vendor" value="1" min="1">
                    </div>
                    <div id="addFoodItemError" class="text-danger small" style="display: none;"></div>
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
    var apiRoot = baseUrl.replace(/\/?$/, '/');

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

    var addFoodModal = document.getElementById('addFoodModal');
    var addFoodForm = document.getElementById('addFoodForm');
    var addFoodSearch = document.getElementById('addFoodSearch');
    var addFoodItemId = document.getElementById('addFoodItemId');
    var addFoodProductId = document.getElementById('addFoodProductId');
    var addFoodResults = document.getElementById('addFoodResults');
    var addFoodSelected = document.getElementById('addFoodSelected');
    var addVendorProductSearch = document.getElementById('addVendorProductSearch');
    var addVendorProductResults = document.getElementById('addVendorProductResults');
    var addVendorProductSelected = document.getElementById('addVendorProductSelected');
    var addFoodItemError = document.getElementById('addFoodItemError');
    var vendorSearchTimeout = null;

    function clearOwnFoodSelection() {
        if (addFoodItemId) addFoodItemId.value = '';
        if (addFoodSelected) addFoodSelected.style.display = 'none';
    }
    function clearVendorFoodSelection() {
        if (addFoodProductId) addFoodProductId.value = '';
        if (addVendorProductSelected) addVendorProductSelected.style.display = 'none';
    }
    function resetAddFoodModalFields() {
        if (addFoodSearch) addFoodSearch.value = '';
        if (addVendorProductSearch) addVendorProductSearch.value = '';
        if (addFoodItemId) addFoodItemId.value = '';
        if (addFoodProductId) addFoodProductId.value = '';
        if (addFoodResults) { addFoodResults.innerHTML = ''; addFoodResults.style.display = 'none'; }
        if (addVendorProductResults) { addVendorProductResults.innerHTML = ''; addVendorProductResults.style.display = 'none'; }
        if (addFoodSelected) addFoodSelected.style.display = 'none';
        if (addVendorProductSelected) addVendorProductSelected.style.display = 'none';
        if (addFoodItemError) addFoodItemError.style.display = 'none';
        var qo = document.getElementById('addFoodQtyOwn');
        var qv = document.getElementById('addFoodQtyVendor');
        if (qo) qo.value = '1';
        if (qv) qv.value = '1';
    }

    document.querySelectorAll('.add-food-btn').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var visitId = this.getAttribute('data-visit-id');
            document.getElementById('addFoodVisitId').value = visitId;
            document.getElementById('addFoodModalLabel').textContent = 'Add food / beverage — Session #' + visitId;
            resetAddFoodModalFields();
            new bootstrap.Modal(addFoodModal).show();
        });
    });

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
                        if (addFoodItemError) addFoodItemError.style.display = 'none';
                    });
                    addFoodResults.appendChild(a);
                });
            }
            addFoodResults.style.display = 'block';
        }
        function refreshFoodResults() {
            if (addFoodItemError) addFoodItemError.style.display = 'none';
            var q = (addFoodSearch.value || '').trim().toLowerCase();
            var matches = q.length < 1
                ? foodItemsData.slice()
                : foodItemsData.filter(function (f) { return (f.name || '').toLowerCase().indexOf(q) !== -1; });
            renderFoodResults(matches);
        }
        addFoodSearch.addEventListener('input', function () {
            clearOwnFoodSelection();
            refreshFoodResults();
        });
        addFoodSearch.addEventListener('focus', function () {
            refreshFoodResults();
        });
        addFoodSearch.addEventListener('blur', function () {
            setTimeout(function () { addFoodResults.style.display = 'none'; }, 200);
        });
    }

    if (addVendorProductSearch && addVendorProductResults) {
        function fetchVendorProducts(q) {
            var params = new URLSearchParams();
            params.set('per_page', 'beve');
            params.set('food', '');
            params.set('is_active', '1');
            if ((q || '').trim() !== '') {
                params.set('q', (q || '').trim());
            }
            return fetch(apiRoot + 'api/products?' + params.toString(), {
                credentials: 'same-origin',
                headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
            }).then(function (r) {
                if (!r.ok) {
                    return Promise.reject(new Error('HTTP ' + r.status));
                }
                return r.json();
            });
        }
        function renderVendorResults(products) {
            addVendorProductResults.innerHTML = '';
            if (!products || products.length === 0) {
                var empty = document.createElement('div');
                empty.className = 'list-group-item text-muted small';
                empty.textContent = 'No matching FOOD-/BEVE- items. (Stock is checked when you add.)';
                addVendorProductResults.appendChild(empty);
            } else {
                products.forEach(function (p) {
                    var price = p.selling_price;
                    var priceLabel = price !== null && price !== undefined && !isNaN(Number(price))
                        ? '₹' + Number(price).toFixed(2)
                        : 'No price';
                    var label = (p.name || '') + ' — ' + (p.sku || '') + ' — ' + priceLabel + (p.unit ? ' / ' + p.unit : '');
                    var a = document.createElement('a');
                    a.href = '#';
                    a.className = 'list-group-item list-group-item-action' + (price === null || price === undefined ? ' text-muted' : '');
                    a.textContent = label;
                    a.addEventListener('click', function (e) {
                        e.preventDefault();
                        if (price === null || price === undefined || isNaN(Number(price))) {
                            if (addFoodItemError) {
                                addFoodItemError.textContent = 'This product has no selling price. Set batch selling price or procurement rule.';
                                addFoodItemError.style.display = 'block';
                            }
                            return;
                        }
                        addFoodProductId.value = String(p.id);
                        addVendorProductSelected.textContent = 'Selected (vendor): ' + label;
                        addVendorProductSelected.style.display = 'block';
                        addVendorProductSearch.value = p.name || '';
                        addVendorProductResults.style.display = 'none';
                        addVendorProductResults.innerHTML = '';
                        if (addFoodItemError) addFoodItemError.style.display = 'none';
                    });
                    addVendorProductResults.appendChild(a);
                });
            }
            addVendorProductResults.style.display = 'block';
        }
        function runVendorSearch() {
            var q = (addVendorProductSearch.value || '').trim();
            if (q.length > 0 && q.length < 2) {
                addVendorProductResults.innerHTML = '';
                addVendorProductResults.style.display = 'none';
                return;
            }
            fetchVendorProducts(q).then(function (data) {
                var list = (data && data.data) ? data.data : [];
                renderVendorResults(list);
            }).catch(function () {
                addVendorProductResults.innerHTML = '';
                var err = document.createElement('div');
                err.className = 'list-group-item text-danger small';
                err.textContent = 'Could not load catalog. Refresh the page or check you are logged in.';
                addVendorProductResults.appendChild(err);
                addVendorProductResults.style.display = 'block';
            });
        }
        addVendorProductSearch.addEventListener('input', function () {
            clearVendorFoodSelection();
            clearTimeout(vendorSearchTimeout);
            vendorSearchTimeout = setTimeout(runVendorSearch, 300);
        });
        addVendorProductSearch.addEventListener('focus', function () {
            runVendorSearch();
        });
        addVendorProductSearch.addEventListener('blur', function () {
            setTimeout(function () { addVendorProductResults.style.display = 'none'; }, 200);
        });
    }

    if (addFoodForm) {
        addFoodForm.addEventListener('submit', function (e) {
            var hasOwn = addFoodItemId && addFoodItemId.value && addFoodItemId.value !== '';
            var hasVendor = addFoodProductId && addFoodProductId.value && addFoodProductId.value !== '';
            if (!hasOwn && !hasVendor) {
                e.preventDefault();
                if (addFoodItemError) {
                    addFoodItemError.textContent = 'Select at least one: own menu item and/or vendor catalog product.';
                    addFoodItemError.style.display = 'block';
                }
                return false;
            }
        });
    }
})();
</script>
