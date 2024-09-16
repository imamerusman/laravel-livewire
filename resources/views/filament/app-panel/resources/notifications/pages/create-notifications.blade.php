<x-filament-panels::page
    @class([
        'fi-resource-create-record-page',
        'fi-resource-' . str_replace('/', '-', $this->getResource()::getSlug()),
    ])
>
    <x-filament-panels::form
        :wire:key="$this->getId() . '.forms.' . $this->getFormStatePath()"
        wire:submit="create"
    >
        <div class="flex-row justify-center content-center text-center items-center mr-24">
            <x-filament::button wire:click="getAiResponse" class="w-20 py-2 px-2" type="button">
                <span wire:loading.remove wire:target="getAiResponse">Autofill</span>
            </x-filament::button>
        </div>
        {{ $this->form }}

        <x-filament-panels::form.actions
            :actions="$this->getCachedFormActions()"
            :full-width="$this->hasFullWidthFormActions()"
        />
    </x-filament-panels::form>
    <script>
        document.addEventListener('livewire:initialized', () => {
            const userTimezone = Intl.DateTimeFormat().resolvedOptions().timeZone;
            if (userTimezone) {
                @this.
                $call('setTimeZoneOnFormPropertyTimeZone', userTimezone)
            }
        })
    </script>
</x-filament-panels::page>
