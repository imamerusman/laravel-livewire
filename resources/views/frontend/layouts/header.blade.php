<!-- ====== Navbar Section Start -->
<header
    x-data="
      {
        navbarOpen: false,
      }
    "
    class="absolute z-50 w-full left-0 top-0"
>
    <div class="container xl:px-24 lg:px-24">
        <div class="flex -mx-4 items-center justify-between">
            <div class="px-4 w-60 max-w-full">
                <a href="{{route('index')}}" class="w-full block py-5">
                    <img
                        src="{{asset('logo/black.svg')}}"
                        alt="logo"
                        height="50px"
                        class="w-full"
                        id="logo"
                    />

                </a>
            </div>
            <div class="flex px-4 justify-between items-center w-full">
                <div>
                    <button
                        @click="navbarOpen = !navbarOpen"
                        :class="navbarOpen && 'navbarTogglerActive'"
                        id="navbarToggler"
                        class="
                  block
                  absolute
                  right-4
                  top-1/2
                  -translate-y-1/2
                  lg:hidden
                  focus:ring-2
                  ring-main
                  px-3
                  py-[6px]
                  rounded-lg
                "
                    >
                <span
                    class="relative w-[30px] h-[2px] my-[6px] block bg-body-color"
                ></span>
                        <span
                            class="relative w-[30px] h-[2px] my-[6px] block bg-body-color"
                        ></span>
                        <span
                            class="relative w-[30px] h-[2px] my-[6px] block bg-body-color"
                        ></span>
                    </button>
                    <nav
                        :class="!navbarOpen && 'hidden' "
                        id="navbarCollapse"
                        class=" absolute py-5 px-6 bg-main lg:bg-transparent shadow rounded-lg max-w-[250px] w-full lg:max-w-full lg:w-full  right-4 top-full  lg:block lg:static lg:shadow-none z-40 ">
                        <ul class="block lg:flex">
                            @php
                                $navItems = [
                                    ['route' => 'index', 'label' => 'Home'],
                                    ['route' => 'pricing', 'label' => 'Pricing'],
                                    ['route' => 'about', 'label' => 'About'],
                                    ['route' => 'blog', 'label' => 'Blog'],
                                    ['route' => 'contact-us', 'label' => 'Contact'],
                                ];
                            @endphp

                            @foreach ($navItems as $item)
                                <li>
                                    <a href="{{ $item['route'] === 'index' ? route('index') :
                        ($item['route'] === 'blog' ? route('blog') :
                        ($item['route'] === 'pricing' ? route('pricing') :
                        ($item['route'] === 'about' ? route('about') :
                        ($item['route'] === 'contact-us' ? route('contact-us') :
                        'javascript:void(0)'
                        ))))
                        }}"
                                       class="text-base font-medium py-2 lg:inline-flex flex lg:ml-12
                      {{ Request::routeIs($item['route']) ? 'border border-main text-main p-2 rounded-2xl ' : '' }}
                      {{ Request::routeIs('index') && $item['route'] !== 'index' ? 'text-textColor hover:text-main' : '' }}
                      {{ in_array(Request::route()->getName(), ['ai-notification', 'ar-feature','design-option','live-selling','splash-screen','localization','about','contact-us']) ? 'text-white hover:text-textColor' : '' }}"
                                    >
                                        {{ $item['label'] }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>


                    </nav>
                </div>
                <div class="sm:flex justify-end hidden  lg:pr-0" x-data="{modalOpen: false}">
                    <a href="#">
                        @if(auth()->check() && !auth()->user()->hasSubscriptions() && empty(auth()->user()->activeSubscription()))
                            <button @click="modalOpen = !modalOpen" class="bg-transparent text-sm py-2 px-4 border rounded
                            @if(Request::routeIs('index', 'blog', 'blog-details' ,'pricing'))
                                hover:bg-main text-textColor hover:text-textColor border-textColor hover:border-white
                            @elseif(Request::routeIs('ai-notification', 'ar-feature','design-option','live-selling','splash-screen','localization','about','contact-us'))
                                hover:bg-main text-white hover:text-textColor border-white hover:border-textColor
                            @endif"
                            >
                                14 Days Free Trial
                            </button>
                        @endif
                        <section class="hidden" x-bind:class="{'hidden' : !modalOpen}">
                            <div
                                x-show="modalOpen"
                                x-transition:enter="transition ease-out duration-400 transform opacity-0"
                                x-transition:enter-start="opacity-0 translate-y-4"
                                x-transition:enter-end="opacity-100 translate-y-0"
                                x-transition
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
                                            <h3 class="mb-4 text-2xl  font-medium text-gray-900">
                                                Login with your Shopify store
                                            </h3>
                                            <p class="text-center text-black">
                                                Please enter your Shopify store URL to login with Appify
                                            </p>
                                            <form class="space-y-6" action="{{route('shop-url')}}">
                                                <div>
                                                    <input type="text" wire:model="shop_url" name="shop_url" id="email"
                                                           class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5  "
                                                           placeholder="your-shopify-url.myshopify.com" value=""
                                                           required>
                                                </div>
                                                <button type="submit"
                                                        class="text-white bg-main px-2 py-2 rounded-lg">
                                                    Add To My Shop
                                                </button>

                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>
                    </a>
                </div>
                @if(!Auth::check())
                    <div class="sm:flex justify-end pr-16 lg:pr-0">
                        <div class="flex">
                            <a href="{{url('app/login')}}" class="
                        @if(Request::routeIs('blog', 'blog-details' , 'pricing'))
                                text:base font-medium  py-2 lg:inline-flex flex lg:ml-12
                            @else
                                lg:text-white xl:text:white 2xl:text-black md:text-black sm:text-black font-medium  py-2 lg:inline-flex flex lg:ml-12
                            @endif
                        ">
                                <span class="mt-1">Sign In</span>
                            </a>
                                <a href="#" class="@if(Request::routeIs('blog', 'blog-details' , 'pricing'))
                                text:base font-medium  py-2 lg:inline-flex flex lg:ml-12
                            @else
                                lg:text-white xl:text:white md:text-black sm:text-black font-medium  py-2 lg:inline-flex flex lg:ml-12
                            @endif">
                                <section x-data="{modalOpen: false}">
                                    <x-filament::button @click="modalOpen = !modalOpen" class="w-full">
                                        <div class="flex flex-row ">
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

                                                            <button type="submit">
                                                                <div class="flex flex-row bg-main px-5 py-2 rounded-lg">
                                                                    Add To My Shop
                                                                </div>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </section>
                            </a>
                        </div>
                    </div>

                @else
                    <div class="sm:flex justify-end pr-16 lg:pr-0">
                        <div class="flex">
                            <a href="{{url('app')}}" class="
                        @if(Request::routeIs('blog', 'blog-details' , 'pricing'))
                                text:base font-medium  py-2 lg:inline-flex flex lg:ml-12
                            @else
                                lg:text-white xl:text:white md:text-black sm:text-black font-medium  py-2 lg:inline-flex flex lg:ml-12
                            @endif
                        ">
                                Dashboard
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</header>
<!-- ====== Navbar Section End -->
<script>
    // Get the logo element
    const logo = document.getElementById('logo');

    // Get the current route (you need to replace this with the actual way you retrieve the route)
    //get current route
    const currentRoute = "{{ Route::currentRouteName() }}";

    // Check the route and change the logo accordingly
    if (currentRoute === 'index' || currentRoute === 'blog' || currentRoute === 'blog-details' || currentRoute === 'pricing') {
        logo.src = "{{asset('logo/blue.svg')}}";
    } else {
        logo.src = "{{asset('logo/white.svg')}}";
    }

    document.getElementById('urlInput')?.addEventListener('input', function () {
        let inputValue = this.value;

        const prefixesToRemove = ['http://', 'https://', 'www.','https://www.', 'http://www.'];
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
