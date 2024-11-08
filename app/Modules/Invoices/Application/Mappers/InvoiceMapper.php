<?php

namespace App\Modules\Invoices\Application\Mappers;

use App\Domain\Enums\StatusEnum;
use App\Modules\Invoices\Domain\Entities\Company;
use App\Modules\Invoices\Domain\Entities\Invoice;
use App\Modules\Invoices\Domain\ValueObjects\InvoiceProduct;
use App\Modules\Invoices\Domain\ValueObjects\Money;
use App\Modules\Invoices\Infrastructure\Models\Invoice as InvoiceModel;
use Ramsey\Uuid\Uuid;

class InvoiceMapper
{
    public static function fromEloquent(InvoiceModel $model): Invoice
    {
        $company = new Company(
            id: Uuid::fromString($model->company->id),
            name: $model->company->name,
            street: $model->company->street,
            city: $model->company->city,
            zip: $model->company->zip,
            phone: $model->company->phone,
            email: $model->company->email,
        );

        $products = $model->products->map(fn ($product) => new InvoiceProduct(
            name: $product->name,
            unitPrice: new Money($product->price, $product->currency),
            quantity: $product->pivot->quantity,
        ));

        return new Invoice(
            id: Uuid::fromString($model->id),
            number: $model->number,
            date: $model->date,
            dueDate: $model->due_date,
            company: $company,
            status: StatusEnum::tryFrom($model->status),
            products: $products,
        );
    }

    public static function toEloquent(Invoice $entity): InvoiceModel
    {
        $model = new InvoiceModel();
        if ($entity->id) {
            $model = InvoiceModel::findOrFail($entity->id);
        }
        $model->number = $entity->number;
        $model->date = $entity->date;
        $model->due_date = $entity->dueDate;
        $model->company_id = $entity->company->id;
        $model->status = $entity->status->value;

        return $model;
    }
}
