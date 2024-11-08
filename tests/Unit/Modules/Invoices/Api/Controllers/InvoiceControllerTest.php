<?php

namespace Tests\Unit\Modules\Invoices\Api\Controllers;

use App\Modules\Invoices\Api\Controllers\InvoiceController;
use App\Modules\Invoices\Api\Dto\InvoiceDto;
use App\Modules\Invoices\Application\Queries\FindInvoiceByIdQuery;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Mockery\MockInterface;
use Tests\TestCase;

class InvoiceControllerTest extends TestCase
{
    public function test_invoice_does_not_exist(): void
    {
        $queryMock = $this->mock(FindInvoiceByIdQuery::class, function (MockInterface $mock) {
            $mock->shouldReceive('execute')->andThrow(ModelNotFoundException::class);
        });

        $controller = new InvoiceController;
        $response = $controller->show('non-existing-id', $queryMock);

        $this->assertEquals('Invoice not found', $response->getData()->message);
        $this->assertEquals(404, $response->getStatusCode());
    }

    public function test_invoice_is_found(): void
    {
        $invoiceDtoMock = $this->mock(InvoiceDto::class);
        $queryMock = $this->mock(FindInvoiceByIdQuery::class, function (MockInterface $mock) use ($invoiceDtoMock) {
            $mock->shouldReceive('execute')->andReturn($invoiceDtoMock);
        });

        $controller = new InvoiceController;
        $response = $controller->show('id', $queryMock);

        $this->assertEquals(200, $response->getStatusCode());
    }
}
