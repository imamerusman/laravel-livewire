@props([
    'type' => 'info',
    'message' => 'This is a dismissible alert',
    'link' => '#',
    'dismissible' => true,
    'timeout' => 0,
])
<div x-data="{
        show: true,
        timeout: null,
        dismiss() {
            clearTimeout(this.timeout);
            this.show = false;
        },
        init() {
            if ({{ $timeout }} > 0) {
                this.timeout = setTimeout(() => {
                    this.dismiss();
                }, {{ $timeout }});
            }
        }
    }"
     x-init="init()"
     x-show="show"
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0 transform translate-y-2"
     x-transition:enter-end="opacity-100 transform translate-y-0"
     x-transition:leave="transition ease-in duration-300"
     x-transition:leave-start="opacity-100 transform translate-y-0"
     x-transition:leave-end="opacity-0 transform translate-y-2"

    {{ $attributes->class(['pointer-events-none']) }}>
    <div
            @class([
                'bg-blue-500' => $type === 'info',
                'bg-green-500' => $type === 'success',
                'bg-gray-500' => $type === 'secondary',
                'bg-yellow-500' => $type === 'warning',
                'bg-red-500' => $type === 'error',
                'pointer-events-auto flex items-center justify-between gap-x-6 px-6 py-2.5 sm:rounded-xl sm:py-3 sm:pl-4 sm:pr-3.5'
            ])>
        <p class="text-sm leading-6 text-white flex flex-row space-x-2">
            @switch($type)
                @case('info')
                    <x-heroicon-o-information-circle class="w-6 h-6" />
                    @break
                @case('success')
                    <x-heroicon-o-check-circle class="w-6 h-6" />
                    @break
                @case('secondary')
                    <x-heroicon-o-information-circle class="w-6 h-6" />
                    @break
                @case('warning')
                    <x-heroicon-o-exclamation-triangle class="w-6 h-6" />
                    @break
                @case('error')
                    <x-heroicon-o-x-circle class="w-6 h-6" />
                    @break
            @endswitch
                <strong class="font-semibold">
                    {!! $message !!}
                </strong>
        </p>
        @if($dismissible)
            <button type="button"
                    @click="dismiss()"
                    class="-m-3 flex-none p-3 focus-visible:outline-offset-[-4px]">
                <span class="sr-only">Dismiss</span>
                <svg class="h-5 w-5 text-white" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                    <path d="M6.28 5.22a.75.75 0 00-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 101.06 1.06L10 11.06l3.72 3.72a.75.75 0 101.06-1.06L11.06 10l3.72-3.72a.75.75 0 00-1.06-1.06L10 8.94 6.28 5.22z"/>
                </svg>
            </button>
        @endif
    </div>
</div>
