<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('dashboard/erp', 'Home::erpDashboard');

// Auth: login screen and logout
$routes->get('login', 'Auth::login');
$routes->post('auth/attemptLogin', 'Auth::attemptLogin');
$routes->get('logout', 'Auth::logout');

// Catalog - Vendors
$routes->get('catalog/vendors', 'Vendors::index', ['filter' => 'admin']);
$routes->post('catalog/vendors', 'Vendors::add', ['filter' => 'admin']);
$routes->post('catalog/vendors/update/(:num)', 'Vendors::update/$1', ['filter' => 'admin']);
$routes->post('catalog/vendors/set-status/(:num)', 'Vendors::setStatus/$1', ['filter' => 'admin']);

// Catalog - Products
$routes->get('catalog/products', 'Products::index');
$routes->get('catalog/beverage', 'Products::beverage');
$routes->post('catalog/products', 'Products::add');
$routes->post('catalog/products/update/(:num)', 'Products::update/$1');
$routes->post('catalog/products/set-status/(:num)', 'Products::setStatus/$1');

// Catalog - Coupons
$routes->get('catalog/coupons', 'Coupons::index');
$routes->post('catalog/coupons', 'Coupons::add');
$routes->post('catalog/coupons/update/(:num)', 'Coupons::update/$1');
$routes->post('catalog/coupons/set-status/(:num)', 'Coupons::setStatus/$1');

// Inventory - Stock Movements (list only, sorted by date)
$routes->get('inventory/stock-movements', 'StockMovements::index');

// Inventory - Procurement Rules (create and activate/deactivate only; no edit)
$routes->get('inventory/procurement-rules', 'ProcurementRules::index', ['filter' => 'admin']);
$routes->post('inventory/procurement-rules', 'ProcurementRules::add', ['filter' => 'admin']);
$routes->post('inventory/procurement-rules/set-status/(:num)', 'ProcurementRules::setStatus/$1', ['filter' => 'admin']);

// Sales - Customers
$routes->get('sales/customers', 'Customers::index');
$routes->post('sales/customers', 'Customers::add');
$routes->post('sales/customers/update/(:num)', 'Customers::update/$1');
$routes->post('sales/customers/set-status/(:num)', 'Customers::setStatus/$1');

// Sales - Orders (list + create order screen)
$routes->get('sales/orders', 'Orders::index');
$routes->get('sales/orders/create', 'Orders::create');
$routes->post('sales/orders/store', 'Orders::store');
$routes->post('sales/orders/set-status/(:num)', 'Orders::setStatus/$1');
$routes->get('sales/invoices', 'Invoices::index');
$routes->get('sales/invoices/view/(:num)', 'Invoices::view/$1');
$routes->get('sales/orders/api/products', 'Orders::apiProducts');
$routes->get('sales/orders/api/customer-by-phone', 'Orders::apiCustomerByPhone');
$routes->get('sales/orders/api/validate-coupon', 'Orders::apiValidateCoupon');
$routes->post('sales/orders/api/validate-coupon', 'Orders::apiValidateCoupon');
$routes->get('sales/orders/api/product-price', 'Orders::apiProductPrice');
$routes->post('sales/orders/api/quick-add-customer', 'Orders::apiQuickAddCustomer');

// Inventory - Stock Batches (list, add page, edit)
$routes->get('inventory/stock-batches', 'StockBatches::index', ['filter' => 'admin']);
$routes->get('inventory/stock-batches/add', 'StockBatches::add', ['filter' => 'admin']);
$routes->post('inventory/stock-batches/create', 'StockBatches::create', ['filter' => 'admin']);
$routes->get('inventory/stock-batches/edit/(:num)', 'StockBatches::edit/$1', ['filter' => 'admin']);
$routes->post('inventory/stock-batches/update/(:num)', 'StockBatches::update/$1', ['filter' => 'admin']);
$routes->post('inventory/stock-batches/delete/(:num)', 'StockBatches::delete/$1', ['filter' => 'admin']);

// Gaming - Categories (gaming categories and modes) — admin only
$routes->get('gaming/categories', 'Gaming::categories', ['filter' => 'admin']);
$routes->post('gaming/categories', 'Gaming::addCategory', ['filter' => 'admin']);
$routes->post('gaming/categories/update/(:num)', 'Gaming::updateCategory/$1', ['filter' => 'admin']);
$routes->post('gaming/categories/set-status/(:num)', 'Gaming::setStatusCategory/$1', ['filter' => 'admin']);
$routes->post('gaming/controllers', 'Gaming::addController', ['filter' => 'admin']);
$routes->post('gaming/controllers/update/(:num)', 'Gaming::updateController/$1', ['filter' => 'admin']);
$routes->post('gaming/controllers/set-status/(:num)', 'Gaming::setStatusController/$1', ['filter' => 'admin']);
$routes->post('gaming/titles', 'Gaming::addTitle', ['filter' => 'admin']);
$routes->post('gaming/titles/update/(:num)', 'Gaming::updateTitle/$1', ['filter' => 'admin']);
$routes->post('gaming/titles/set-status/(:num)', 'Gaming::setStatusTitle/$1', ['filter' => 'admin']);
$routes->post('gaming/modes', 'Gaming::addMode', ['filter' => 'admin']);
$routes->post('gaming/modes/update/(:num)', 'Gaming::updateMode/$1', ['filter' => 'admin']);
$routes->post('gaming/modes/set-status/(:num)', 'Gaming::setStatusMode/$1', ['filter' => 'admin']);

// Gaming - Price rules — admin only
$routes->get('gaming/price-rules', 'Gaming::priceRules', ['filter' => 'admin']);
$routes->post('gaming/price-rules', 'Gaming::addPriceRule', ['filter' => 'admin']);
$routes->post('gaming/price-rules/update/(:num)', 'Gaming::updatePriceRule/$1', ['filter' => 'admin']);
$routes->post('gaming/price-rules/set-status/(:num)', 'Gaming::setStatusPriceRule/$1', ['filter' => 'admin']);

// Gaming - Food & Beverages
$routes->get('gaming/food-beverages', 'Gaming::foodBeverages');
$routes->post('gaming/food-beverages', 'Gaming::addFoodBeverageItem');
$routes->post('gaming/food-beverages/update/(:num)', 'Gaming::updateFoodBeverageItem/$1');
$routes->post('gaming/food-beverages/set-status/(:num)', 'Gaming::setStatusFoodBeverageItem/$1');

// Gaming - Sessions (start session, ongoing tiles, ended sessions, add food, end, generate invoice)
$routes->get('gaming/sessions', 'Gaming::sessions');
$routes->post('gaming/sessions/start', 'Gaming::startSession');
$routes->post('gaming/sessions/add-food', 'Gaming::addFood');
$routes->post('gaming/sessions/end/(:num)', 'Gaming::endSession/$1');
$routes->post('gaming/sessions/generate-invoice/(:num)', 'Gaming::generateInvoice/$1');
$routes->get('gaming/sessions/api/customer-search', 'Gaming::apiCustomerSearch');

// Admin - Users — admin only
$routes->get('admin/users', 'Admin\Users::index', ['filter' => 'admin']);
$routes->post('admin/users', 'Admin\Users::add', ['filter' => 'admin']);
$routes->post('admin/users/update/(:num)', 'Admin\Users::update/$1', ['filter' => 'admin']);
$routes->post('admin/users/set-status/(:num)', 'Admin\Users::setStatus/$1', ['filter' => 'admin']);

// Admin - Activity Log — admin only
$routes->get('admin/activity-log', 'ActivityLog::index', ['filter' => 'admin']);

// API - Products (list with search, sort, filter, pagination)
$routes->get('api/products', 'Api\Products::index');
