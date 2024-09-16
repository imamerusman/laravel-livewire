@php($expireSubscription = \Carbon\Carbon::parse(auth()->user()->lastSubscription()->expires_on)->format('d-m-Y'))
<x-filament-breezy::grid-section md=2
                                 title="Your Current Plan"
                                 description="You Can Upgrade your plan or cancel anytime.">
    <x-filament::card>
        @vite(['resources/css/app.css'])
        @if(!Auth::user()->activeSubscription())
            <div class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400"
                 role="alert">
                <span class="font-medium">Alert!</span>
                <span class="block sm:inline">Your subscription is Cancelled. You can use the app features till
                {{$expireSubscription}}.</span>
            </div>
        @endif
        <div class=" w-full rounded-lg shadow-lg my-5 dark:shadow-blue-900">
            <div class="flex flex-row items-center  px-6 py-4">
                <div class="flex flex-row">
                    <h2 class="font-bold text-xl dark:text-gray-100">
                        Current Plan :
                        {{Auth::user()->activeSubscription()->plan->name
                          ?? Auth::user()->lastSubscription()->plan->name}}
                    </h2>
                </div>
            </div>
            <div class="flex flex-col items-center md:items-start bg-gray-100 px-6 py-3 rounded-lg dark:bg-gray-700">
                <h2 class="text-black dark:text-white font-bold text-2xl mb-2">
                    ${{
                    Auth::user()->activeSubscription() !== null
                    ? Auth::user()->activeSubscription() / 100
                    : Auth::user()->lastSubscription()?->charging_price / 100
                     }}
                    @if(Auth::user()->activeSubscription()?->plan->duration === 30 || Auth::user()->lastSubscription()?->plan->duration === 30)
                        / Monthly
                    @else
                        / Yearly
                    @endif
                </h2>
                <p class="text-gray-600 dark:text-white">Access to
                    {{
                        Auth::user()->activeSubscription() !== null
                    ? Auth::user()->activeSubscription()
                    : Auth::user()->lastSubscription()
                }}
                    features</p>
            </div>
        </div>
        <div class="flex flex-row justify-end space-x-4">
            <x-filament::button wire:click="cancelSubscription" color="danger" class="align-right">
                <span>Cancel</span>
            </x-filament::button>
            <x-filament::button
                tag="a"
                href="{{route('pricing')}}"
                target="_blank"
                class="align-right">
                <span>Update</span>
            </x-filament::button>
        </div>
    </x-filament::card>
</x-filament-breezy::grid-section>
