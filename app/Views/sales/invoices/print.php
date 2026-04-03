<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice <?= esc($invoice['invoice_number']) ?></title>
    <style>
        * { box-sizing: border-box; }
        body { font-family: system-ui, -apple-system, sans-serif; font-size: 12px; line-height: 1.4; color: #222; max-width: 800px; margin: 0 auto; padding: 20px; }
        .no-print { margin-bottom: 16px; }
        @media print {
            body { padding: 0; }
            .no-print { display: none !important; }
            a { color: #000; text-decoration: none; }
        }
        .invoice-header { display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 20px; margin-bottom: 28px; padding-bottom: 16px; border-bottom: 2px solid #333; }
        .company h1 { margin: 0 0 6px 0; font-size: 20px; font-weight: 700; }
        .company p { margin: 0 0 2px 0; }
        .invoice-title { text-align: right; }
        .invoice-title h2 { margin: 0 0 4px 0; font-size: 24px; font-weight: 700; }
        .invoice-meta { font-size: 11px; color: #555; }
        .parties { display: grid; grid-template-columns: 1fr 1fr; gap: 24px; margin-bottom: 24px; }
        .party-box { padding: 12px; background: #f8f9fa; border-radius: 4px; }
        .party-box h3 { margin: 0 0 8px 0; font-size: 11px; text-transform: uppercase; letter-spacing: 0.05em; color: #666; }
        .party-box p { margin: 0 0 2px 0; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { padding: 8px 10px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background: #f0f0f0; font-size: 11px; text-transform: uppercase; }
        td.qty, td.unit, td.amount { text-align: right; }
        .totals { margin-left: auto; width: 280px; }
        .totals table { margin-bottom: 0; }
        .totals td { border: none; padding: 4px 0; }
        .totals .total-row { font-weight: 700; font-size: 14px; border-top: 2px solid #333; padding-top: 8px; margin-top: 4px; }
        .footer { margin-top: 32px; padding-top: 16px; border-top: 1px solid #ddd; font-size: 11px; color: #666; }
        .footer p { margin: 0 0 4px 0; }
        .payment-terms { margin-top: 16px; }
    </style>
</head>
<body>
    <div class="no-print">
        <a href="<?= base_url('sales/invoices') ?>" style="margin-right:12px;">← Back to Invoices</a>
        <button type="button" onclick="window.print();" style="padding:8px 16px;cursor:pointer;background:#0d6efd;color:#fff;border:none;border-radius:4px;">Print / Save as PDF</button>
    </div>

    <div class="invoice-header">
        <div class="company">
            <h1><?= esc($company->name) ?></h1>
            <?php if ($company->addressLine1): ?><p><?= esc($company->addressLine1) ?></p><?php endif; ?>
            <?php if ($company->addressLine2): ?><p><?= esc($company->addressLine2) ?></p><?php endif; ?>
            <?php if ($company->city || $company->state || $company->postalCode): ?>
                <p><?= implode(', ', array_filter([$company->city, $company->state, $company->postalCode])) ?></p>
            <?php endif; ?>
            <?php if ($company->country): ?><p><?= esc($company->country) ?></p><?php endif; ?>
            <?php if ($company->phone): ?><p>Tel: <?= esc($company->phone) ?></p><?php endif; ?>
            <?php if ($company->email): ?><p>Email: <?= esc($company->email) ?></p><?php endif; ?>
            <?php if ($company->taxNumber): ?><p><strong>Tax / GST:</strong> <?= esc($company->taxNumber) ?></p><?php endif; ?>
            <?php if ($company->website): ?><p><?= esc($company->website) ?></p><?php endif; ?>
        </div>
        <div class="invoice-title">
            <h2>INVOICE</h2>
            <p class="invoice-meta"><strong>No.</strong> <?= esc($invoice['invoice_number']) ?></p>
            <p class="invoice-meta"><strong>Date:</strong> <?= $invoice['issued_at'] ? date('d M Y', strtotime($invoice['issued_at'])) : '—' ?></p>
            <?php if (! empty($isGaming) && ! empty($invoice['gaming_visit_id'])): ?>
                <p class="invoice-meta"><strong>Gaming session #</strong> <?= (int) $invoice['gaming_visit_id'] ?></p>
            <?php elseif (! empty($order['order_number'])): ?>
                <p class="invoice-meta"><strong>Order ref.</strong> <?= esc($order['order_number']) ?></p>
            <?php endif; ?>
        </div>
    </div>

    <div class="parties">
        <div class="party-box">
            <h3>Bill from (Seller)</h3>
            <p><strong><?= esc($company->name) ?></strong></p>
            <?php if ($company->addressLine1): ?><p><?= esc($company->addressLine1) ?></p><?php endif; ?>
            <?php if ($company->taxNumber): ?><p>Tax ID: <?= esc($company->taxNumber) ?></p><?php endif; ?>
        </div>
        <div class="party-box">
            <h3>Bill to (Customer)</h3>
            <p><strong><?= esc($customer['name'] ?? '—') ?></strong></p>
            <?php if (! empty($customer['address_line1'])): ?><p><?= esc($customer['address_line1']) ?></p><?php endif; ?>
            <?php if (! empty($customer['address_line2'])): ?><p><?= esc($customer['address_line2']) ?></p><?php endif; ?>
            <?php if (! empty($customer['city']) || ! empty($customer['state']) || ! empty($customer['postal_code'])): ?>
                <p><?= implode(', ', array_filter([$customer['city'] ?? '', $customer['state'] ?? '', $customer['postal_code'] ?? ''])) ?></p>
            <?php endif; ?>
            <?php if (! empty($customer['phone'])): ?><p>Tel: <?= esc($customer['phone']) ?></p><?php endif; ?>
            <?php if (! empty($customer['email'])): ?><p>Email: <?= esc($customer['email']) ?></p><?php endif; ?>
            <?php if (! empty($customer['tax_number'])): ?><p>Tax ID: <?= esc($customer['tax_number']) ?></p><?php endif; ?>
        </div>
    </div>

    <?php
    $coupon = $coupon ?? null;
    ?>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Description</th>
                <th class="qty">Qty</th>
                <th class="unit">Listing price</th>
                <th class="unit">Discount</th>
                <th class="unit">Price after discount</th>
                <th class="amount">Amount</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $sr = 1;
            $productDiscountForDisplay = 0.0;
            foreach ($items as $row):
                $qty          = max(1, (int) ($row['qty'] ?? 1));
                $sellingUnit = (float) ($row['unit_price'] ?? 0);
                $listingUnit = (float) ($row['listing_price_snapshot'] ?? $row['unit_price'] ?? 0);
                $unitDiscount = $listingUnit > $sellingUnit ? ($listingUnit - $sellingUnit) : 0.0;
                $discountLine = $unitDiscount * $qty;
                $lineTotal = (float) ($row['line_total'] ?? ($sellingUnit * $qty));
                $productDiscountForDisplay += $discountLine;
                ?>
                <tr>
                    <td><?= $sr++ ?></td>
                    <td>
                        <?= esc($row['description'] ?? $row['product_name'] ?? 'Product') ?>
                        <?php if (! empty($row['sku'])): ?><br><span style="font-size:10px;color:#666;">SKU: <?= esc($row['sku']) ?></span><?php endif; ?>
                    </td>
                    <td class="qty"><?= (int) $qty ?></td>
                    <td class="unit"><?= number_format($listingUnit, 2) ?></td>
                    <td class="unit"><?= $discountLine > 0 ? '-' . number_format($discountLine, 2) : '—' ?></td>
                    <td class="unit"><?= number_format($sellingUnit, 2) ?></td>
                    <td class="amount"><?= number_format($lineTotal, 2) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <?php
    $couponDiscount  = isset($couponDiscount) ? (float) $couponDiscount : (float) ($invoice['discount_amount'] ?? 0);
    $productDiscount = round($productDiscountForDisplay, 2);
    $totalDiscount   = round($productDiscount + $couponDiscount, 2);
    ?>
    <div class="totals">
        <table>
            <tr><td>Subtotal</td><td style="text-align:right;"><?= number_format((float) $invoice['subtotal'], 2) ?></td></tr>
            <tr>
                <td>
                    Discount (coupon)<?php if (! empty($coupon) && $couponDiscount > 0): ?> <span style="color:#555;">(<?= esc($coupon['code']) ?><?php
                        $dtype = $coupon['discount_type'] ?? '';
                        $dval  = (float) ($coupon['discount_value'] ?? 0);
                        if ($dtype === 'PERCENTAGE' || $dtype === 'percent'): ?> — <?= $dval ?>%<?php
                        elseif ($dtype === 'FLAT' || $dtype === 'fixed'): ?> — <?= number_format($dval, 2) ?><?php endif; ?>)</span>
                    <?php endif; ?>
                </td>
                <td style="text-align:right;"><?= $couponDiscount > 0 ? number_format($couponDiscount, 2) : number_format(0, 2) ?></td>
            </tr>
            <tr>
                <td>Total discount</td>
                <td style="text-align:right;"><?= $totalDiscount > 0 ? '-' : '' ?><?= number_format($totalDiscount, 2) ?></td>
            </tr>
            <?php if ((float) ($invoice['tax_amount'] ?? 0) > 0): ?>
                <tr><td>Tax</td><td style="text-align:right;"><?= number_format((float) $invoice['tax_amount'], 2) ?></td></tr>
            <?php endif; ?>
            <tr class="total-row"><td>Total</td><td style="text-align:right;"><?= number_format((float) $invoice['total_amount'], 2) ?></td></tr>
        </table>
    </div>

    <?php if ($company->paymentTerms): ?>
        <div class="payment-terms"><strong>Payment terms:</strong> <?= esc($company->paymentTerms) ?></div>
    <?php endif; ?>

    <div class="footer">
        <?php if ($company->invoiceFooter): ?>
            <p><?= esc($company->invoiceFooter) ?></p>
        <?php endif; ?>
        <p>This is a computer-generated invoice and does not require a signature.</p>
    </div>
</body>
</html>
