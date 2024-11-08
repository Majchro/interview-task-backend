<?php

declare(strict_types=1);

namespace App\Modules\Invoices\Infrastructure\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Invoice extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'id',
        'number',
        'date',
        'due_date',
        'company_id',
        'status',
    ];

    protected $dates = [
        'date',
        'due_date',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'invoice_product_lines')->withPivot(('quantity'));
    }
}
