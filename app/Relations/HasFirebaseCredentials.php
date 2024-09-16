<?php

namespace App\Relations;

use App\Models\FirebaseCredential;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\HasOne;

trait HasFirebaseCredentials
{
    public function firebaseCredentials(): HasOne
    {
        return $this->hasOne(FirebaseCredential::class);
    }

    protected function hasFirebaseCredentials(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->firebaseCredentials()->exists(),
        );
    }
}
