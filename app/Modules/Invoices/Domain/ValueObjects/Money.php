<?php

declare(strict_types=1);

namespace App\Modules\Invoices\Domain\ValueObjects;

use App\Domain\ValueObject;

final class Money extends ValueObject
{
    public function __construct(
        public readonly int $amount,
        public readonly string $currency
    )
    {}

    public function __toString()
    {
        return ($this->amount / 100) . ' ' . $this->currency;
    }

    public function jsonSerialize(): string
    {
        return $this->__toString();
    }
}
