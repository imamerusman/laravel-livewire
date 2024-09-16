<x-filament-breezy::grid-section md=2
                                 title="Your Current Billing"
                                 description="Your current billing information and subscription status.">
    @php($defaultPaymentMethod = auth()->user()->defaultPaymentMethod())
    <x-filament::card>
        <div>
            @if(filled($defaultPaymentMethod))
                <div class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                    <div class="flex flex-row justify-between">
                        <div>
                            <dt class="font-medium text-gray-500">
                                Default Payment Method
                            </dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                <span class="bg-gray-100 px-2 py-4 rounded-md text-sm font-medium text-gray-900 my-2">
                                    {{ucfirst($defaultPaymentMethod->card->brand)}} **** ****
                                **** {{$defaultPaymentMethod->card->last4}}
                                </span>
                            </dd>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <div class="flex flex-row justify-end space-x-4">

            @if(filled($defaultPaymentMethod))
                <x-filament::button
                    tag="a"
                    href="{{auth()->user()->billingPortalUrl(request()->fullUrl())}}"
                    target="_blank"
                    class="align-right">
                    <span>Manage</span>
                </x-filament::button>
            @else
                <x-filament::button class="align-right"
                                    tag="a"
                                    href="{{auth()->user()->billingPortalUrl(request()->fullUrl())}}"
                                    target="_blank">
                    <span>Add Payment Method</span>
                </x-filament::button>
            @endif
        </div>
    </x-filament::card>
</x-filament-breezy::grid-section>
