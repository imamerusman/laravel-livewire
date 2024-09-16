<?php

namespace App\Models\Plans;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Plan extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'price',
        'currency',
        'duration',
    ];

    public function features(): BelongsToMany
    {
        return $this->belongsToMany(Feature::class);
    }

    protected function displayName(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->name . ' - '
                . $this->price . ' '
                . strtoupper($this->currency)
                . ' / ' . $this->duration . ' days',
        );
    }
}
