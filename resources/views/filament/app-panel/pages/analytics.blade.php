{{--@php use App\Filament\AppPanel\Widgets\AppTerminationEventsWidget; @endphp
<x-filament-panels::page>
    @livewire(AppTerminationEventsWidget::class)
</x-filament-panels::page>--}}
@php
    use App\Filament\AppPanel\Widgets\CartStatesOverview;use App\Filament\AppPanel\Widgets\CheckoutAnalytics;use App\Filament\AppPanel\Widgets\MostSearchProductAnalytics;use App\Filament\AppPanel\Widgets\MostSellingProductAnalytics;use App\Filament\AppPanel\Widgets\MostViewedProductAnalytics;use App\Filament\AppPanel\Widgets\NotificationAnalytics;use App\Livewire\ReadCountAnalytics;

    $activeSubscription = auth()->user()->activeSubscription();
@endphp
@vite(['resources/css/app.css'])
<x-filament-panels::page>
    {{--{{dd(Request::url() !== url('/app/analytics'))}}--}}
    <div class="grid lg:grid-cols-2 md:grid-cols-2 grid-cols-1 gap-x-6 gap-y-8">

        <div class="card-header">
            <div class="card-body bg-white py-5 pb-7 px-5 text-lg">
                <h4 class="card-title py-3 ml-3 font-bold pb-3">Checkout Cart Abandonment Notification Analytics </h4>
                @livewire(CartStatesOverview::class)
            </div>
        </div>
        <div>
            @livewire(NotificationAnalytics::class)
        </div>
     {{--   <div>
            @livewire(CheckoutAnalytics::class)
        </div>--}}
        <div>
            @livewire(MostSearchProductAnalytics::class)
        </div>
        <x-locked-component :locked="$activeSubscription === true" :title="'🔒 Best-Selling Product Analysis'">
            @livewire(MostSellingProductAnalytics::class)
        </x-locked-component>
        <x-locked-component :locked="$activeSubscription === true" :title="'🔒 Popular Product Analytics'">
            @livewire(MostViewedProductAnalytics::class)
        </x-locked-component>
    </div>

</x-filament-panels::page>
