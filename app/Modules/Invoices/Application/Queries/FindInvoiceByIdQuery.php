<?php

declare(strict_types=1);

namespace App\Modules\Invoices\Application\Queries;

use App\Modules\Invoices\Api\Dto\InvoiceDto;
use App\Modules\Invoices\Domain\Repositories\InvoiceRepositoryInterface;

class FindInvoiceByIdQuery
{
    private InvoiceRepositoryInterface $invoiceRepository;

    public function __construct()
    {
        $this->invoiceRepository = app(InvoiceRepositoryInterface::class);
    }

    public function execute(string $id): InvoiceDto
    {
        $invoice = $this->invoiceRepository->findById($id);

        return new InvoiceDto($invoice);
    }
}
