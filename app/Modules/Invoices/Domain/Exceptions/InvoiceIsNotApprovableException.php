<?php

declare(strict_types=1);

namespace App\Modules\Invoices\Domain\Exceptions;

use DomainException;

class InvoiceIsNotApprovableException extends DomainException
{
    public function __construct()
    {
        parent::__construct('The invoice is already either approved or rejected');
    }
}
