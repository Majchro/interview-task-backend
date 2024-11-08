<?php

declare(strict_types=1);

namespace App\Modules\Invoices\Infrastructure\Providers;

use App\Modules\Invoices\Domain\Repositories\InvoiceRepositoryInterface;
use App\Modules\Invoices\Infrastructure\EventSubscribers\InvoiceApprovalEventSubscriber;
use App\Modules\Invoices\Infrastructure\Repositories\InvoiceRepository;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

class InvoicesServiceProvider extends ServiceProvider implements DeferrableProvider
{
    public function boot(): void
    {
        Event::subscribe(InvoiceApprovalEventSubscriber::class);
    }

    public function register(): void
    {
        $this->app->scoped(InvoiceRepositoryInterface::class, InvoiceRepository::class);
    }

    public function provides(): array
    {
        return [
            InvoiceRepositoryInterface::class,
        ];
    }
}
