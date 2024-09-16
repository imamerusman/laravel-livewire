<x-filament-widgets::widget>
    <x-filament::section>
        @vite(['resources/css/app.css'])
        <div class=" w-full rounded-lg shadow-lg mt-0 dark:shadow-blue-900">
            <div class="flex flex-row items-center  px-6 py-4">
                <div class="flex flex-row">
                    <h2 class="font-bold text-xl dark:text-gray-100">Current Plan :  {{Auth::user()->activeSubscription()}}</h2>
                </div>
            </div>
            <div class="flex flex-col items-center md:items-start bg-gray-100 px-6 py-3 rounded-lg dark:bg-gray-700">
                <h2 class="text-black dark:text-white font-bold text-2xl mb-2">${{Auth::user()->activeSubscription()}}/mo</h2>
                <p class="text-gray-600 dark:text-white">Access to {{Auth::user()->activeSubscription()}} features</p>
            </div>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
