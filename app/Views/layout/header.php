<header class="site-header">
    <nav class="navbar navbar-expand-lg navbar-dark" aria-label="Main navigation">
        <div class="container-fluid px-3 px-sm-4">
            <a class="navbar-brand d-flex align-items-center" href="<?= base_url('/') ?>" aria-label="Gameshala ERP Home">
                <img src="<?= asset_url('assets/images/logo.png') ?>" alt="Gameshala ERP" class="logo-img me-2" height="36" width="auto" loading="lazy">
                <span class="d-none d-sm-inline">Gameshala ERP</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain" aria-controls="navbarMain" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarMain">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="<?= base_url('catalog') ?>" role="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="bi bi-collection nav-icon me-1"></i>Catalog</a>
                        <ul class="dropdown-menu dropdown-menu-dark">
                            <li><a class="dropdown-item" href="<?= base_url('catalog/products') ?>"><i class="bi bi-box-seam me-2"></i>Products</a></li>
                            <li><a class="dropdown-item" href="<?= base_url('catalog/coupons') ?>"><i class="bi bi-tag me-2"></i>Coupons</a></li>
                            <?php if (is_admin()): ?>
                            <li><a class="dropdown-item" href="<?= base_url('catalog/vendors') ?>"><i class="bi bi-building me-2"></i>Vendors</a></li>
                            <?php endif; ?>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="<?= base_url('inventory') ?>" role="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="bi bi-boxes nav-icon me-1"></i>Inventory</a>
                        <ul class="dropdown-menu dropdown-menu-dark">
                            <?php if (is_admin()): ?>
                            <li><a class="dropdown-item" href="<?= base_url('inventory/stock-batches') ?>"><i class="bi bi-boxes me-2"></i>Stock Batches</a></li>
                            <?php endif; ?>
                            <li><a class="dropdown-item" href="<?= base_url('inventory/stock-movements') ?>"><i class="bi bi-arrow-left-right me-2"></i>Stock Movements</a></li>
                            <?php if (is_admin()): ?>
                            <li><a class="dropdown-item" href="<?= base_url('inventory/procurement-rules') ?>"><i class="bi bi-clipboard-check me-2"></i>Procurement Rules</a></li>
                            <?php endif; ?>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="<?= base_url('sales') ?>" role="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="bi bi-credit-card nav-icon me-1"></i>Sales</a>
                        <ul class="dropdown-menu dropdown-menu-dark">
                            <li><a class="dropdown-item" href="<?= base_url('sales/orders') ?>"><i class="bi bi-cart me-2"></i>Orders</a></li>
                            <li><a class="dropdown-item" href="<?= base_url('sales/customers') ?>"><i class="bi bi-people me-2"></i>Customers</a></li>
                            <li><a class="dropdown-item" href="<?= base_url('sales/invoices') ?>"><i class="bi bi-receipt me-2"></i>Invoices</a></li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="<?= base_url('gaming/sessions') ?>" role="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="bi bi-controller nav-icon me-1"></i>Gaming</a>
                        <ul class="dropdown-menu dropdown-menu-dark">
                            <?php if (is_admin()): ?>
                            <li><a class="dropdown-item" href="<?= base_url('gaming/categories') ?>"><i class="bi bi-tags me-2"></i>Categories</a></li>
                            <?php endif; ?>
                            <li><a class="dropdown-item" href="<?= base_url('gaming/price-rules') ?>"><i class="bi bi-currency-rupee me-2"></i>Price Rules</a></li>
                            <li><a class="dropdown-item" href="<?= base_url('gaming/food-beverages') ?>"><i class="bi bi-cup-straw me-2"></i>Food & Beverages</a></li>
                            <li><a class="dropdown-item" href="<?= base_url('gaming/sessions') ?>"><i class="bi bi-play-circle me-2"></i>Sessions</a></li>
                        </ul>
                    </li>
                    <?php if (is_admin()): ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="<?= base_url('admin/users') ?>" role="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="bi bi-gear nav-icon me-1"></i>Admin</a>
                        <ul class="dropdown-menu dropdown-menu-dark">
                            <li><a class="dropdown-item" href="<?= base_url('admin/users') ?>"><i class="bi bi-person-badge me-2"></i>Users</a></li>
                            <li><a class="dropdown-item" href="<?= base_url('admin/activity-log') ?>"><i class="bi bi-clipboard-data me-2"></i>Activity Log</a></li>
                        </ul>
                    </li>
                    <?php endif; ?>
                    <?php if (session()->has('user_id')): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= base_url('logout') ?>"><i class="bi bi-box-arrow-right nav-icon me-1"></i>Logout</a>
                    </li>
                    <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= base_url('login') ?>"><i class="bi bi-box-arrow-in-right nav-icon me-1"></i>Login</a>
                    </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>
</header>
<main class="site-main">
