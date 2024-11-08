<?php

declare(strict_types=1);

namespace App\Modules\Invoices\Infrastructure\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'id',
        'name',
        'price',
        'currency'
    ];
}
