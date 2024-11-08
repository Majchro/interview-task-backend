<?php

declare(strict_types=1);

namespace Tests\Unit\Modules\Invoices\Infrastructure\EventSubscribers;

use App\Domain\Enums\StatusEnum;
use App\Modules\Approval\Api\Dto\ApprovalDto;
use App\Modules\Approval\Api\Events\EntityApproved;
use App\Modules\Approval\Api\Events\EntityRejected;
use App\Modules\Invoices\Application\Commands\UpdateInvoiceStatusCommand;
use App\Modules\Invoices\Domain\Entities\Invoice;
use App\Modules\Invoices\Infrastructure\EventSubscribers\InvoiceApprovalEventSubscriber;
use Mockery\MockInterface;
use Ramsey\Uuid\Uuid;
use Tests\TestCase;

class InvoiceApprovalEventSubscriberTest extends TestCase
{
    private function createApprovalDto(): ApprovalDto
    {
        return new ApprovalDto(
            id: Uuid::uuid4(),
            entity: Invoice::class,
            status: StatusEnum::APPROVED,
        );
    }

    public function test_handle_entity_approved(): void
    {
        $event = new EntityApproved($this->createApprovalDto());
        $command = $this->mock(UpdateInvoiceStatusCommand::class, function (MockInterface $mock) use ($event) {
            $mock->shouldReceive('execute')->once();
        });
        $subscriber = new InvoiceApprovalEventSubscriber();
        $subscriber->handleEntityApproved($event, $command);
    }

    public function test_handle_entity_rejected(): void
    {
        $event = new EntityRejected($this->createApprovalDto());
        $command = $this->mock(UpdateInvoiceStatusCommand::class, function (MockInterface $mock) use ($event) {
            $mock->shouldReceive('execute')->once();
        });
        $subscriber = new InvoiceApprovalEventSubscriber();
        $subscriber->handleEntityRejected($event, $command);
    }
}
