<?php

namespace App\Controllers;

use App\Models\OrderModel;
use App\Models\OrderItemModel;
use App\Models\CustomerModel;
use App\Models\ProductModel;
use App\Models\InvoiceModel;
use App\Models\GamingVisitModel;
use App\Models\StockBatchModel;
use App\Models\VendorModel;

class Home extends BaseController
{
    public function index(): string
    {
        $orderModel   = model(OrderModel::class);
        $customerModel = model(CustomerModel::class);
        $productModel = model(ProductModel::class);
        $invoiceModel    = model(InvoiceModel::class);
        $gamingVisitModel = model(GamingVisitModel::class);
        $stockBatchModel = model(StockBatchModel::class);
        $vendorModel     = model(VendorModel::class);

        $thisMonthStart = date('Y-m-01 00:00:00');
        $thisMonthEnd   = date('Y-m-t 23:59:59');
        $todayStart     = date('Y-m-d 00:00:00');
        $todayEnd       = date('Y-m-d 23:59:59');
        $lastMonthStart = date('Y-m-01 00:00:00', strtotime('first day of last month'));
        $lastMonthEnd   = date('Y-m-t 23:59:59', strtotime('last day of last month'));

        $orderItemModel = model(OrderItemModel::class);
        $ordersT        = $orderModel->getTable();
        $itemsT        = $orderItemModel->getTable();

        $invoicesT = $invoiceModel->getTable();
        $visitsT   = $gamingVisitModel->getTable();

        $orderT = $orderModel->db->table($ordersT);
        $totalOrderRevenueRow = $orderT->selectSum('total_amount')->where('status', 'PAID')->get()->getRow();
        $totalOrderRevenue = $totalOrderRevenueRow ? (float) ($totalOrderRevenueRow->total_amount ?? 0) : 0.0;
        $totalOrders = (int) $orderModel->db->table($ordersT)->countAll();
        $revenueOrderThisMonthRow = $orderT->selectSum('total_amount')->where('status', 'PAID')->where('created_at >=', $thisMonthStart)->where('created_at <=', $thisMonthEnd)->get()->getRow();
        $revenueOrderThisMonth = $revenueOrderThisMonthRow ? (float) ($revenueOrderThisMonthRow->total_amount ?? 0) : 0.0;
        $revenueOrderTodayRow = $orderT->selectSum('total_amount')->where('status', 'PAID')->where('created_at >=', $todayStart)->where('created_at <=', $todayEnd)->get()->getRow();
        $revenueOrderToday = $revenueOrderTodayRow ? (float) ($revenueOrderTodayRow->total_amount ?? 0) : 0.0;
        $ordersThisMonth = (int) $orderT->where('status', 'PAID')->where('created_at >=', $thisMonthStart)->where('created_at <=', $thisMonthEnd)->countAllResults();

        $invT = $invoiceModel->db->table($invoicesT);
        $revenueGamingTotalRow = $invT->selectSum('total_amount')->where('gaming_visit_id IS NOT NULL', null, false)->get()->getRow();
        $revenueGamingTotal = $revenueGamingTotalRow ? (float) ($revenueGamingTotalRow->total_amount ?? 0) : 0.0;
        $revenueGamingThisMonthRow = $invoiceModel->db->table($invoicesT)->selectSum('total_amount')->where('gaming_visit_id IS NOT NULL', null, false)->where('created_at >=', $thisMonthStart)->where('created_at <=', $thisMonthEnd)->get()->getRow();
        $revenueGamingThisMonth = $revenueGamingThisMonthRow ? (float) ($revenueGamingThisMonthRow->total_amount ?? 0) : 0.0;
        $revenueGamingTodayRow = $invoiceModel->db->table($invoicesT)->selectSum('total_amount')->where('gaming_visit_id IS NOT NULL', null, false)->where('created_at >=', $todayStart)->where('created_at <=', $todayEnd)->get()->getRow();
        $revenueGamingToday = $revenueGamingTodayRow ? (float) ($revenueGamingTodayRow->total_amount ?? 0) : 0.0;

        $totalRevenue    = $totalOrderRevenue + $revenueGamingTotal;
        $revenueThisMonth = $revenueOrderThisMonth + $revenueGamingThisMonth;
        $revenueToday    = $revenueOrderToday + $revenueGamingToday;

        $profitExpr = "SUM({$itemsT}.line_total - ({$itemsT}.qty * {$itemsT}.unit_cost_snapshot))";
        $totalProfitRow = $orderItemModel->db->query(
            "SELECT {$profitExpr} AS total FROM {$itemsT} JOIN {$ordersT} ON {$ordersT}.id = {$itemsT}.order_id WHERE {$ordersT}.status = 'PAID'"
        )->getRow();
        $totalProfit = $totalProfitRow && $totalProfitRow->total !== null ? (float) $totalProfitRow->total : 0.0;
        $profitThisMonthRow = $orderItemModel->db->query(
            "SELECT {$profitExpr} AS total FROM {$itemsT} JOIN {$ordersT} ON {$ordersT}.id = {$itemsT}.order_id WHERE {$ordersT}.status = 'PAID' AND {$ordersT}.created_at >= ? AND {$ordersT}.created_at <= ?",
            [$thisMonthStart, $thisMonthEnd]
        )->getRow();
        $profitThisMonth = $profitThisMonthRow && $profitThisMonthRow->total !== null ? (float) $profitThisMonthRow->total : 0.0;
        $profitTodayRow = $orderItemModel->db->query(
            "SELECT {$profitExpr} AS total FROM {$itemsT} JOIN {$ordersT} ON {$ordersT}.id = {$itemsT}.order_id WHERE {$ordersT}.status = 'PAID' AND {$ordersT}.created_at >= ? AND {$ordersT}.created_at <= ?",
            [$todayStart, $todayEnd]
        )->getRow();
        $profitToday = $profitTodayRow && $profitTodayRow->total !== null ? (float) $profitTodayRow->total : 0.0;
        $revenueOrderLastMonthRow = $orderT->selectSum('total_amount')->where('status', 'PAID')->where('created_at >=', $lastMonthStart)->where('created_at <=', $lastMonthEnd)->get()->getRow();
        $revenueLastMonth = $revenueOrderLastMonthRow ? (float) ($revenueOrderLastMonthRow->total_amount ?? 0) : 0.0;

        $ordersByStatus = [];
        foreach (['PENDING', 'CONFIRMED', 'PAID', 'CANCELLED'] as $status) {
            $ordersByStatus[$status] = (int) $orderModel->db->table($orderModel->getTable())->where('status', $status)->countAllResults();
        }

        $recentOrders = $orderModel->builder()
            ->select('orders.*, customers.name AS customer_name')
            ->join('customers', 'customers.id = orders.customer_id', 'left')
            ->orderBy('orders.created_at', 'desc')
            ->limit(5)
            ->get()
            ->getResultArray();

        $ongoingSessionsCount = (int) $gamingVisitModel->db->table($visitsT)->where('status', 'ONGOING')->countAllResults();
        $sessionsEndedToday   = (int) $gamingVisitModel->db->table($visitsT)->where('status', 'FINISHED')->where('end_time >=', $todayStart)->where('end_time <=', $todayEnd)->countAllResults();
        $unpaidResult = $gamingVisitModel->db->table($visitsT)->selectSum('total_amount')->where('status', 'FINISHED')->where('invoice_id IS NULL', null, false)->get()->getRow();
        $unpaidSessionsAmount = $unpaidResult && $unpaidResult->total_amount !== null ? (float) $unpaidResult->total_amount : 0.0;
        $unpaidSessionsCount  = (int) $gamingVisitModel->db->table($visitsT)->where('status', 'FINISHED')->where('invoice_id IS NULL', null, false)->countAllResults();

        $prefix = $invoiceModel->db->DBPrefix;
        $recentGamingInvoices = $invoiceModel->builder()
            ->select('invoices.*, customers.name AS customer_name')
            ->join($prefix . 'customers customers', 'customers.id = invoices.customer_id', 'left')
            ->where('invoices.gaming_visit_id IS NOT NULL', null, false)
            ->orderBy('invoices.created_at', 'desc')
            ->limit(5)
            ->get()
            ->getResultArray();

        $totalCustomers = (int) $customerModel->where('is_active', 1)->countAllResults();
        $totalProducts  = (int) $productModel->where('is_active', 1)->countAllResults();
        $totalVendors   = (int) $vendorModel->where('is_active', 1)->countAllResults();
        $totalInvoices  = (int) $invoiceModel->countAllResults();
        $invoiceSumRow = $invoiceModel->db->table($invoiceModel->getTable())->selectSum('total_amount')->get()->getRow();
        $invoicedAmount = $invoiceSumRow ? (float) ($invoiceSumRow->total_amount ?? 0) : 0.0;

        $lowStockThreshold = 5;
        $lowStockBatches   = $stockBatchModel->builder()
            ->select('stock_batches.*, products.name AS product_name')
            ->join('products', 'products.id = stock_batches.product_id', 'left')
            ->where('stock_batches.remaining_qty <=', $lowStockThreshold)
            ->where('stock_batches.remaining_qty >=', 0)
            ->orderBy('stock_batches.remaining_qty', 'asc')
            ->limit(10)
            ->get()
            ->getResultArray();

        $data = [
            'pageTitle'            => 'Dashboard - Gameshala ERP',
            'totalRevenue'         => $totalRevenue,
            'revenueToday'         => $revenueToday,
            'revenueThisMonth'     => $revenueThisMonth,
            'revenueLastMonth'     => $revenueLastMonth,
            'totalOrders'          => $totalOrders,
            'ordersThisMonth'      => $ordersThisMonth,
            'totalProfit'          => $totalProfit,
            'profitThisMonth'      => $profitThisMonth,
            'profitToday'          => $profitToday,
            'ordersByStatus'       => $ordersByStatus,
            'recentOrders'         => $recentOrders,
            'recentGamingInvoices' => $recentGamingInvoices,
            'totalCustomers'       => $totalCustomers,
            'totalProducts'        => $totalProducts,
            'totalVendors'         => $totalVendors,
            'totalInvoices'       => $totalInvoices,
            'invoicedAmount'       => $invoicedAmount,
            'lowStockBatches'      => $lowStockBatches,
            'lowStockThreshold'    => $lowStockThreshold,
            'ongoingSessionsCount' => $ongoingSessionsCount,
            'sessionsEndedToday'   => $sessionsEndedToday,
            'unpaidSessionsCount'  => $unpaidSessionsCount,
            'unpaidSessionsAmount' => $unpaidSessionsAmount,
        ];

        return view('layout/main', [
            'pageTitle' => $data['pageTitle'],
            'content'   => view('pages/dashboard', $data),
        ]);
    }

    /**
     * ERP dashboard: catalog, inventory, sales (orders), profit, customers, low stock.
     */
    public function erpDashboard(): string
    {
        $orderModel      = model(OrderModel::class);
        $orderItemModel  = model(OrderItemModel::class);
        $customerModel   = model(CustomerModel::class);
        $productModel    = model(ProductModel::class);
        $invoiceModel    = model(InvoiceModel::class);
        $stockBatchModel = model(StockBatchModel::class);
        $vendorModel     = model(VendorModel::class);

        $thisMonthStart = date('Y-m-01 00:00:00');
        $thisMonthEnd   = date('Y-m-t 23:59:59');
        $todayStart     = date('Y-m-d 00:00:00');
        $todayEnd       = date('Y-m-d 23:59:59');
        $lastMonthStart = date('Y-m-01 00:00:00', strtotime('first day of last month'));
        $lastMonthEnd   = date('Y-m-t 23:59:59', strtotime('last day of last month'));

        $ordersT = $orderModel->getTable();
        $itemsT  = $orderItemModel->getTable();
        $orderT  = $orderModel->db->table($ordersT);

        $totalRevenueRow = $orderT->selectSum('total_amount')->where('status', 'PAID')->get()->getRow();
        $totalRevenue    = $totalRevenueRow ? (float) ($totalRevenueRow->total_amount ?? 0) : 0.0;
        $totalOrders     = (int) $orderModel->db->table($ordersT)->countAll();
        $paidOrdersCount = (int) $orderT->where('status', 'PAID')->countAllResults();

        $revenueThisMonthRow = $orderT->selectSum('total_amount')->where('status', 'PAID')->where('created_at >=', $thisMonthStart)->where('created_at <=', $thisMonthEnd)->get()->getRow();
        $revenueThisMonth    = $revenueThisMonthRow ? (float) ($revenueThisMonthRow->total_amount ?? 0) : 0.0;
        $revenueTodayRow     = $orderT->selectSum('total_amount')->where('status', 'PAID')->where('created_at >=', $todayStart)->where('created_at <=', $todayEnd)->get()->getRow();
        $revenueToday        = $revenueTodayRow ? (float) ($revenueTodayRow->total_amount ?? 0) : 0.0;
        $ordersThisMonth     = (int) $orderT->where('status', 'PAID')->where('created_at >=', $thisMonthStart)->where('created_at <=', $thisMonthEnd)->countAllResults();

        $revenueLastMonthRow = $orderT->selectSum('total_amount')->where('status', 'PAID')->where('created_at >=', $lastMonthStart)->where('created_at <=', $lastMonthEnd)->get()->getRow();
        $revenueLastMonth    = $revenueLastMonthRow ? (float) ($revenueLastMonthRow->total_amount ?? 0) : 0.0;

        $profitExpr = "SUM({$itemsT}.line_total - ({$itemsT}.qty * {$itemsT}.unit_cost_snapshot))";
        $totalProfitRow = $orderItemModel->db->query(
            "SELECT {$profitExpr} AS total FROM {$itemsT} JOIN {$ordersT} ON {$ordersT}.id = {$itemsT}.order_id WHERE {$ordersT}.status = 'PAID'"
        )->getRow();
        $totalProfit = $totalProfitRow && $totalProfitRow->total !== null ? (float) $totalProfitRow->total : 0.0;
        $profitThisMonthRow = $orderItemModel->db->query(
            "SELECT {$profitExpr} AS total FROM {$itemsT} JOIN {$ordersT} ON {$ordersT}.id = {$itemsT}.order_id WHERE {$ordersT}.status = 'PAID' AND {$ordersT}.created_at >= ? AND {$ordersT}.created_at <= ?",
            [$thisMonthStart, $thisMonthEnd]
        )->getRow();
        $profitThisMonth = $profitThisMonthRow && $profitThisMonthRow->total !== null ? (float) $profitThisMonthRow->total : 0.0;
        $profitTodayRow = $orderItemModel->db->query(
            "SELECT {$profitExpr} AS total FROM {$itemsT} JOIN {$ordersT} ON {$ordersT}.id = {$itemsT}.order_id WHERE {$ordersT}.status = 'PAID' AND {$ordersT}.created_at >= ? AND {$ordersT}.created_at <= ?",
            [$todayStart, $todayEnd]
        )->getRow();
        $profitToday = $profitTodayRow && $profitTodayRow->total !== null ? (float) $profitTodayRow->total : 0.0;

        $ordersByStatus = [];
        foreach (['PENDING', 'CONFIRMED', 'PAID', 'CANCELLED'] as $status) {
            $ordersByStatus[$status] = (int) $orderModel->db->table($ordersT)->where('status', $status)->countAllResults();
        }

        $recentOrders = $orderModel->builder()
            ->select('orders.*, customers.name AS customer_name')
            ->join('customers', 'customers.id = orders.customer_id', 'left')
            ->orderBy('orders.created_at', 'desc')
            ->limit(8)
            ->get()
            ->getResultArray();

        $totalCustomers = (int) $customerModel->where('is_active', 1)->countAllResults();
        $totalProducts  = (int) $productModel->where('is_active', 1)->countAllResults();
        $totalVendors   = (int) $vendorModel->where('is_active', 1)->countAllResults();
        $totalInvoices  = (int) $invoiceModel->countAllResults();
        $invoiceSumRow  = $invoiceModel->db->table($invoiceModel->getTable())->selectSum('total_amount')->get()->getRow();
        $invoicedAmount = $invoiceSumRow ? (float) ($invoiceSumRow->total_amount ?? 0) : 0.0;

        $lowStockThreshold = 5;
        $lowStockBatches   = $stockBatchModel->builder()
            ->select('stock_batches.*, products.name AS product_name')
            ->join('products', 'products.id = stock_batches.product_id', 'left')
            ->where('stock_batches.remaining_qty <=', $lowStockThreshold)
            ->where('stock_batches.remaining_qty >=', 0)
            ->orderBy('stock_batches.remaining_qty', 'asc')
            ->limit(10)
            ->get()
            ->getResultArray();

        $data = [
            'pageTitle'          => 'ERP Dashboard - Gameshala ERP',
            'totalRevenue'       => $totalRevenue,
            'revenueToday'       => $revenueToday,
            'revenueThisMonth'   => $revenueThisMonth,
            'revenueLastMonth'   => $revenueLastMonth,
            'totalOrders'        => $totalOrders,
            'paidOrdersCount'    => $paidOrdersCount,
            'ordersThisMonth'    => $ordersThisMonth,
            'totalProfit'        => $totalProfit,
            'profitThisMonth'    => $profitThisMonth,
            'profitToday'        => $profitToday,
            'ordersByStatus'     => $ordersByStatus,
            'recentOrders'       => $recentOrders,
            'totalCustomers'     => $totalCustomers,
            'totalProducts'      => $totalProducts,
            'totalVendors'       => $totalVendors,
            'totalInvoices'      => $totalInvoices,
            'invoicedAmount'     => $invoicedAmount,
            'lowStockBatches'    => $lowStockBatches,
            'lowStockThreshold'  => $lowStockThreshold,
        ];

        return view('layout/main', [
            'pageTitle' => $data['pageTitle'],
            'content'   => view('pages/erp_dashboard', $data),
        ]);
    }
}
