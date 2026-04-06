<div class="container py-4 px-3 px-sm-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3 mb-4">
        <h1 class="h4 fw-semibold mb-0">Create order</h1>
        <a href="<?= base_url('sales/orders') ?>" class="btn btn-outline-secondary">Back to orders</a>
    </div>

    <?= form_open(base_url('sales/orders/store'), ['id' => 'orderForm']) ?>
        <?= csrf_field() ?>
        <input type="hidden" name="customer_id" id="customerId" value="">

        <div class="card mb-4">
            <div class="card-header fw-semibold">Customer</div>
            <div class="card-body">
                <div class="row g-2 align-items-end">
                    <div class="col-md-4">
                        <label for="customerPhoneSearch" class="form-label">Phone number</label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="customerPhoneSearch" placeholder="Enter phone to look up" maxlength="30" autocomplete="off">
                            <button type="button" class="btn btn-outline-secondary" id="btnLookupCustomer">Look up</button>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label d-block">&nbsp;</label>
                        <button type="button" class="btn btn-outline-primary" id="btnQuickAddCustomer">Quick add customer</button>
                    </div>
                </div>
                <div id="customerDisplay" class="mt-2 text-success small" style="display: none;"></div>
                <div id="customerError" class="mt-2 text-danger small" style="display: none;"></div>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header fw-semibold">Products</div>
            <div class="card-body">
                <label for="productSearch" class="form-label">Search product (name or SKU)</label>
                <div class="position-relative">
                    <input type="text" class="form-control mb-3" id="productSearch" placeholder="Type to search..." autocomplete="off">
                    <div id="productSearchResults" class="list-group position-absolute shadow" style="display: none; z-index: 1000; max-height: 220px; overflow-y: auto; left: 0; right: 0;"></div>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered align-middle" id="orderItemsTable">
                        <thead class="table-light">
                            <tr>
                                <th>Product</th>
                                <th>Batch / Rule</th>
                                <th class="text-center" style="width: 100px;">Qty</th>
                                <th class="text-end" style="width: 110px;">Listing price</th>
                                <th class="text-end" style="width: 95px;">Discount</th>
                                <th class="text-end" style="width: 110px;">Selling price</th>
                                <th class="text-end" style="width: 110px;">Line total</th>
                                <th style="width: 60px;"></th>
                            </tr>
                        </thead>
                        <tbody id="orderItemsBody">
                            <tr id="orderItemsEmpty">
                                <td colspan="8" class="text-center text-secondary py-4">Add products using the search above.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header fw-semibold">Coupon</div>
            <div class="card-body">
                <div class="row g-2 align-items-end">
                    <div class="col-md-4">
                        <label for="couponCode" class="form-label">Coupon code</label>
                        <div class="input-group">
                            <input type="text" class="form-control" name="coupon_code" id="couponCode" placeholder="Enter code" maxlength="100">
                            <button type="button" class="btn btn-outline-secondary" id="btnApplyCoupon">Apply</button>
                        </div>
                    </div>
                </div>
                <div id="couponMessage" class="mt-2 small" style="display: none;"></div>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-body">
                <div class="row justify-content-end">
                    <div class="col-md-5 col-lg-4">
                        <table class="table table-sm mb-0">
                            <tr>
                                <td>Subtotal</td>
                                <td class="text-end" id="displaySubtotal">0.00</td>
                            </tr>
                            <tr id="rowProductDiscount" style="display: none;">
                                <td>Product discount</td>
                                <td class="text-end text-muted" id="displayProductDiscount">0.00</td>
                            </tr>
                            <tr>
                                <td>Discount (coupon)<span id="appliedCouponLabel" class="text-muted small ms-1" style="display:none;"></span></td>
                                <td class="text-end" id="displayDiscount">0.00</td>
                            </tr>
                            <tr id="rowTotalDiscount" style="display: none;">
                                <td>Total discount</td>
                                <td class="text-end" id="displayTotalDiscount">0.00</td>
                            </tr>
                            <tr class="fw-semibold">
                                <td>Total</td>
                                <td class="text-end" id="displayTotal">0.00</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <input type="hidden" name="coupon_discount_amount" id="couponDiscountAmount" value="0">
        <button type="submit" class="btn btn-primary" id="btnSubmitOrder">Create order</button>
    <?= form_close() ?>
</div>

<!-- Quick add customer modal -->
<div class="modal fade" id="quickAddCustomerModal" tabindex="-1" aria-labelledby="quickAddCustomerModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title fs-6" id="quickAddCustomerModalLabel">Quick add customer</h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="quickAddCustomerForm">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="qaCustomerType" class="form-label">Type <span class="text-danger">*</span></label>
                            <select class="form-select" id="qaCustomerType" name="customer_type" required>
                                <option value="INDIVIDUAL">INDIVIDUAL</option>
                                <option value="BUSINESS">BUSINESS</option>
                                <option value="WALK_IN">WALK_IN</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="qaCustomerName" class="form-label">Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="qaCustomerName" name="name" required maxlength="150">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="qaCustomerPhone" class="form-label">Phone <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="qaCustomerPhone" name="phone" required maxlength="30">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="qaCustomerEmail" class="form-label">Email</label>
                            <input type="email" class="form-control" id="qaCustomerEmail" name="email" maxlength="191">
                        </div>
                    </div>
                    <div id="quickAddCustomerError" class="text-danger small mb-2" style="display: none;"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save and use</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
(function () {
    var baseUrl = '<?= base_url() ?>';
    var apiRoot = baseUrl.replace(/\/?$/, '/');
    var csrfName = '<?= csrf_token() ?>';
    var csrfVal = '<?= csrf_hash() ?>';

    var customerIdEl = document.getElementById('customerId');
    var customerPhoneSearch = document.getElementById('customerPhoneSearch');
    var customerDisplay = document.getElementById('customerDisplay');
    var customerError = document.getElementById('customerError');
    var productSearch = document.getElementById('productSearch');
    var productSearchResults = document.getElementById('productSearchResults');
    var orderItemsBody = document.getElementById('orderItemsBody');
    var orderItemsEmpty = document.getElementById('orderItemsEmpty');
    var couponCode = document.getElementById('couponCode');
    var couponMessage = document.getElementById('couponMessage');
    var couponDiscountAmount = document.getElementById('couponDiscountAmount');
    var displaySubtotal = document.getElementById('displaySubtotal');
    var displayDiscount = document.getElementById('displayDiscount');
    var displayTotal = document.getElementById('displayTotal');
    var orderForm = document.getElementById('orderForm');

    var lineItems = [];
    var appliedCouponDiscount = 0;
    var appliedCouponCode = '';

    function showCustomerFound(name, phone, id) {
        customerIdEl.value = id;
        customerDisplay.textContent = 'Customer: ' + name + ' (' + phone + ')';
        customerDisplay.style.display = 'block';
        customerError.style.display = 'none';
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

    document.getElementById('btnLookupCustomer').addEventListener('click', function () {
        var phone = (customerPhoneSearch.value || '').trim();
        if (!phone) {
            showCustomerError('Enter a phone number.');
            return;
        }
        clearCustomerMsg();
        fetch(baseUrl + 'sales/orders/api/customer-by-phone?phone=' + encodeURIComponent(phone))
            .then(function (r) { return r.json(); })
            .then(function (data) {
                if (data.found && data.customer) {
                    showCustomerFound(data.customer.name, data.customer.phone, data.customer.id);
                } else {
                    showCustomerError('No customer found with this phone. Use Quick add customer.');
                }
            })
            .catch(function () { showCustomerError('Lookup failed.'); });
    });

    document.getElementById('btnQuickAddCustomer').addEventListener('click', function () {
        var modal = new bootstrap.Modal(document.getElementById('quickAddCustomerModal'));
        document.getElementById('quickAddCustomerForm').reset();
        document.getElementById('quickAddCustomerError').style.display = 'none';
        modal.show();
    });

    document.getElementById('quickAddCustomerForm').addEventListener('submit', function (e) {
        e.preventDefault();
        var form = this;
        var errEl = document.getElementById('quickAddCustomerError');
        errEl.style.display = 'none';
        var fd = new FormData(form);
        fd.append(csrfName, csrfVal);
        fetch(baseUrl + 'sales/orders/api/quick-add-customer', {
            method: 'POST',
            body: fd,
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
            .then(function (r) { return r.json(); })
            .then(function (data) {
                if (data.success && data.customer) {
                    var c = data.customer;
                    showCustomerFound(c.name, c.phone, c.id);
                    customerPhoneSearch.value = c.phone;
                    bootstrap.Modal.getInstance(document.getElementById('quickAddCustomerModal')).hide();
                } else {
                    errEl.textContent = (data.errors && typeof data.errors === 'object') ? Object.values(data.errors).join(' ') : (data.message || 'Failed to add customer.');
                    errEl.style.display = 'block';
                }
            })
            .catch(function () {
                errEl.textContent = 'Request failed.';
                errEl.style.display = 'block';
            });
    });

    var productSearchTimeout;
    productSearch.addEventListener('input', function () {
        var q = (productSearch.value || '').trim();
        clearTimeout(productSearchTimeout);
        productSearchResults.style.display = 'none';
        productSearchResults.innerHTML = '';
        if (q.length < 2) return;
        productSearchTimeout = setTimeout(function () {
            fetch(baseUrl + 'sales/orders/api/products?q=' + encodeURIComponent(q))
                .then(function (r) { return r.json(); })
                .then(function (products) {
                    if (products.length === 0) {
                        productSearchResults.innerHTML = '<div class="list-group-item text-secondary">No products found.</div>';
                    } else {
                        products.forEach(function (p) {
                            var a = document.createElement('a');
                            a.href = '#';
                            a.className = 'list-group-item list-group-item-action';
                            a.textContent = (p.sku ? p.sku + ' — ' : '') + (p.name || '');
                            a.dataset.id = p.id;
                            a.dataset.name = p.name || '';
                            a.addEventListener('click', function (e) {
                                e.preventDefault();
                                productSearch.value = '';
                                productSearchResults.style.display = 'none';
                                addProductById(p.id, p.name);
                            });
                            productSearchResults.appendChild(a);
                        });
                    }
                    productSearchResults.style.display = 'block';
                });
        }, 250);
    });
    productSearch.addEventListener('blur', function () {
        setTimeout(function () { productSearchResults.style.display = 'none'; }, 200);
    });

    function addProductById(productId, productName) {
        fetch(baseUrl + 'sales/orders/api/product-price?product_id=' + productId)
            .then(function (r) { return r.json(); })
            .then(function (data) {
                if (!data.found) {
                    alert(data.message || 'Product not available or no pricing.');
                    return;
                }
                var available = parseInt(data.remaining_qty, 10) || 0;
                if (available < 1) {
                    alert('Insufficient stock. No units available for this product.');
                    return;
                }
                var listingPrice = typeof data.listing_price !== 'undefined' ? parseFloat(data.listing_price) : parseFloat(data.unit_price);
                var item = {
                    product_id: data.product_id,
                    product_name: data.product_name,
                    batch_id: data.batch_id,
                    batch_code: data.batch_code,
                    rule_name: data.rule_name,
                    unit_cost: data.unit_cost,
                    unit_price: data.unit_price,
                    listing_price: listingPrice,
                    remaining_qty: data.remaining_qty,
                    qty: 1
                };
                var existing = lineItems.find(function (x) { return x.batch_id === item.batch_id && x.product_id === item.product_id; });
                if (existing) {
                    existing.qty = Math.min((existing.qty || 0) + 1, existing.remaining_qty);
                    renderItems();
                } else {
                    lineItems.push(item);
                    renderItems();
                }
            })
            .catch(function () { alert('Could not load price.'); });
    }

    function removeItem(index) {
        lineItems.splice(index, 1);
        renderItems();
    }

    function renderItems() {
        orderItemsBody.innerHTML = '';
        if (lineItems.length === 0) {
            orderItemsBody.appendChild(orderItemsEmpty);
            orderItemsEmpty.style.display = '';
            updateTotals();
            return;
        }
        orderItemsEmpty.style.display = 'none';
        var subtotal = 0;
        lineItems.forEach(function (item, i) {
            var qty = Math.min(Math.max(1, item.qty || 1), item.remaining_qty || 999);
            item.qty = qty;
            var lineTotal = (item.unit_price * qty).toFixed(2);
            subtotal += parseFloat(lineTotal);
            var tr = document.createElement('tr');
            var listPrice = parseFloat(item.listing_price ?? item.unit_price);
            var sellPrice = parseFloat(item.unit_price);
            var unitDiscount = listPrice > sellPrice ? (listPrice - sellPrice) : 0;
            var lineDiscount = unitDiscount * qty;
            var discountCell = lineDiscount > 0 ? ('\u2212' + lineDiscount.toFixed(2)) : '\u2014';
            tr.innerHTML =
                '<td>' + escapeHtml(item.product_name) + '</td>' +
                '<td class="small">' + escapeHtml(item.batch_code) + ' / ' + escapeHtml(item.rule_name) + '</td>' +
                '<td class="text-center"><input type="number" min="1" max="' + (item.remaining_qty || 999) + '" class="form-control form-control-sm qty-input" data-index="' + i + '" value="' + qty + '" style="width:70px;margin:0 auto;"></td>' +
                '<td class="text-end">' + listPrice.toFixed(2) + '</td>' +
                '<td class="text-end text-muted small">' + discountCell + '</td>' +
                '<td class="text-end">' + sellPrice.toFixed(2) + '</td>' +
                '<td class="text-end line-total">' + lineTotal + '</td>' +
                '<td><button type="button" class="btn btn-sm btn-outline-danger remove-item" data-index="' + i + '">×</button></td>';
            orderItemsBody.appendChild(tr);
            tr.querySelector('.qty-input').addEventListener('change', function () {
                var requested = parseInt(this.value, 10) || 1;
                var maxQty = item.remaining_qty || 999;
                if (requested > maxQty) {
                    alert('Only ' + maxQty + ' unit(s) available for this product.');
                    lineItems[i].qty = maxQty;
                    renderItems();
                    return;
                }
                lineItems[i].qty = requested;
                renderItems();
            });
            tr.querySelector('.remove-item').addEventListener('click', function () {
                removeItem(i);
            });
        });
        updateTotals();
    }

    function updateTotals() {
        var subtotal = 0;
        var totalProductDiscount = 0;
        lineItems.forEach(function (item) {
            var qty = item.qty || 1;
            var listPrice = parseFloat(item.listing_price ?? item.unit_price);
            var sellPrice = parseFloat(item.unit_price);
            subtotal += sellPrice * qty;
            if (listPrice > sellPrice) {
                totalProductDiscount += (listPrice - sellPrice) * qty;
            }
        });
        subtotal = Math.round(subtotal * 100) / 100;
        totalProductDiscount = Math.round(totalProductDiscount * 100) / 100;
        var couponDiscount = appliedCouponDiscount;
        var totalDiscount = totalProductDiscount + couponDiscount;
        var total = Math.round((subtotal - couponDiscount) * 100) / 100;
        displaySubtotal.textContent = subtotal.toFixed(2);
        displayDiscount.textContent = couponDiscount.toFixed(2);
        displayTotal.textContent = total.toFixed(2);
        couponDiscountAmount.value = couponDiscount;
        var rowProductDiscount = document.getElementById('rowProductDiscount');
        var displayProductDiscount = document.getElementById('displayProductDiscount');
        var rowTotalDiscount = document.getElementById('rowTotalDiscount');
        var displayTotalDiscount = document.getElementById('displayTotalDiscount');
        if (rowProductDiscount && displayProductDiscount) {
            if (totalProductDiscount > 0) {
                rowProductDiscount.style.display = '';
                displayProductDiscount.textContent = '\u2212' + totalProductDiscount.toFixed(2);
            } else {
                rowProductDiscount.style.display = 'none';
            }
        }
        if (rowTotalDiscount && displayTotalDiscount) {
            if (totalDiscount > 0) {
                rowTotalDiscount.style.display = '';
                displayTotalDiscount.textContent = '\u2212' + totalDiscount.toFixed(2);
            } else {
                rowTotalDiscount.style.display = 'none';
            }
        }
        updateAppliedCouponLabel();
    }
    function updateAppliedCouponLabel() {
        var el = document.getElementById('appliedCouponLabel');
        if (!el) return;
        if (appliedCouponDiscount > 0 && appliedCouponCode) {
            el.textContent = '(' + appliedCouponCode + ' \u2014 \u20B9' + appliedCouponDiscount.toFixed(2) + ')';
            el.style.display = 'inline';
        } else {
            el.textContent = '';
            el.style.display = 'none';
        }
    }

    orderForm.addEventListener('submit', function (e) {
        if (!customerIdEl.value) {
            e.preventDefault();
            alert('Please select or add a customer (look up by phone or quick add).');
            return;
        }
        if (lineItems.length === 0) {
            e.preventDefault();
            alert('Add at least one product.');
            return;
        }
        orderItemsBody.querySelectorAll('tr').forEach(function (tr) {
            if (tr.id === 'orderItemsEmpty') return;
            var idx = parseInt(tr.querySelector('.qty-input').dataset.index, 10);
            var item = lineItems[idx];
            if (!item) return;
            var qtyInput = document.createElement('input');
            qtyInput.type = 'hidden';
            qtyInput.name = 'items[' + idx + '][product_id]';
            qtyInput.value = item.product_id;
            tr.appendChild(qtyInput);
            var batchInput = document.createElement('input');
            batchInput.type = 'hidden';
            batchInput.name = 'items[' + idx + '][batch_id]';
            batchInput.value = item.batch_id;
            tr.appendChild(batchInput);
            var qInput = document.createElement('input');
            qInput.type = 'hidden';
            qInput.name = 'items[' + idx + '][qty]';
            qInput.value = item.qty;
            tr.appendChild(qInput);
            var upInput = document.createElement('input');
            upInput.type = 'hidden';
            upInput.name = 'items[' + idx + '][unit_price]';
            upInput.value = item.unit_price;
            tr.appendChild(upInput);
            var ucInput = document.createElement('input');
            ucInput.type = 'hidden';
            ucInput.name = 'items[' + idx + '][unit_cost_snapshot]';
            ucInput.value = item.unit_cost;
            tr.appendChild(ucInput);
            var lpInput = document.createElement('input');
            lpInput.type = 'hidden';
            lpInput.name = 'items[' + idx + '][listing_price_snapshot]';
            lpInput.value = item.listing_price ?? item.unit_price;
            tr.appendChild(lpInput);
        });
    });

    document.getElementById('btnApplyCoupon').addEventListener('click', function () {
        var code = (couponCode.value || '').trim();
        var subtotal = 0;
        lineItems.forEach(function (item) {
            subtotal += (item.unit_price || 0) * (item.qty || 1);
        });
        subtotal = Math.round(subtotal * 100) / 100;
        if (!code) {
            couponMessage.textContent = 'Enter a coupon code.';
            couponMessage.className = 'mt-2 small text-danger';
            couponMessage.style.display = 'block';
            appliedCouponDiscount = 0;
            appliedCouponCode = '';
            updateTotals();
            updateAppliedCouponLabel();
            return;
        }
        if (lineItems.length === 0) {
            couponMessage.textContent = 'Add products to the order before applying a coupon.';
            couponMessage.className = 'mt-2 small text-danger';
            couponMessage.style.display = 'block';
            appliedCouponDiscount = 0;
            appliedCouponCode = '';
            updateTotals();
            updateAppliedCouponLabel();
            return;
        }
        var params = new URLSearchParams();
        params.set('code', code);
        params.set('subtotal', String(subtotal));
        fetch(apiRoot + 'sales/orders/api/validate-coupon?' + params.toString(), {
            method: 'GET',
            credentials: 'same-origin',
            headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
        })
            .then(function (r) {
                if (!r.ok) {
                    return Promise.reject(new Error('HTTP ' + r.status));
                }
                return r.json();
            })
            .then(function (data) {
                couponMessage.style.display = 'block';
                if (data.valid) {
                    appliedCouponDiscount = data.discount_amount || 0;
                    appliedCouponCode = (data.code || '').trim();
                    couponMessage.textContent = appliedCouponCode ? ('Coupon ' + appliedCouponCode + ' applied. Discount: \u20B9' + appliedCouponDiscount.toFixed(2)) : (data.message + ' Discount: \u20B9' + appliedCouponDiscount.toFixed(2));
                    couponMessage.className = 'mt-2 small text-success';
                } else {
                    appliedCouponDiscount = 0;
                    appliedCouponCode = '';
                    couponMessage.textContent = data.message || 'Invalid coupon.';
                    couponMessage.className = 'mt-2 small text-danger';
                }
                updateTotals();
                updateAppliedCouponLabel();
            })
            .catch(function () {
                couponMessage.textContent = 'Could not validate coupon. Check your connection or try again.';
                couponMessage.className = 'mt-2 small text-danger';
                couponMessage.style.display = 'block';
            });
    });

    function escapeHtml(s) {
        if (!s) return '';
        var div = document.createElement('div');
        div.textContent = s;
        return div.innerHTML;
    }
})();
</script>

<style>
#productSearchResults { min-width: 300px; }
#orderItemsTable .qty-input { display: inline-block; }
</style>
