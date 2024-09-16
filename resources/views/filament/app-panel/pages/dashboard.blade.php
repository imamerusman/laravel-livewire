<?php

use App\Filament\AppPanel\Widgets\CartStatesOverview;
use App\Filament\AppPanel\Widgets\CheckoutAnalytics;
use App\Filament\AppPanel\Widgets\MostSearchProductAnalytics;
use App\Filament\AppPanel\Widgets\MostSellingProductAnalytics;
use App\Filament\AppPanel\Widgets\MostViewedProductAnalytics;
use App\Filament\AppPanel\Widgets\NotificationAnalytics;
use App\Filament\AppPanel\Pages\AppDevelopmentStatus;
use App\Livewire\ReadCountAnalytics;
use App\Models\AppDevOrder;

$user = auth()->user()->activeSubscription();
?>
<x-locked-page :locked="!filled($user)">
    <x-filament-panels::page class="fi-dashboard-page">
        <style>
            .gap-y-8 {
                row-gap: 1rem !important;
            }
        </style>
        @if(!$firebaseCredentialsConfigured)
            <x-dismisable-alert
                message="Firebase credentials not configured. Please configure them in your profile.
            <a class='underline hover:no-underline' href='{{url('app/my-profile')}}'>Click here </a>"
                :link="url('app/my-profile')"
                type="error"
            />
        @endif
        @if(!$personalAccessTokenExists)
            <x-dismisable-alert
                message="You will need to create a personal access token to connect your app with your panel.
        <a class='underline hover:no-underline' href='{{url('app/my-profile')}}'>Click here </a> "
                :link="url('app/my-profile')"
                type="warning"
            />
        @endif
        @if(isset($appDevOrder) && filled($appDevOrder->started_at) && $appDevOrder->status === AppDevOrder::IN_PROGRESS)
            <x-dismisable-alert
                message="Your Application will be ready in: {{ 15 - $appDevOrder->created_at->diffInDays(now()) }} days."
                type="secondary"
            />
        @endif
        @if(isset($appDevOrder) && $appDevOrder->status === AppDevOrder::PENDING)
            <x-dismisable-alert
                message="Your Application Development Order Ready to be submitted. Click to submit.
            <a class='underline hover:no-underline' href='{{AppDevelopmentStatus::getUrl()}}'>Click here </a>"
                :link="AppDevelopmentStatus::getUrl()"
                type="secondary"
            />
        @endif
        @if(!$stripeConfigured)
        <x-dismisable-alert
            message="You can add a Payment Method in your profile to auto renew your subscription."
            :link="url('app/my-profile')"
            type="secondary"
        />
    @endif<x-filament-widgets::widgets
            :columns="$this->getColumns()"
            :data="$this->getWidgetData()"
            :widgets="$this->getVisibleWidgets()"
        />
        <div class="grid lg:grid-cols-2 md:grid-cols-2 grid-cols-1 gap-x-6 gap-y-8">

            <x-filament::card>
                <h4 class="py-2 px-2 font-bold">
                    Checkout Cart Abandonment Notification Analytics
                </h4>
                <div class="pt-12">
                    @livewire(CartStatesOverview::class)
                </div>
            </x-filament::card>
            <div>
                @livewire(NotificationAnalytics::class)
            </div>
            <div>
                @livewire(MostSearchProductAnalytics::class)
            </div>
            <x-locked-component :locked="$user === 'Basic'" :title="'🔒 Best-Selling Product Analysis'">
                @livewire(MostSellingProductAnalytics::class)
            </x-locked-component>
            <x-locked-component :locked="$user === 'Basic'" :title="'🔒 Popular Product Analytics'">
                @livewire(MostViewedProductAnalytics::class)
            </x-locked-component>
        </div>
    </x-filament-panels::page>
</x-locked-page>
