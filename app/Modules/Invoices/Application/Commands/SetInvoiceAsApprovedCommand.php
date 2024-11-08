<?php

declare(strict_types=1);

namespace App\Modules\Invoices\Application\Commands;

use App\Domain\Enums\StatusEnum;
use App\Modules\Approval\Api\ApprovalFacadeInterface;
use App\Modules\Approval\Api\Dto\ApprovalDto;
use App\Modules\Invoices\Domain\Entities\Invoice;
use App\Modules\Invoices\Domain\Exceptions\InvoiceIsNotApprovableException;
use App\Modules\Invoices\Domain\Repositories\InvoiceRepositoryInterface;

class SetInvoiceAsApprovedCommand
{
    private InvoiceRepositoryInterface $invoiceRepository;
    private ApprovalFacadeInterface $approvalFacade;

    public function __construct()
    {
        $this->invoiceRepository = app(InvoiceRepositoryInterface::class);
        $this->approvalFacade = app(ApprovalFacadeInterface::class);
    }

    public function execute(string $id): void
    {
        $invoice = $this->invoiceRepository->findById($id);
        if ($invoice->status !== StatusEnum::DRAFT) {
            throw new InvoiceIsNotApprovableException();
        }

        $this->approvalFacade->approve(new ApprovalDto(
            $invoice->id,
            $invoice->status,
            Invoice::class,
        ));
    }
}
