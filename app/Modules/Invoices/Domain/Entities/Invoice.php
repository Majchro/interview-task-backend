<?php

declare(strict_types=1);

namespace App\Modules\Invoices\Domain\Entities;

use App\Domain\Enums\StatusEnum;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Ramsey\Uuid\UuidInterface;

class Invoice
{
    public function __construct(
        public ?UuidInterface $id,
        public string $number,
        public Carbon $date,
        public Carbon $dueDate,
        public Company $company,
        public StatusEnum $status,
        public Collection $products,
    )
    {}
}
