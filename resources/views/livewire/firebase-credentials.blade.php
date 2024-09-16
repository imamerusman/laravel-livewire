<x-filament-breezy::grid-section md=2
                                 title="Add Your firebase Credentials"
                                 description="Add your firebase credentials to your profile.">
    <div>
        @if(auth()->user()->has_firebase_credentials)
            {{ $this->table }}
        @else
            <x-filament::card>
                <form wire:submit.prevent="updateServiceAccountFile" class="space-y-6">
                    <div class="text-sm text-gray-500">
                        You can get your firebase credentials from your firebase console.
                        <ul class="list-disc list-inside">
                            <li class="mb-2">
                                <a href="https://console.firebase.google.com/project/_/settings/serviceaccounts/adminsdk"
                                   class="font-medium text-gray-900 underline dot dark:text-gray-100 hover:text-gray-600 dark:hover:text-gray-200"
                                   target="_blank" rel="noopener noreferrer"
                                >
                                    Click here
                                </a>
                                To get your firebase credentials.
                            </li>
                            <li class="mb-2">
                                Then Click on <span class="cursor-pointer font-medium bg-[#1a73e8] text-white rounded-lg p-2 dark:text-gray-100 hover:text-white dark:hover:text-gray-200">Generate new private key</span>
                            </li>
                            <li class="mb-2">
                                Upload the downloaded file here.
                            </li>
                        </ul>

                    </div>

                    {{ $this->form }}

                    <div class="text-right">
                        <x-filament::button type="submit" form="submit" class="align-right">
                            Submit!
                        </x-filament::button>
                    </div>
                </form>
            </x-filament::card>
        @endif
    </div>
</x-filament-breezy::grid-section>
