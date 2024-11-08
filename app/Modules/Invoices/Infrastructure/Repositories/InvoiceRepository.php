<?php

declare(strict_types=1);

namespace App\Modules\Invoices\Infrastructure\Repositories;

use App\Modules\Invoices\Application\Mappers\InvoiceMapper;
use App\Modules\Invoices\Domain\Entities\Invoice;
use App\Modules\Invoices\Infrastructure\Models\Invoice as InvoiceModel;
use App\Modules\Invoices\Domain\Repositories\InvoiceRepositoryInterface;

class InvoiceRepository implements InvoiceRepositoryInterface
{
    public function findById(string $id): Invoice
    {
        $model = InvoiceModel::findOrFail($id);
        return InvoiceMapper::fromEloquent($model);
    }

    public function save(Invoice $invoice): Invoice
    {
        $model = InvoiceMapper::toEloquent($invoice);
        $model->save();

        return InvoiceMapper::fromEloquent($model);
    }
}
