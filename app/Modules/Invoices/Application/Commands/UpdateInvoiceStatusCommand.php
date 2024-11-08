<?php

declare(strict_types=1);

namespace App\Modules\Invoices\Application\Commands;

use App\Domain\Enums\StatusEnum;
use App\Modules\Invoices\Domain\Entities\Invoice;
use App\Modules\Invoices\Domain\Repositories\InvoiceRepositoryInterface;
use Ramsey\Uuid\UuidInterface;

class UpdateInvoiceStatusCommand
{
    private InvoiceRepositoryInterface $invoiceRepository;

    public function __construct()
    {
        $this->invoiceRepository = app(InvoiceRepositoryInterface::class);
    }

    public function execute(UuidInterface $id, StatusEnum $status): Invoice
    {
        $invoice = $this->invoiceRepository->findById((string) $id);
        $invoice->status = $status;

        return $this->invoiceRepository->save($invoice);
    }
}
