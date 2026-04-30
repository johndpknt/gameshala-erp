<?php
$isEdit = $batch !== null;
$formAction = $isEdit ? base_url('inventory/stock-batches/update/' . (int) $batch['id']) : base_url('inventory/stock-batches/create');
$productId = old('product_id') ?? ($batch['product_id'] ?? '');
$vendorId  = old('vendor_id') ?? ($batch['vendor_id'] ?? '');
$ruleId    = old('procurement_rule_id') ?? ($batch['current_procurement_rule_id'] ?? '');
$receivedAt = old('received_at') ?? ($batch['received_at'] ?? '');
if ($receivedAt && strlen($receivedAt) >= 16) {
    $receivedAt = date('Y-m-d\TH:i', strtotime($receivedAt));
} elseif ($receivedAt && strlen($receivedAt) === 10) {
    $receivedAt .= 'T00:00';
}
?>
<div class="container py-4 px-3 px-sm-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3 mb-4">
        <h1 class="h4 fw-semibold mb-0"><?= $isEdit ? 'Edit Stock Batch' : 'Add Stock Batch' ?></h1>
        <a href="<?= base_url('inventory/stock-batches') ?>" class="btn btn-outline-secondary">Back to list</a>
    </div>

    <?= form_open($formAction, ['method' => 'post', 'class' => 'needs-validation', 'id' => 'batchForm']) ?>
        <?= csrf_field() ?>
        <div class="row">
            <div class="col-lg-8">
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="batch_code" class="form-label">Batch code <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="batch_code" name="batch_code" required maxlength="100"
                                   value="<?= esc(old('batch_code', $batch['batch_code'] ?? '')) ?>" placeholder="e.g. BATCH-2024-001">
                        </div>

                        <?php
// Always show product filter (matches prod UX); vendor filter when list is large.
$showVendorSearch = count($vendors) > 10;
?>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="product_id" class="form-label">Product <span class="text-danger">*</span></label>
                                <input type="text" class="form-control mb-1" id="product_search" placeholder="Type to search products..." autocomplete="off" aria-label="Filter products">
                                <select class="form-select select-searchable" id="product_id" name="product_id" required data-search-input="product_search">
                                    <option value="">— Select product —</option>
                                    <?php foreach ($products as $p): ?>
                                        <option value="<?= (int) $p['id'] ?>" data-sku="<?= esc($p['sku'] ?? '') ?>" data-search="<?= esc($p['name'] . ' ' . ($p['sku'] ?? '')) ?>"
                                            <?= (string) $productId === (string) $p['id'] ? ' selected' : '' ?>>
                                            <?= esc($p['name']) ?> (<?= esc($p['sku'] ?? '') ?>)
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="vendor_id" class="form-label">Vendor <span class="text-danger">*</span></label>
                                <?php if ($showVendorSearch): ?>
                                <input type="text" class="form-control mb-1" id="vendor_search" placeholder="Type to search vendors..." autocomplete="off" aria-label="Filter vendors">
                                <?php endif; ?>
                                <select class="form-select select-searchable" id="vendor_id" name="vendor_id" required data-search-input="vendor_search">
                                    <option value="">— Select vendor —</option>
                                    <?php foreach ($vendors as $v): ?>
                                        <option value="<?= (int) $v['id'] ?>" data-search="<?= esc($v['name']) ?>"
                                            <?= (string) $vendorId === (string) $v['id'] ? ' selected' : '' ?>>
                                            <?= esc($v['name']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="procurement_rule_id" class="form-label">Procurement rule</label>
                            <div class="form-text mb-1">Optional for products with SKU starting with <code>BEVE-</code> or <code>FOOD-</code> (set <strong>Unit cost</strong> and <strong>Selling price</strong> on this batch). Select <strong>Own rule</strong> when you want to enter selling price manually.</div>
                            <select class="form-select" id="procurement_rule_id" name="procurement_rule_id">
                                <option value="">— No rule —</option>
                                <option value="own" <?= (string) $ruleId === 'own' ? ' selected' : '' ?>>Own rule (manual selling price)</option>
                                <?php foreach ($rules as $r): ?>
                                    <option value="<?= (int) $r['id'] ?>" <?= (string) $ruleId === (string) $r['id'] ? ' selected' : '' ?>>
                                        <?= esc($r['name']) ?> (<?= esc($r['discount_type'] ?? 'FLAT') ?>: <?= esc($r['discount_value'] ?? 0) ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <label for="purchased_qty" class="form-label">Purchased qty <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" id="purchased_qty" name="purchased_qty" required min="0" step="1"
                                       value="<?= esc(old('purchased_qty', $batch['purchased_qty'] ?? '0')) ?>">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="remaining_qty" class="form-label">Remaining qty <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" id="remaining_qty" name="remaining_qty" required min="0" step="1"
                                       value="<?= esc(old('remaining_qty', $batch['remaining_qty'] ?? '0')) ?>">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="unit_cost" class="form-label">Unit cost <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" id="unit_cost" name="unit_cost" required min="0" step="0.01"
                                       value="<?= esc(old('unit_cost', $batch['unit_cost'] ?? '0')) ?>">
                            </div>
                        </div>
                        <?php
                        $spVal = old('selling_price', $isEdit && isset($batch['selling_price']) && $batch['selling_price'] !== null && $batch['selling_price'] !== ''
                            ? $batch['selling_price']
                            : '');
                        $ownRuleDiscountVal = old('own_rule_discount', $isEdit && isset($batch['own_rule_discount']) && $batch['own_rule_discount'] !== null && $batch['own_rule_discount'] !== ''
                            ? $batch['own_rule_discount']
                            : '');
                        ?>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="selling_price" class="form-label">Selling price</label>
                                <input type="number" class="form-control" id="selling_price" name="selling_price" min="0" step="0.01"
                                       value="<?= esc($spVal) ?>" placeholder="BEVE- / FOOD- only">
                                <div class="form-text" id="selling_price_hint">Enabled when product SKU starts with <code>BEVE-</code> or <code>FOOD-</code>, or when <strong>Own rule</strong> is selected.</div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="own_rule_discount" class="form-label">Own rule discount</label>
                                <input type="number" class="form-control" id="own_rule_discount" name="own_rule_discount" min="0" step="0.01"
                                       value="<?= esc($ownRuleDiscountVal) ?>" placeholder="Per-unit discount">
                                <div class="form-text" id="own_rule_discount_hint">Required for <strong>Own rule</strong>. Used to show discount on order page.</div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="received_at" class="form-label">Received at <span class="text-danger">*</span></label>
                            <input type="datetime-local" class="form-control" id="received_at" name="received_at" required
                                   value="<?= esc($receivedAt) ?>">
                        </div>

                        <div class="mb-3">
                            <label for="remarks" class="form-label">Remarks</label>
                            <textarea class="form-control" id="remarks" name="remarks" rows="2" maxlength="500" placeholder="Optional"><?= esc(old('remarks', $batch['remarks'] ?? '')) ?></textarea>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary"><?= $isEdit ? 'Update batch' : 'Add batch' ?></button>
                        <a href="<?= base_url('inventory/stock-batches') ?>" class="btn btn-secondary">Cancel</a>
                    </div>
                </div>
            </div>
        </div>
    <?= form_close() ?>
</div>

<script>
(function () {
    // Searchable select: filter options by data-search as user types (when search input exists)
    document.querySelectorAll('.select-searchable').forEach(function (select) {
        var inputId = select.getAttribute('data-search-input');
        var input = inputId ? document.getElementById(inputId) : null;
        if (!input) return;

        function filterOptions() {
            var term = (input.value || '').toLowerCase();
            var options = select.querySelectorAll('option');
            options.forEach(function (opt) {
                if (opt.value === '') {
                    opt.style.display = '';
                    return;
                }
                var search = (opt.getAttribute('data-search') || opt.textContent || '').toLowerCase();
                opt.style.display = term !== '' && search.indexOf(term) === -1 ? 'none' : '';
            });
        }

        input.addEventListener('input', filterOptions);
        input.addEventListener('keyup', filterOptions);
        select.addEventListener('change', function () {
            var opt = select.options[select.selectedIndex];
            input.value = opt && opt.value ? (opt.getAttribute('data-search') || opt.textContent).trim() : '';
        });
        if (select.value) {
            var selected = select.options[select.selectedIndex];
            if (selected) input.value = (selected.getAttribute('data-search') || selected.textContent || '').trim();
        }
    });

    function skuAllowsSellingPrice(sku) {
        if (!sku) return false;
        var u = String(sku).toUpperCase();
        return u.indexOf('BEVE-') === 0 || u.indexOf('FOOD-') === 0;
    }
    function ownRuleSelected() {
        var ruleSel = document.getElementById('procurement_rule_id');
        return !!ruleSel && String(ruleSel.value || '').toLowerCase() === 'own';
    }
    function updateSellingPriceFieldState() {
        var sel = document.getElementById('product_id');
        var input = document.getElementById('selling_price');
        var ownDiscountInput = document.getElementById('own_rule_discount');
        if (!sel || !input) return;
        var opt = sel.options[sel.selectedIndex];
        var sku = opt && opt.getAttribute('data-sku') ? opt.getAttribute('data-sku') : '';
        var isOwn = ownRuleSelected();
        var allow = skuAllowsSellingPrice(sku) || isOwn;
        input.disabled = !allow;
        if (!allow) {
            input.value = '';
            input.removeAttribute('required');
        } else {
            input.setAttribute('required', 'required');
        }
        if (ownDiscountInput) {
            ownDiscountInput.disabled = !isOwn;
            if (!isOwn) {
                ownDiscountInput.value = '';
                ownDiscountInput.removeAttribute('required');
            } else {
                ownDiscountInput.setAttribute('required', 'required');
            }
        }
    }
    var productSel = document.getElementById('product_id');
    if (productSel) {
        productSel.addEventListener('change', updateSellingPriceFieldState);
    }
    var ruleSel = document.getElementById('procurement_rule_id');
    if (ruleSel) {
        ruleSel.addEventListener('change', updateSellingPriceFieldState);
    }
    updateSellingPriceFieldState();
})();
</script>
