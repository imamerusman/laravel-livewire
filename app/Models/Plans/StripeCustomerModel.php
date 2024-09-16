<?php

namespace App\Models\Plans;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class StripeCustomerModel extends Model
{
    use HasUuids;

    protected $table = 'stripe_customers';
    protected $guarded = [];
    protected $dates = [];

    public function model(): MorphTo
    {
        return $this->morphTo();
    }
}
