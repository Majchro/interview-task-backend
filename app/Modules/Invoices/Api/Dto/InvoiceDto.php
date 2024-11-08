<?php

declare(strict_types=1);

namespace App\Modules\Invoices\Api\Dto;

use App\Modules\Invoices\Domain\Entities\Invoice;
use App\Modules\Invoices\Domain\ValueObjects\Money;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class InvoiceDto
{
    public Money $totalPrice;
    public string $number;
    public Carbon $date;
    public Carbon $dueDate;
    public array $company;
    public array $billedCompany;
    public Collection $products;

    public function __construct(Invoice $invoice)
    {
        $this->number = $invoice->number;
        $this->date = $invoice->date;
        $this->dueDate = $invoice->dueDate;
        $this->company = [
            'name' => $invoice->company->name,
            'streetAddress' => $invoice->company->street,
            'city' => $invoice->company->city,
            'zip' => $invoice->company->zip,
            'phone' => $invoice->company->phone,
        ];
        $this->billedCompany = [
            'name' => $invoice->company->name,
            'streetAddress' => $invoice->company->street,
            'city' => $invoice->company->city,
            'zip' => $invoice->company->zip,
            'phone' => $invoice->company->phone,
            'email' => $invoice->company->email,
        ];
        $this->products = $invoice->products->map(function ($product) {
            $total = $product->unitPrice->amount * $product->quantity;

            return [
                'name' => $product->name,
                'quantity' => $product->quantity,
                'unitPrice' => $product->unitPrice,
                'total' => new Money($total, currency: $product->unitPrice->currency),
            ];
        });

        $this->totalPrice = $this->calculateTotalPrice();
    }

    private function calculateTotalPrice(): Money
    {
        if ($this->products->isEmpty()) {
            return new Money(0, currency: 'USD');
        }

        $value = $this->products->sum(fn ($product) => $product['total']->amount);

        return new Money($value, currency: $this->products->first()['unitPrice']->currency);
    }
}
