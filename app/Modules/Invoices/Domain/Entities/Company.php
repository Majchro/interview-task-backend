<?php

declare(strict_types=1);

namespace App\Modules\Invoices\Domain\Entities;

use Ramsey\Uuid\UuidInterface;

class Company
{
    public function __construct(
        public UuidInterface $id,
        public string $name,
        public string $street,
        public string $city,
        public string $zip,
        public string $phone,
        public string $email,
    )
    {}
}
