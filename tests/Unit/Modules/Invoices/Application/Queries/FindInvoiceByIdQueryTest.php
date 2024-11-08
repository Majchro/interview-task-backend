<?php

namespace Tests\Unit\Modules\Invoices\Application\Queries;

use App\Domain\Enums\StatusEnum;
use App\Modules\Invoices\Api\Dto\InvoiceDto;
use App\Modules\Invoices\Application\Queries\FindInvoiceByIdQuery;
use App\Modules\Invoices\Domain\Entities\Company;
use App\Modules\Invoices\Domain\Entities\Invoice;
use App\Modules\Invoices\Domain\Repositories\InvoiceRepositoryInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Mockery\MockInterface;
use Ramsey\Uuid\Uuid;
use Tests\TestCase;

class FindInvoiceByIdQueryTest extends TestCase
{
    public function test_invoice_does_not_exist(): void
    {
        $this->mock(InvoiceRepositoryInterface::class, function (MockInterface $mock) {
            $mock->shouldReceive('findById')->andThrow(ModelNotFoundException::class);
        });

        $query = new FindInvoiceByIdQuery();
        $this->expectException(ModelNotFoundException::class);
        $query->execute('non-existing-id');
    }

    public function test_invoice_is_found(): void
    {
        $this->mock(InvoiceRepositoryInterface::class, function (MockInterface $mock) {
            $mock->shouldReceive('findById')->andReturn(new Invoice(
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
                status: StatusEnum::REJECTED,
                products: collect([]),
            ));
        });

        $query = new FindInvoiceByIdQuery();
        $result = $query->execute('id');
        $this->assertInstanceOf(InvoiceDto::class, $result);
    }
}
