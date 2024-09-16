<?php

namespace App\Livewire\Dashboard;

use Livewire\Attributes\Lazy;
use Livewire\Component;

#[Lazy]
class BillingOverView extends Component
{
    public static function canView(): bool
    {
        return auth()->user()->hasSubscriptions();
    }

    public static function getSort(): string
    {
        return auth()->user()->id;
    }

    public function render()
    {
        return view('livewire.dashboard.billing-over-view');
    }
}
