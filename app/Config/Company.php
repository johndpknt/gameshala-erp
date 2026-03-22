<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

/**
 * Company / business details for invoices and documents.
 * Edit these values for your business.
 */
class Company extends BaseConfig
{
    public string $name          = 'Gameshaala';
    public string $addressLine1  = '';
    public string $addressLine2  = '';
    public string $city         = '';
    public string $state        = '';
    public string $postalCode   = '';
    public string $country      = '';
    public string $phone        = '';
    public string $email        = '';
    /** Tax ID / GST / Registration number */
    public string $taxNumber    = '';
    public string $website       = '';
    /** Payment terms shown on invoice (e.g. "Net 30", "Due on receipt") */
    public string $paymentTerms = 'Due on receipt';
    /** Short note or terms (e.g. "Thank you for your business.") */
    public string $invoiceFooter = 'Thank you for shopping with us.';
}
