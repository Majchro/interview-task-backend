<?php

namespace Tests\Unit\Modules\Invoices\Api\Controllers;

use App\Modules\Invoices\Api\Controllers\RejectInvoiceController;
use App\Modules\Invoices\Application\Commands\SetInvoiceAsRejectedCommand;
use App\Modules\Invoices\Domain\Exceptions\InvoiceIsNotApprovableException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Mockery\MockInterface;
use Tests\TestCase;

class RejectInvoiceControllerTest extends TestCase
{
    public function test_invoice_does_not_exist(): void
    {
        $commandMock = $this->mock(SetInvoiceAsRejectedCommand::class, function (MockInterface $mock) {
            $mock->shouldReceive('execute')->andThrow(ModelNotFoundException::class);
        });

        $controller = new RejectInvoiceController;
        $response = $controller('non-existing-id', $commandMock);

        $this->assertEquals('Invoice not found', $response->getData()->message);
        $this->assertEquals(404, $response->getStatusCode());
    }

    public function test_invoice_is_not_approvable(): void
    {
        $commandMock = $this->mock(SetInvoiceAsRejectedCommand::class, function (MockInterface $mock) {
            $mock->shouldReceive('execute')->andThrow(InvoiceIsNotApprovableException::class);
        });

        $controller = new RejectInvoiceController;
        $response = $controller('id', $commandMock);

        $this->assertEquals(400, $response->getStatusCode());
    }

    public function test_invoice_is_approved(): void
    {
        $commandMock = $this->mock(SetInvoiceAsRejectedCommand::class, function (MockInterface $mock) {
            $mock->shouldReceive('execute')->once();
        });

        $controller = new RejectInvoiceController;
        $response = $controller('id', $commandMock);

        $this->assertEquals('Invoice rejected', $response->getData()->data);
        $this->assertEquals(200, $response->getStatusCode());
    }
}
