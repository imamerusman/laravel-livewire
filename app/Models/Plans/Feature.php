<?php

namespace App\Models\Plans;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Feature extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'name',
        'code',
        'description',
        'type',
        'limit',
    ];

    public function plans(): BelongsToMany
    {
        return $this->belongsToMany(Plan::class);
    }

    public function scopeCode($query, string $code)
    {
        return $query->where('code', $code);
    }

    public function scopeLimited($query)
    {
        return $query->where('type', 'limit');
    }

    public function scopeFeature($query)
    {
        return $query->where('type', 'feature');
    }

    public function isUnlimited(): bool
    {
        return (bool) ($this->type == 'limit' && $this->limit < 0);
    }
}
