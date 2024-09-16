<x-filament-panels::page>
    <div class="flex flex-col lg:flex-row ">
        <div class="p-2">
            {{$this->table}}
        </div>
        <div class="w-full p-2">
            <x-filament::card class="lg:flex-grow">
                @if(filled($conversation))
                    <x-chat :conversation="$conversation"/>
                @else
                    <div class="flex items-center justify-center h-full">
                        <p class="mt-4 text-gray-400">Select a conversation to start messaging</p>
                    </div>
                @endif
            </x-filament::card>
        </div>
    </div>
</x-filament-panels::page>
