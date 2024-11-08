<?php

declare(strict_types=1);

namespace App\Modules\Invoices\Domain\ValueObjects;

use App\Modules\Invoices\Domain\ValueObjects\Money;

class InvoiceProduct
{
    public function __construct(
        public readonly string $name,
        public readonly Money $unitPrice,
        public readonly int $quantity,
    )
    {}
}
