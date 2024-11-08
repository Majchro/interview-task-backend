<?php

namespace Tests\Unit\Modules\Invoices\Application\Commands;

use App\Domain\Enums\StatusEnum;
use App\Modules\Invoices\Application\Commands\UpdateInvoiceStatusCommand;
use App\Modules\Invoices\Domain\Entities\Company;
use App\Modules\Invoices\Domain\Entities\Invoice;
use App\Modules\Invoices\Domain\Repositories\InvoiceRepositoryInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Mockery\MockInterface;
use Ramsey\Uuid\Uuid;
use Tests\TestCase;

class UpdateInvoiceStatusCommandTest extends TestCase
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

        $command = new UpdateInvoiceStatusCommand;
        $this->expectException(ModelNotFoundException::class);
        $command->execute(Uuid::uuid4(), StatusEnum::DRAFT);
    }

    public function test_invoice_is_updated(): void
    {
        $this->mock(InvoiceRepositoryInterface::class, function (MockInterface $mock) {
            $mock->shouldReceive('findById')->andReturn($this->createInvoice(StatusEnum::DRAFT));
            $mock->shouldReceive('save')->once();
        });

        $command = new UpdateInvoiceStatusCommand;
        $command->execute(Uuid::uuid4(), StatusEnum::APPROVED);
    }
}
