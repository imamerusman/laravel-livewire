<x-filament-panels::page
    @class([
        'fi-resource-edit-record-page',
        'fi-resource-' . str_replace('/', '-', $this->getResource()::getSlug()),
        'fi-resource-record-' . $record->getKey(),
    ])
>
    @capture($form)
    <x-filament-panels::form
        :wire:key="$this->getId() . '.forms.' . $this->getFormStatePath()"
        wire:submit="save"
    >
        {{ $this->form }}

        <x-filament-panels::form.actions
            :actions="$this->getCachedFormActions()"
            :full-width="$this->hasFullWidthFormActions()"
        />
    </x-filament-panels::form>
    @endcapture

    @php
        $relationManagers = $this->getRelationManagers();
    @endphp

    @if ((! $this->hasCombinedRelationManagerTabsWithContent()) || (! count($relationManagers)))
        {{ $form() }}
    @endif

    @if (count($relationManagers))
        <x-filament-panels::resources.relation-managers
            :active-locale="$activeLocale ?? null"
            :active-manager="$activeRelationManager"
            :content-tab-label="$this->getContentTabLabel()"
            :managers="$relationManagers"
            :owner-record="$record"
            :page-class="static::class"
        >
            @if ($this->hasCombinedRelationManagerTabsWithContent())
                <x-slot name="content">
                    {{ $form() }}
                </x-slot>
            @endif
        </x-filament-panels::resources.relation-managers>
    @endif
    {{--{{$this->table}}--}}

    @php($recentProduct = "")
    <div>
        <div class=" card-body bg-white py-5 pb-7 px-5 text-lg">
            <h4 class="card-title py-3 ml-3 font-bold pb-3">User Schedule Notification
                Click and Sale Rate  </h4>
            <div>
                <livewire:read-count-schedule-analytics :model="$this->record"/>
            </div>
        </div>
    </div>

</x-filament-panels::page>
