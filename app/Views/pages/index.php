<div class="container py-4 py-md-5 px-3 px-sm-4">
    <section class="mb-4 mb-md-5">
        <h1 class="page-title fw-semibold mb-2 mb-md-3">Welcome to Gameshala ERP</h1>
        <p class="lead text-secondary mb-0 mb-md-4">Your central hub for managing operations. Use the menu above to navigate Catalog, Inventory, Sales, and Admin.</p>
    </section>

    <section class="mb-4 mb-md-5">
        <h2 class="h5 fw-semibold mb-3">Quick access</h2>
        <div class="row g-3">
            <div class="col-12 col-sm-6 col-lg-3">
                <a href="<?= base_url('catalog/products') ?>" class="quick-link-card">
                    <span class="card-title">Products</span>
                    <p class="card-desc">Manage product catalog and pricing</p>
                </a>
            </div>
            <div class="col-12 col-sm-6 col-lg-3">
                <a href="<?= base_url('sales/orders') ?>" class="quick-link-card">
                    <span class="card-title">Orders</span>
                    <p class="card-desc">View and manage sales orders</p>
                </a>
            </div>
            <div class="col-12 col-sm-6 col-lg-3">
                <a href="<?= base_url('inventory/stock-movements') ?>" class="quick-link-card">
                    <span class="card-title">Stock</span>
                    <p class="card-desc">Track inventory and movements</p>
                </a>
            </div>
            <?php if (is_admin()): ?>
            <div class="col-12 col-sm-6 col-lg-3">
                <a href="<?= base_url('admin/users') ?>" class="quick-link-card">
                    <span class="card-title">Users</span>
                    <p class="card-desc">Manage admin and staff users</p>
                </a>
            </div>
            <?php endif; ?>
        </div>
    </section>
</div>
