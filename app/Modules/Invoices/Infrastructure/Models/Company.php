<?php

declare(strict_types=1);

namespace App\Modules\Invoices\Infrastructure\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Company extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'id',
        'name',
        'street',
        'city',
        'zip',
        'phone',
        'email',
    ];

    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }
}
