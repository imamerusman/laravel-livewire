<x-filament-panels::page.simple>
    @if (filament()->hasRegistration())
        <x-slot name="subheading">
            {{ __('filament-panels::pages/auth/login.actions.register.before') }}

            {{ $this->registerAction }}
        </x-slot>
    @endif

    <x-filament-panels::form wire:submit="authenticate">
        {{ $this->form }}

        <x-filament-panels::form.actions
            :actions="$this->getCachedFormActions()"
            :full-width="$this->hasFullWidthFormActions()"
        />
    </x-filament-panels::form>
    <!-- Main modal -->
    <section x-data="{modalOpen: false}">
        <x-filament::button @click="modalOpen = !modalOpen" class="w-full">
            <div class="flex flex-row">
                Get Started
            </div>
        </x-filament::button>
        <div class="hidden" x-bind:class="{'hidden' : !modalOpen}">
        <div
            x-show="modalOpen"
            x-transition:enter="transition ease-out duration-300 transform opacity-0"
            x-transition:enter-start="opacity-0 translate-y-4"
            x-transition:enter-end="opacity-100 translate-y-0"
            class="
          bg-black bg-opacity-90
          fixed
          top-0
          left-0
          w-full
          min-h-screen
          h-full
          flex
          items-center
          justify-center
          px-4
          py-5
        "
        >
            <div
                @click.outside="modalOpen = false"
                class="
            w-full
            max-w-[570px]
            rounded-[20px]
            bg-white
            py-12
            px-8
            md:py-[60px] md:px-[70px]
            text-center
          "
            >

                <div class="relative bg-white rounded-lg ">
                    <div class="px-6 py-6 lg:px-8">
                        <h3 class="mb-2 text-xl   font-bold text-gray-900">
                            Login with your Shopify store
                        </h3>
                        <p class="text-sm text-black text-left mb-6">
                            Please enter your Shopify store URL to login with Appify
                        </p>
                        <form class="space-y-6" action="{{route('shop-url')}}">
                            <div>
                                {{--<input type="text" name="shop_url" wire:model="shop_url"
                                       id="urlInput"
                                       class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5  "
                                       placeholder="your-shopify-url.myshopify.com"
                                       value="" required>--}}

                                <div class="grid gap-y-2">
                                    <div class="fi-input-wrp flex rounded-lg shadow-sm border transition duration-75 bg-white focus-within:ring-2  ring-main overflow-hidden">
                                        <div class="items-center gap-x-3 ps-3 flex border-e border-gray-200 pe-3">
                                                                                 <span class="fi-input-wrp-label whitespace-nowrap text-sm text-gray-500">
                                                                                    https://
                                                                                </span>
                                        </div>
                                        <div class="min-w-0 flex-1">
                                            <input  class="fi-input block w-full border-none py-1.5 text-black  outline-none transition duration-75  focus-visible:ring-0 disabled:text-gray-500 disabled:[-webkit-text-fill-color:theme(colors.gray.500)] disabled:placeholder:[-webkit-text-fill-color:theme(colors.gray.400)]  sm:text-sm sm:leading-6" id="urlInput" required="required" type="text" name="shop_url" placeholder="your-shopify-url.myshopify.com" value wire:model="shop_url">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <script>
                                document.getElementById('urlInput')?.addEventListener('input', function () {
                                    let inputValue = this.value;

                                    const prefixesToRemove = ['http://', 'https://', 'www.', 'https://www.', 'http://www.'];
                                    // Remove the 'https://' prefix if it exists
                                    prefixesToRemove.forEach(prefix => {
                                        if (inputValue.startsWith(prefix)) {
                                            inputValue = inputValue.slice(prefix.length);
                                        }
                                    });
                                    // Update the input field with the modified value
                                    this.value = inputValue;
                                });
                            </script>
                            <x-filament::button type="submit" class="bg-main hover:bg-mainDark">
                                <div class="flex flex-row ">
                                    Add To My Shop
                                </div>
                            </x-filament::button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        </div>
    </section>
    @vite(['resources/css/app.css'])
</x-filament-panels::page.simple>


