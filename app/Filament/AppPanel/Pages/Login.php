<?php

namespace App\Filament\AppPanel\Pages;

use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Illuminate\Http\RedirectResponse;
use Livewire\Attributes\Rule;
use Livewire\Features\SupportRedirects\Redirector;

class Login extends \Filament\Pages\Auth\Login implements HasActions
{
    use InteractsWithActions;

    #[Rule('required', 'string', 'max:255' )]
    public string $shop_url = "";

    protected static string $view = 'filament.app-panel.pages.auth.login';

    public function loginWithShopify(): RedirectResponse|Redirector
    {
        return redirect()->route('shopify.install', ['shop' => $this->shop_url]);
    }
}
