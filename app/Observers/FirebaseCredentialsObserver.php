<?php

namespace App\Observers;

use App\Models\FirebaseCredential;

class FirebaseCredentialsObserver
{
    public function retrieved(FirebaseCredential $firebaseCredential): void
    {
        $firebaseCredential->last_used_at = now();
    }
}
