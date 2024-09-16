@php use App\Livewire\ReadCountAnalytics;use App\Models\Notifications\NotificationTypes;use Filament\Facades\Filament; @endphp

@php
    $plan = auth()->user()->activeSubscription();
@endphp
<x-filament-panels::page
    :class="
        \Illuminate\Support\Arr::toCssClasses([
            'filament-resources-list-records-page',
            'filament-resources-' . str_replace('/', '-', $this->getResource()::getSlug()),
        ])
    "
>
    {{ Filament::renderHook('resource.pages.list-records.table.start') }}

    {{ $this->table }}

    <div class="relative py-4">
        <div class="absolute inset-0 flex items-center" aria-hidden="true">
            <div class="w-full border-t dark:border-gray-600"></div>
        </div>
    </div>

    <h1 class="font-bold text-2xl">
        {{__('Other Notifications')}}
    </h1>
    <x-filament::grid @class(["pt-6 gap-4 filament-breezy-grid-section"])>

        <x-filament::grid.column>
            <h3 @class(['text-lg font-medium filament-breezy-grid-title'])>Recover Abandoned Carts</h3>

            <p @class(['mt-1 text-sm text-gray-500 filament-breezy-grid-description'])>
                Prompt users who left items in their carts to complete their purchase, boosting conversions
            </p>
        </x-filament::grid.column>

        <x-filament::grid.column>
            <div class="grid lg:grid-cols-2 md:grid-cols-2 grid-cols-1 gap-x-6 gap-y-8">
                @php($abandonedCart = $this->getOtherNotificationModel(NotificationTypes::AbandonedCartReminder))
                <div>
                    <div class="@if($abandonedCart->enabled !== 0) hidden @endif card-body bg-white py-5 pb-7 px-5 text-lg">
                        <h4 class="card-title py-3 ml-3 font-bold pb-3">User Abandonment Notification
                            Click and Sale Rate </h4>
                        <div>
                            <livewire:read-count-analytics :model="$this->getOtherNotificationModel(NotificationTypes::AbandonedCartReminder)"/>
                        </div>
                    </div>
                </div>
                <div>
                    @vite(['resources/css/app.css'])
                    <x-locked-component :locked="$plan">
                        <form wire:submit.prevent="saveNotificationForm('{{NotificationTypes::AbandonedCartReminder}}')"
                              class="space-y-6">
                            {{ $this->AbandonedCartReminderForm }}
                            <div class="text-right">
                                <x-filament::button type="submit" form="submit" class="align-right">
                                    Submit!
                                </x-filament::button>
                            </div>
                        </form>
                    </x-locked-component>
                </div>
            </div>
        </x-filament::grid.column>
    </x-filament::grid>

    <x-filament::grid @class(["pt-6 gap-4 filament-breezy-grid-section"])>

        <x-filament::grid.column>
            <h3 @class(['text-lg font-medium filament-breezy-grid-title'])>Discover New Products</h3>

            <p @class(['mt-1 text-sm text-gray-500 filament-breezy-grid-description'])>
                Keep users engaged by recommending recently added products matching their interests.
            </p>
        </x-filament::grid.column>

        <x-filament::grid.column>
            <div class="grid lg:grid-cols-2 md:grid-cols-2 grid-cols-1 gap-x-6 gap-y-8">
                @php($recentProduct = $this->getOtherNotificationModel(NotificationTypes::RecentProductReminder))
                <div>
                    <div class="@if($recentProduct->enabled !== 0) hidden @endif card-body bg-white py-5 pb-7 px-5 text-lg">
                        <h4 class="card-title py-3 ml-3 font-bold pb-3">User Shopping time Notification
                            Click and Sale Rate  </h4>
                        <div>
                            <livewire:read-count-analytics :model="$this->getOtherNotificationModel(NotificationTypes::RecentProductReminder)"/>
                        </div>
                    </div>
                </div>
                <div>
                    @vite(['resources/css/app.css'])
                    <x-locked-component :locked="$plan">
                        <form wire:submit.prevent="saveNotificationForm('{{NotificationTypes::RecentProductReminder}}')"
                              class="space-y-6">
                            {{ $this->RecentProductReminderForm }}
                            <div class="text-right">
                                <x-filament::button type="submit" form="submit" class="align-right">
                                    Submit!
                                </x-filament::button>
                            </div>
                        </form>
                    </x-locked-component>
                </div>
            </div>
        </x-filament::grid.column>
    </x-filament::grid>

    <x-filament::grid @class(["pt-6 gap-4 filament-breezy-grid-section"])>

        <x-filament::grid.column>
            <h3 @class(['text-lg font-medium filament-breezy-grid-title'])>Shop Smartly!</h3>

            <p @class(['mt-1 text-sm text-gray-500 filament-breezy-grid-description'])>
                Help users make the most of their shopping time by suggesting personalized deals and items.
            </p>
        </x-filament::grid.column>

        <x-filament::grid.column>
            <div class="grid lg:grid-cols-2 md:grid-cols-2 grid-cols-1 gap-x-6 gap-y-8">
                @php($shoppingTimeReminder = $this->getOtherNotificationModel(NotificationTypes::ShoppingTimeReminder))
                <div>
                    <div class="@if($shoppingTimeReminder->enabled !== 0) hidden @endif card-body bg-white py-5 pb-7 px-5 text-lg">
                        <h4 class="card-title py-3 ml-3 font-bold pb-3">User Shopping Time Reminder Notification
                            Click and Sale Rate  </h4>
                        <div>
                            <livewire:read-count-analytics :model="$this->getOtherNotificationModel(NotificationTypes::ShoppingTimeReminder)"/>
                        </div>
                    </div>
                </div>
                <div>
                    @vite(['resources/css/app.css'])
                    <x-locked-component :locked="$plan">
                        <form wire:submit.prevent="saveNotificationForm('{{NotificationTypes::ShoppingTimeReminder}}')"
                              class="space-y-6">
                            {{ $this->ShoppingTimeReminderForm }}
                            <div class="text-right">
                                <x-filament::button type="submit" form="submit" class="align-right">
                                    Submit!
                                </x-filament::button>
                            </div>
                        </form>
                    </x-locked-component>
                </div>
            </div>
        </x-filament::grid.column>
    </x-filament::grid>

    <x-filament::grid @class(["pt-6 gap-4 filament-breezy-grid-section"])>

        <x-filament::grid.column>
            <h3 @class(['text-lg font-medium filament-breezy-grid-title'])>We Hate to See You Go</h3>

            <p @class(['mt-1 text-sm text-gray-500 filament-breezy-grid-description'])>
                Offer a friendly reminder about the benefits of staying connected with the app before users leave.
            </p>
        </x-filament::grid.column>

        <x-filament::grid.column>
            <div class="grid lg:grid-cols-2 md:grid-cols-2 grid-cols-1 gap-x-6 gap-y-8">
                @php($appTerminationReminder = $this->getOtherNotificationModel(NotificationTypes::AppTerminationReminder))
                <div>
                    <div class="@if($appTerminationReminder->enabled !== 0) hidden @endif card-body bg-white py-5 pb-7 px-5 text-lg">
                        <h4 class="card-title py-3 ml-3 font-bold pb-3">User App Termination Reminder Notification
                            Click and Sale Rate  </h4>
                        <div>
                            <livewire:read-count-analytics :model="$this->getOtherNotificationModel(NotificationTypes::AppTerminationReminder)"/>
                        </div>
                    </div>
                </div>
                <div>
                    @vite(['resources/css/app.css'])
                    <x-locked-component :locked="$plan">
                        <form wire:submit.prevent="saveNotificationForm('{{NotificationTypes::AppTerminationReminder}}')"
                              class="space-y-6">
                            {{ $this->AppTerminationReminderForm }}
                            <div class="text-right">
                                <x-filament::button type="submit" form="submit" class="align-right">
                                    Submit!
                                </x-filament::button>
                            </div>
                        </form>
                    </x-locked-component>
                </div>
            </div>
        </x-filament::grid.column>
    </x-filament::grid>

    {{ Filament::renderHook('resource.pages.list-records.table.end') }}
</x-filament-panels::page>
