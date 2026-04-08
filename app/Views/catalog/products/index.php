<div class="container py-4 px-3 px-sm-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3 mb-4">
        <h1 class="h4 fw-semibold mb-0"><?= esc($pageHeading ?? 'Products') ?></h1>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#productModal" id="btnAddProduct">
            Add product
        </button>
    </div>

    <?php
    $productBase = $listBase ?? base_url('catalog/products');
    $productSortUrl = function ($col) use ($productBase, $searchQ, $sort, $order) {
        $next = ($sort === $col && $order === 'asc') ? 'desc' : 'asc';
        return $productBase . '?' . http_build_query(array_filter(['q' => $searchQ, 'sort' => $col, 'order' => $next]));
    };
    $productArrow = function ($col) use ($sort, $order) {
        if ($sort !== $col) return '';
        return $order === 'asc' ? ' ↑' : ' ↓';
    };
    ?>
    <form method="get" action="<?= esc($productBase) ?>" class="mb-4">
        <div class="input-group" style="max-width: 400px;">
            <input type="search" name="q" class="form-control" placeholder="Search by name, SKU, slug, description..."
                   value="<?= esc($searchQ) ?>">
            <button type="submit" class="btn btn-outline-secondary">Search</button>
        </div>
    </form>

    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th><a href="<?= $productSortUrl('sku') ?>" class="text-dark text-decoration-none">SKU<?= $productArrow('sku') ?></a></th>
                    <th><a href="<?= $productSortUrl('name') ?>" class="text-dark text-decoration-none">Name<?= $productArrow('name') ?></a></th>
                    <th><a href="<?= $productSortUrl('unit') ?>" class="text-dark text-decoration-none">Unit<?= $productArrow('unit') ?></a></th>
                    <th><a href="<?= $productSortUrl('is_public') ?>" class="text-dark text-decoration-none">Public<?= $productArrow('is_public') ?></a></th>
                    <th><a href="<?= $productSortUrl('is_active') ?>" class="text-dark text-decoration-none">Status<?= $productArrow('is_active') ?></a></th>
                    <th class="text-end" style="width: 120px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($products)): ?>
                    <tr>
                        <td colspan="6" class="text-center text-secondary py-4">No products found.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($products as $p): ?>
                        <tr>
                            <td><code class="small"><?= esc($p['sku']) ?></code></td>
                            <td><?= esc($p['name']) ?></td>
                            <td><?= esc($p['unit'] ?? 'PCS') ?></td>
                            <td>
                                <?php $public = isset($p['is_public']) ? (int) $p['is_public'] : 1; ?>
                                <span class="badge <?= $public ? 'bg-info' : 'bg-secondary' ?>">
                                    <?= $public ? 'Yes' : 'No' ?>
                                </span>
                            </td>
                            <td>
                                <?php $active = isset($p['is_active']) ? (int) $p['is_active'] : 1; ?>
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
                                            <a class="dropdown-item product-edit" href="#"
                                               data-id="<?= (int) $p['id'] ?>"
                                               data-sku="<?= esc($p['sku']) ?>"
                                               data-name="<?= esc($p['name']) ?>"
                                               data-slug="<?= esc($p['slug'] ?? '') ?>"
                                               data-description="<?= esc($p['description'] ?? '') ?>"
                                               data-image-urls="<?= esc(json_encode($p['image_urls'] ?? [])) ?>"
                                               data-unit="<?= esc($p['unit'] ?? 'PCS') ?>"
                                               data-is-public="<?= isset($p['is_public']) ? (int) $p['is_public'] : 1 ?>">
                                                Edit
                                            </a>
                                        </li>
                                        <?php if ($active): ?>
                                            <li>
                                                <?= form_open(base_url('catalog/products/set-status/' . (int) $p['id']), ['class' => 'd-inline']) ?>
                                                    <?= csrf_field() ?>
                                                    <input type="hidden" name="is_active" value="0">
                                                    <button type="submit" class="dropdown-item text-warning">Mark inactive</button>
                                                <?= form_close() ?>
                                            </li>
                                        <?php else: ?>
                                            <li>
                                                <?= form_open(base_url('catalog/products/set-status/' . (int) $p['id']), ['class' => 'd-inline']) ?>
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

<!-- Add/Edit Product Modal -->
<div class="modal fade" id="productModal" tabindex="-1" aria-labelledby="productModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title fs-6" id="productModalLabel">Add product</h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <?= form_open('', ['id' => 'productForm', 'enctype' => 'multipart/form-data']) ?>
                <?= csrf_field() ?>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="productSku" class="form-label">SKU <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="productSku" name="sku" required maxlength="100" value="" placeholder="e.g. PROD-001">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="productName" class="form-label">Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="productName" name="name" required maxlength="200" value="">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="productSlug" class="form-label">Slug</label>
                        <input type="text" class="form-control" id="productSlug" name="slug" maxlength="200" value="" placeholder="Auto-generated from name if empty">
                    </div>
                    <div class="mb-3">
                        <label for="productDescription" class="form-label">Description</label>
                        <textarea class="form-control" id="productDescription" name="description" rows="2" maxlength="65535"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Images <span class="text-danger">*</span></label>
                        <p class="small text-secondary mb-2">Upload one or more images (required when adding). You can select multiple files in one box or add more boxes.</p>
                        <div id="productCurrentImages" class="d-flex flex-wrap gap-2 mb-2" style="display: none;"></div>
                        <div id="productImageInputs">
                            <input type="file" class="form-control product-image-input mb-2" name="image[]" accept="image/jpeg,image/png,image/gif,image/webp" multiple>
                        </div>
                        <button type="button" class="btn btn-outline-secondary btn-sm mt-1" id="btnAddImageInput">+ Add another image</button>
                        <small class="d-block text-secondary mt-1">Max 2MB each. JPEG, PNG, GIF or WebP.</small>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="productUnit" class="form-label">Unit <span class="text-danger">*</span></label>
                            <select class="form-select" id="productUnit" name="unit" required>
                                <option value="PCS">PCS</option>
                                <option value="BOX">BOX</option>
                                <option value="KG">KG</option>
                                <option value="L">L</option>
                                <option value="M">M</option>
                                <option value="SET">SET</option>
                                <option value="UNIT">UNIT</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3 d-flex align-items-end">
                            <div class="form-check">
                                <input type="hidden" name="is_public" value="0">
                                <input class="form-check-input" type="checkbox" id="productIsPublic" name="is_public" value="1" checked>
                                <label class="form-check-label" for="productIsPublic">Public (visible in catalog)</label>
                            </div>
                        </div>
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
    var form = document.getElementById('productForm');
    var modal = document.getElementById('productModal');
    var modalLabel = document.getElementById('productModalLabel');
    var addUrl = '<?= base_url('catalog/products') ?>';

    var baseUrl = '<?= base_url('/') ?>';

    document.getElementById('btnAddImageInput').addEventListener('click', function () {
        var container = document.getElementById('productImageInputs');
        var input = document.createElement('input');
        input.type = 'file';
        input.className = 'form-control product-image-input mb-2';
        input.name = 'image[]';
        input.setAttribute('accept', 'image/jpeg,image/png,image/gif,image/webp');
        input.multiple = true;
        container.appendChild(input);
    });

    function resetProductImageInputs() {
        var container = document.getElementById('productImageInputs');
        container.innerHTML = '';
        var input = document.createElement('input');
        input.type = 'file';
        input.className = 'form-control product-image-input mb-2';
        input.name = 'image[]';
        input.setAttribute('accept', 'image/jpeg,image/png,image/gif,image/webp');
        input.multiple = true;
        container.appendChild(input);
    }

    document.getElementById('btnAddProduct').addEventListener('click', function () {
        modalLabel.textContent = 'Add product';
        form.action = addUrl;
        form.reset();
        document.getElementById('productIsPublic').checked = true;
        resetProductImageInputs();
        document.getElementById('productCurrentImages').innerHTML = '';
        document.getElementById('productCurrentImages').style.display = 'none';
    });

    document.querySelectorAll('.product-edit').forEach(function (el) {
        el.addEventListener('click', function (e) {
            e.preventDefault();
            var id = this.getAttribute('data-id');
            modalLabel.textContent = 'Edit product';
            form.action = '<?= base_url('catalog/products/update/') ?>' + id;
            document.getElementById('productSku').value = this.getAttribute('data-sku') || '';
            document.getElementById('productName').value = this.getAttribute('data-name') || '';
            document.getElementById('productSlug').value = this.getAttribute('data-slug') || '';
            document.getElementById('productDescription').value = this.getAttribute('data-description') || '';
            document.getElementById('productUnit').value = this.getAttribute('data-unit') || 'PCS';
            document.getElementById('productIsPublic').checked = parseInt(this.getAttribute('data-is-public'), 10) === 1;
            resetProductImageInputs();
            var imageUrls = [];
            try {
                imageUrls = JSON.parse(this.getAttribute('data-image-urls') || '[]');
            } catch (err) {}
            var container = document.getElementById('productCurrentImages');
            container.innerHTML = '';
            if (imageUrls.length > 0) {
                container.style.display = 'flex';
                imageUrls.forEach(function (url) {
                    var img = document.createElement('img');
                    img.src = baseUrl + url;
                    img.alt = '';
                    img.className = 'rounded border';
                    img.style.width = '48px';
                    img.style.height = '48px';
                    img.style.objectFit = 'cover';
                    container.appendChild(img);
                });
            } else {
                container.style.display = 'none';
            }
            new bootstrap.Modal(modal).show();
        });
    });
})();
</script>
