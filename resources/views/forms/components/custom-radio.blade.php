<x-dynamic-component
        :component="$getFieldWrapperView()"
        :field="$field"
>
    <div x-data="{ state: $wire.entangle('{{ $getStatePath() }}') }"
         class="flex flex-row space-x-2"
    >
        <!-- Interact with the `state` property in Alpine.js -->
        @foreach ($getOptions() as $value => $label)
            <div @class([
                'gap-2' => true,
            ])>
                <div class="flex items-center">
                    <label for="{{ $getId() }}-{{ $value }}"
                           @click="state = '{{$value}}'"
                           :class="{
                                    'border-primary-600 ring-2 ring-primary-600' : state === '{{$value}}',
                                    'border-gray-300' : state !== '{{$value}}'
                                }"
                           class="w-full cursor-pointer bg-white rounded-lg border grid grid-cols-5 hover:text-gray-600 hover:bg-gray-100 shadow-sm overflow-hidden dark:bg-gray-800 dark:hover:bg-gray-700">
                        <input
                                name="{{ $getId() }}"
                                id="{{ $getId() }}-{{ $value }}"
                                type="radio"
                                value="{{ $value }}"
                                dusk="filament.forms.{{ $getStatePath() }}"
                                class="opacity-0 absolute"
                        {{ $applyStateBindingModifiers('wire:model') }}="{{ $getStatePath() }}"
                        {!! ($isDisabled || $isOptionDisabled($value, $label)) ? 'disabled' : null !!}
                        />
                        <div @class([
                            'font-medium flex justify-between items-center p-5 col-span-4',
                            'text-gray-700' => ! $errors->has($getStatePath()),
                            'dark:text-gray-200 dark:hover:text-gray-300 dark:border-gray-700' => (! $errors->has($getStatePath())) && config('forms.dark_mode'),
                            'text-danger-600' => $errors->has($getStatePath()),
                        ])>
                            <div class="block">
                                <img src="https://flowbite.s3.amazonaws.com/blocks/marketing-ui/hero/mockup-1-light.png" class="dark:hidden w-[272px] h-[572px]" alt="">
                                <img src="https://flowbite.s3.amazonaws.com/blocks/marketing-ui/hero/mockup-1-dark.png" class="hidden dark:block w-[272px] h-[572px]" alt="">
                            </div>
                        </div>
                    </label>
                </div>
            </div>
        @endforeach
    </div>
</x-dynamic-component>
