<?php

declare(strict_types=1);

namespace App\Modules\Invoices\Infrastructure\EventSubscribers;

use App\Domain\Enums\StatusEnum;
use App\Modules\Approval\Api\Events\EntityApproved;
use App\Modules\Approval\Api\Events\EntityRejected;
use App\Modules\Invoices\Application\Commands\UpdateInvoiceStatusCommand;
use App\Modules\Invoices\Domain\Entities\Invoice;
use Illuminate\Contracts\Events\Dispatcher;

class InvoiceApprovalEventSubscriber
{
    public function handleEntityApproved(EntityApproved $event, UpdateInvoiceStatusCommand $command): void
    {
        if (!$this->validateEntityTarget($event->approvalDto->entity)) {
            return;
        }

        $command->execute($event->approvalDto->id, StatusEnum::APPROVED);
    }

    public function handleEntityRejected(EntityRejected $event, UpdateInvoiceStatusCommand $command): void
    {
        if (!$this->validateEntityTarget($event->approvalDto->entity)) {
            return;
        }

        $command->execute($event->approvalDto->id, StatusEnum::REJECTED);
    }

    public function subscribe(Dispatcher $dispatcher): array
    {
        return [
            EntityApproved::class => 'handleEntityApproved',
            EntityRejected::class => 'handleEntityRejected',
        ];
    }

    private function validateEntityTarget(string $entity): bool
    {
        return $entity === Invoice::class;
    }
}
