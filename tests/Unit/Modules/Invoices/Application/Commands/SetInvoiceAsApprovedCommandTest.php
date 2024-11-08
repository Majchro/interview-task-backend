<?php

namespace Tests\Unit\Modules\Invoices\Application\Commands;

use App\Domain\Enums\StatusEnum;
use App\Modules\Approval\Api\ApprovalFacadeInterface;
use App\Modules\Invoices\Application\Commands\SetInvoiceAsApprovedCommand;
use App\Modules\Invoices\Domain\Entities\Company;
use App\Modules\Invoices\Domain\Entities\Invoice;
use App\Modules\Invoices\Domain\Exceptions\InvoiceIsNotApprovableException;
use App\Modules\Invoices\Domain\Repositories\InvoiceRepositoryInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Mockery\MockInterface;
use Ramsey\Uuid\Uuid;
use Tests\TestCase;

class SetInvoiceAsApprovedCommandTest extends TestCase
{
    private function createInvoice(StatusEnum $status): Invoice
    {
        return new Invoice(
            id: Uuid::uuid4(),
            number: 'number',
            date: now(),
            dueDate: now(),
            company: new Company(
                id: Uuid::uuid4(),
                name: 'name',
                street: 'street',
                city: 'city',
                zip: 'zip',
                phone: 'phone',
                email: 'email',
            ),
            status: $status,
            products: collect([]),
        );
    }

    public function test_invoice_does_not_exist(): void
    {
        $this->mock(InvoiceRepositoryInterface::class, function (MockInterface $mock) {
            $mock->shouldReceive('findById')->andThrow(ModelNotFoundException::class);
        });

        $command = new SetInvoiceAsApprovedCommand;
        $this->expectException(ModelNotFoundException::class);
        $command->execute('non-existing-id');
    }

    public function test_invoice_is_not_approvable(): void
    {
        $this->mock(InvoiceRepositoryInterface::class, function (MockInterface $mock)  {
            $mock->shouldReceive('findById')->andReturn($this->createInvoice(StatusEnum::APPROVED));
        });

        $command = new SetInvoiceAsApprovedCommand;
        $this->expectException(InvoiceIsNotApprovableException::class);
        $command->execute('id');
    }

    public function test_invoice_is_approved(): void
    {
        $invoice = $this->createInvoice(StatusEnum::DRAFT);
        $this->mock(InvoiceRepositoryInterface::class, function (MockInterface $mock) use ($invoice) {
            $mock->shouldReceive('findById')->andReturn($invoice);
        });

        $this->mock(ApprovalFacadeInterface::class, function (MockInterface $mock) use ($invoice) {
            $mock->shouldReceive('approve')->andReturnTrue()->once();
        });

        $command = new SetInvoiceAsApprovedCommand;
        $command->execute('id');
    }
}
