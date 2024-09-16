<?php

namespace App\Relations;

use App\Models\Customer;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait HasCustomers
{
    public function customers(): HasMany
    {
        return $this->hasMany(Customer::class);
    }
}
