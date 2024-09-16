@php use App\Models\SplashScreen; @endphp
<div>
    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"
    />
   {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.0.0/flowbite.min.js"></script>--}}
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

    <style>
        body {
            background-color: #e5e5e5;
        }

        [x-cloak] {
            display: none;
        }

        .style1 {
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
            width: 100px;
            height: 71px;
            background-color: transparent;
            border: none;
            cursor: pointer;
        }

        .style1::-webkit-color-swatch {
            border-radius: 15px;
            border: none;
        }

        .style1::-moz-color-swatch {
            border-radius: 15px;
            border: none;
        }

        .style2 {
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
            width: 74px;
            height: 70px;
            background-color: transparent;
            border: none;
            cursor: pointer;
        }

        .style2::-webkit-color-swatch {
            border-radius: 15px;
            border: none;
        }

        .style2::-moz-color-swatch {
            border-radius: 15px;
            border: none;
        }

        /*    ColorMarginInColorPicker define margin according to screen min max*/
        .ColorMarginInColorPicker {
            margin-left: 10%;
            margin-right: 10%;
        }

        /* Container for the carousel */
        .swiper-container {
            width: 100%;
            /*padding-top: 50px;*/
            /*padding-bottom: 50px;*/
        }

        .swiper-slide {
            background-position: center;
            background-size: cover;
            width: 320px;
            box-shadow: 0 15px 50px rgba(0,0,0,0.2);

        }

       /* .swiper-section {
            position: relative;
            width: 100%;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background: #2196f3;
            overflow: hidden;
        }*/
      /*  .testimonialBox{
            position: relative;
            width: 100%;
            padding: 40px;
            padding-top: 90px;
            color: #999;
        }*/
        .testimonialBox .details {
            display: flex;
            align-items: center;
            /*margin-top: 20px;*/
        }
        .swiper-container-3d .swiper-slide-shadow-left,
        .swiper-container-3d .swiper-slide-shadow-right
        {
            background-image: none;
        }



    </style>
    <div class=" h-screen">
        <x-notify::notify class="absolute z-60"/>
        <div x-data="app()" x-init="initScroll()">
            <div class="max-w-7xl mx-auto align-content-center px-4 py-10">

                <div class="hidden" x-bind:class="{'hidden' : step !== 'complete'}"
                     x-show.transition="step === 'complete'"
                     x-transition:enter.duration.500ms>
                    <div x-show="step === 'complete'" class="bg-white max-w-[1000px]
                                        px-6
                                        py-16
                                        sm:px-20 sm:py-10
                                        lg:flex
                                        justify-between
                                        shadow-card

                                        rounded-2xl mx-auto my-auto px-auto py-auto p-10 flex items-center shadow  text-center">
                        <div class="text-center">
                            <svg class="mb-4 h-20 w-20 text-green-500 mx-auto" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd"
                                      d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                      clip-rule="evenodd"/>
                            </svg>

                            <h2 class="text-2xl mb-4 text-gray-800 text-center font-bold">Steps Completed
                                Successfully</h2>

                            <div class="text-gray-600 mb-8">
                                Thank you. We have sent you an email to demo@demo.test. Please click the link in the
                                message
                                to activate your account.
                            </div>

                            <button
                                wire:click.prevent="goToPricing"
                                @click="step = 1"
                                class="text-xl w-[70%] font-bold border-none outline-none focus:invisible block mx-auto focus:outline-none py-2 px-1 rounded-lg shadow-sm text-center text-gray-600 bg-white hover:bg-lightMain font-medium border"
                            >You will be redirected to our pricing page
                            </button>
                        </div>
                    </div>
                </div>
                <div x-show.transition="step != 'complete'"
                     class="text-center justify-center content-center items-center align-content-center  rounded-2xl">
                    <h1 class="mb-2 text-4xl font-bold ">Develop your <span class="text-main">App</span> with <span
                            class="text-main">Customization</span></h1>
                    <p class="lg:my-2 md:my-2 my-5">
                        Unleash the full potential of your creativity and deliver a unique user experience by
                        developing your app with customization </p>
                </div>
                <div x-show.transition="step != 'complete'">
                    <!-- Top Navigation -->
                    <div class="py-4 lg:my-2 md:my-2 my-2 lg:mb-0 md:mb-0 mb-2 lg:mt-0 md:mt-0 mt-16">
                        <div class="shadow-xl rounded-xl bg-amber-50 overflow-auto">
                            <ol class="lg:space-x-12 md:space-x-12 space-x-3  lg:my-3 md:my-3 w-full  flex lg:items-center lg:text-center md:justify-center md:content-center md:items-center md:text-center p-3  text-sm font-medium text-gray-500  sm:text-base   sm:p-4 sm:space-x-4">
                                <li class="flex items-center text-main" >
                                    <span x-show="step !== 'complete' && step <= 1"
                                          class="flex items-center justify-center w-6 h-6 mr-2 text-xs border border-main  rounded-full shrink-0 ">
                                        1
                                    </span>
                                    <span class="hidden" x-bind:class="{'hidden' : step < '1'}">
                                        <svg x-show="step === 'complete' || step > 1" class=" w-6 h-6 mr-2"
                                             fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"
                                             aria-hidden="true">
                                            <path clip-rule="evenodd" fill-rule="evenodd"
                                                  d="M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12zm13.36-1.814a.75.75 0 10-1.22-.872l-3.236 4.53L9.53 12.22a.75.75 0 00-1.06 1.06l2.25 2.25a.75.75 0 001.14-.094l3.75-5.25z"></path>
                                        </svg>
                                    </span>
                                    Theme
                                    <span class="hidden sm:inline-flex sm:ml-2"></span>
                                    <svg class=" w-3 h-3 ml-2 sm:ml-0 sm:mr-4" aria-hidden="true"
                                         xmlns="http://www.w3.org/2000/svg"
                                         fill="none" viewBox="0 0 12 10">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                              stroke-width="2" d="m7 9 4-4-4-4M1 9l4-4-4-4"/>
                                    </svg>
                                </li>
                                <li class="flex items-center"
                                    :class="{
                                     'text-main': step === 'complete'|| step > 2,
                                        'text-custom': step > 1 && !(step === 'complete' || step > 2)
                                     }">
                                        <span
                                            class="flex items-center justify-center w-6 h-6 mr-2 text-xs border rounded-full shrink-0"
                                            x-show="step !== 'complete' && step <= 2">
                                            2
                                        </span>

                                    <span class="hidden" x-bind:class="{'hidden' : step < '2'}">
                                            <svg x-show="step === 'complete' || step > 2" class=" w-6 h-6 mr-2"
                                                 fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"
                                                 aria-hidden="true">
                                                <path clip-rule="evenodd" fill-rule="evenodd"
                                                      d="M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12zm13.36-1.814a.75.75 0 10-1.22-.872l-3.236 4.53L9.53 12.22a.75.75 0 00-1.06 1.06l2.25 2.25a.75.75 0 001.14-.094l3.75-5.25z"></path>
                                            </svg>
                                    </span>
                                    Color & Logo
                                    <span class="hidden sm:inline-flex sm:ml-2"></span>
                                </li>
                            </ol>
                        </div>
                    </div>
                    <!-- /Top Navigation -->

                    <!-- Step Content -->
                    <div class="container">
                        <div class=" bg-white rounded-2xl py-7  shadow-card shadow-md">
                            <div class="py-2 ml-5 mx-6 gap-x-10">
                                <div x-show.transition.in="step === 1"
                                     x-transition:enter.duration.500ms
                                     :class="{'prograss': step === 1}"
                                >
                                    <div class="flex justify-center">
                                        <div class="grid lg:grid-cols-1 md:grid-cols-1 grid-cols-1 gap-8">
                                            <div class="">
                                                <div class="">
                                                    <div x-data="{ currentCategory: 'all' }">
                                                        <div
                                                            class=" flex space-x-4 text-center content-center justify-center justify-items-center">
                                                            <button @click="currentCategory = 'all'"
                                                                    :class="{'text-main ': currentCategory === 'all', ' text-gray-500': currentCategory !== 'all'}"
                                                                    class="rounded font-bold px-2 py-2 overflow-x-auto">All
                                                            </button>
                                                            @foreach($figmaCategories as $category)
                                                                <button @click="currentCategory = '{{$category->id}}'"
                                                                        :class="{'text-main ': currentCategory === '{{$category->id}}', ' text-gray-500': currentCategory !== '{{$category->id}}'}"
                                                                        class="rounded font-bold px-2 py-2 overflow-x-auto">{{$category->name}}</button>
                                                            @endforeach
                                                        </div>
                                                        <div class="overflow-auto h-96 ">
                                                            <div
                                                                class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 p-4 overflow-x-auto">
                                                                @foreach($figmaDesigns as $design)
                                                                    @php($media = $design->getFirstMedia(\App\Models\FigmaDesign::MEDIA_COLLECTION))
                                                                    <div class="overflow-x-auto"
                                                                         x-show="currentCategory === 'all' || currentCategory === '{{$design->figma_cat_id}}'">
                                                                        <div x-data="{ modelOpen: false}">
                                                                            <div @click="myTheme = '{{$design->name}}'"
                                                                            >
                                                                                <input type="radio" id="{{$design->id}}"
                                                                                       name="theme"
                                                                                       value="{{$design->name}}"
                                                                                       wire:model="theme"
                                                                                       class="hidden peer" required>
                                                                                <label for="{{$design->id}}"
                                                                                       class="inline-flex flex-col items-center justify-between p-1 text-gray-500 bg-white border-gray-200 rounded-lg cursor-pointer peer-checked:ring-2 border-2 peer-checked:border-main peer-checked:text-main peer-checked:ring-main hover:text-gray-600 hover:bg-lightMain">
                                                                                    <div class="relative group">
                                                                                        <img src="{{$media?->getFullUrl() ?? asset('assets/blog.jpg')}}" class="dark:hidden" alt="">
                                                                                        <button  class="hidden group-hover:block absolute inset-0 bg-black bg-opacity-50 text-white transition duration-300">
                                                                                           <span class="right-0 mr-2 mt-2 top-0 absolute">
                                                                                                <svg @click="modelOpen =!modelOpen; splashId = '{{$design->id}}'"  fill="none" class=" h-7 w-7" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                                                                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"></path>
                                                                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                                                            </svg>
                                                                                           </span>
                                                                                        </button>
                                                                                    </div>
                                                                                </label>
                                                                            </div>


                                                                            <div x-show="modelOpen"
                                                                                 class="fixed inset-0 z-50 overflow-y-auto"
                                                                                 aria-labelledby="modal-title"
                                                                                 role="dialog" aria-modal="true">
                                                                                <div
                                                                                    class="flex items-end justify-center min-h-screen px-4 text-center md:items-center sm:block sm:p-0">
                                                                                    <div x-cloak
                                                                                         @click="modelOpen = false"
                                                                                         x-show="modelOpen"
                                                                                         x-transition:enter="transition ease-out duration-300 transform"
                                                                                         x-transition:enter-start="opacity-0"
                                                                                         x-transition:enter-end="opacity-100"
                                                                                         x-transition:leave="transition ease-in duration-200 transform"
                                                                                         x-transition:leave-start="opacity-100"
                                                                                         x-transition:leave-end="opacity-0"
                                                                                         class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-40"
                                                                                         aria-hidden="true"
                                                                                    ></div>

                                                                                    <div x-cloak x-show="modelOpen"
                                                                                         x-transition:enter="transition ease-out duration-300 transform"
                                                                                         x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                                                                                         x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                                                                                         x-transition:leave="transition ease-in duration-200 transform"
                                                                                         x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                                                                                         x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                                                                                         class=" inline-block 2xl:mt-36 h-[700px] lg:h-screen md:h-screen sm:h-screen xl:h-screen 2xl:h-[750px] lg:w-[70%] p-8 overflow-hidden text-left transition-all transform bg-white rounded-3xl shadow-xl"
                                                                                    >
                                                                                        <div class="swiper-section">
                                                                                            <div class="swiper-container">
                                                                                                <div class="swiper-wrapper">
                                                                                                    <?php
                                                                                                    $screen = App\Models\SplashScreen::query()?->where('figma_design_id', $design->id)->first();
                                                                                                        $splashScreens = $screen?->getMedia(App\Models\SplashScreen::MEDIA_COLLECTION);
                                                                                                        ?>
                                                                                                    @foreach($splashScreens??[] as $screenShot)
                                                                                                        <div class="swiper-slide">
                                                                                                            <div class="testimonialBox">
                                                                                                                <div class="details">
                                                                                                                    <div class="imgBx ">
                                                                                                                        <img src="{{$screenShot->getFullUrl()}}"
                                                                                                                             alt="">
                                                                                                                    </div>
                                                                                                                </div>
                                                                                                            </div>
                                                                                                        </div>
                                                                                                    @endforeach
                                                                                                </div>

                                                                                                <svg class="swiper-button-prev" fill="none" stroke="#F4B41A" stroke-width="1.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                                                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"></path>
                                                                                                </svg>

                                                                                                {{--<div class="text-main swiper-button-prev"></div>--}}
                                                                                                {{--<div class="text-main swiper-button-next"></div>--}}

                                                                                                <svg class="swiper-button-next" fill="none" stroke="#F4B41A" stroke-width="1.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                                                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"></path>
                                                                                                </svg>
                                                                                            </div>
                                                                                          <div class="flex items-center justify-center 2xl:h-[750px] h-screen">
                                                                                                <div class="relative">
                                                                                                    <div class="h-24 w-24 rounded-full border-t-8 border-b-8 border-gray-200"></div>
                                                                                                    <div class="absolute top-0 left-0 h-24 w-24 rounded-full border-t-8 border-b-8 border-main animate-spin">
                                                                                                    </div>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                @endforeach
                                                            </div>
                                                           @if($figmaDesigns->hasMorePages())
                                                                <div class="text-center flex justify-center my-5">
                                                                    <button wire:click="loadMore()" wire:loading.attr="disabled" type="button" class="py-2 px-1 flex justify-center items-center  content-center bg-main focus:ring-mainDark focus:ring-offset-main text-white w-28 transition ease-in duration-200 text-center text-base font-semibold shadow-md focus:outline-none focus:ring-2 focus:ring-offset-2  rounded-md max-w-md">
                                                                        load more
                                                                        <svg wire:loading wire:target="loadMore" width="20" height="20" fill="currentColor" class="ml-2 animate-spin" viewBox="0 0 1792 1792" xmlns="http://www.w3.org/2000/svg">
                                                                            <path d="M526 1394q0 53-37.5 90.5t-90.5 37.5q-52 0-90-38t-38-90q0-53 37.5-90.5t90.5-37.5 90.5 37.5 37.5 90.5zm498 206q0 53-37.5 90.5t-90.5 37.5-90.5-37.5-37.5-90.5 37.5-90.5 90.5-37.5 90.5 37.5 37.5 90.5zm-704-704q0 53-37.5 90.5t-90.5 37.5-90.5-37.5-37.5-90.5 37.5-90.5 90.5-37.5 90.5 37.5 37.5 90.5zm1202 498q0 52-38 90t-90 38q-53 0-90.5-37.5t-37.5-90.5 37.5-90.5 90.5-37.5 90.5 37.5 37.5 90.5zm-964-996q0 66-47 113t-113 47-113-47-47-113 47-113 113-47 113 47 47 113zm1170 498q0 53-37.5 90.5t-90.5 37.5-90.5-37.5-37.5-90.5 37.5-90.5 90.5-37.5 90.5 37.5 37.5 90.5zm-640-704q0 80-56 136t-136 56-136-56-56-136 56-136 136-56 136 56 56 136zm530 206q0 93-66 158.5t-158 65.5q-93 0-158.5-65.5t-65.5-158.5q0-92 65.5-158t158.5-66q92 0 158 66t66 158z">
                                                                            </path>
                                                                        </svg>
                                                                    </button>
                                                                </div>
                                                           @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div x-show.transition.in="step === 2"
                                     x-transition:enter.duration.500ms
                                     class="hidden"
                                     x-bind:class="{'hidden' : step < 2}"
                                >
                                    <span x-show="step > 1">
                                        <div class="ColorMarginInColorPicker my-5">
                                            <x-color-picker label="Select your primary color" placeholder="#f8fafc"
                                                            wire:model="primaryColor"/>
                                        </div>
                                        <div class="ColorMarginInColorPicker">
                                            <x-color-picker label="Select your secondary color" placeholder="#fafafa"
                                                            wire:model="secondaryColor"/>
                                        </div>
                                        <?php
                                        $image = App\Models\UserAppPreference::query()
                                            ->whereUserId(auth()->id())->first();
                                        $logo = $image?->getFirstMediaUrl(App\Models\UserAppPreference::MEDIA_COLLECTION);
                                        ?>
                                        <div class="py-2 ml-0 mt-3 flex justify-around text-center">
                                            <div x-show="!isEditing">
                                                <span>
                                                    {{$this->form}}
                                                    <x-filament-actions::modals />
                                                </span>
                                            </div>
                                            <div class="flex items-center space-x-4" x-show="isEditing">
                                                <span>
                                                    <div class="text-center font-semibold">Preview Logo</div>
                                                    <img class="w-36 h-36 my-5  rounded-full border" src="{{$logo}}" alt="">
                                                    <button @click="isEditing = !isEditing" type="button" class="mt-2 text-white bg-main hover:bg-mainDark focus:outline-none focus:ring-4 focus:ring-yellow-300 font-medium rounded-md text-sm px-1.5 py-2.5 text-center mr-2 mb-2 dark:focus:ring-yellow-900">Change image</button>
                                                </span>
                                            </div>
                                        </div>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- / Step Content -->
                </div>
            </div>

            <!-- Bottom Navigation -->
            <div class="fixed bottom-0 left-0 right-0 py-5 shadow-md" x-show="step != 'complete'">
                <div class="max-w-5xl mx-auto px-4">
                    <div class="flex justify-between">
                        <div>
                            <button
                                x-show="step > 1"
                                @click="step--"
                                class="hidden text-black bg-gradient-to-r from-white via-white to-white hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-white shadow-md  font-medium rounded-md text-sm px-5 py-2.5 text-center mr-2 mb-2"
                                x-bind:class="{'hidden' : step < 2 || step === 1}"
                            >Previous

                            </button>
                        </div>

                        <div class=" text-right">
                            <button
                                type="button"
                                x-show="step < 2"
                                @click="step++"
                                :disabled="(step === 1 && myTheme === '') || (step === 2 && (primaryColor === '' && secondaryColor === ''))"
                                :class="(step === 1 && myTheme === '') || (step === 2 && (primaryColor === '' && secondaryColor === '')) ? 'opacity-50 cursor-not-allowed' : ''"
                                class="text-white bg-main hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-main rounded-md text-sm px-5 py-2.5 text-center mr-2 mb-2  font-medium"
                            >Next
                            </button>

                            <span class="hidden" x-bind:class="{'hidden' : step < 2}">
                                <button
                                    wire:click.prevent="completeSteps"
                                    @click="step = 'complete'"
                                    class="w-32 focus:outline-none border border-transparent py-2 px-5 rounded-lg shadow-sm text-center text-white bg-main hover:bg-mainDark font-medium"
                                    :disabled="(step > 1 && media.media.length === 0 && !isEditing)"
                                    :class="(step > 1 &&  media.media.length === 0 && !isEditing) ? 'opacity-50 cursor-not-allowed' : ''"
                                >Complete
                                </button>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script defer>
            const swiper = new Swiper('.swiper-container', {
                effect: 'coverflow',
                grabCursor: true,
                centeredSlides: true,
                slidesPerView: 'auto',
                loop: true,
                coverflowEffect: {
                    rotate: 0,
                    stretch: 0,
                    depth: 100,
                    modifier: 2,
                    slideShadows: true,
                },
                navigation: {
                    nextEl: '.swiper-button-next',
                    prevEl: '.swiper-button-prev',
                }

            });
            function app() {
                return {
                    step: 1,
                    imagePreview: '',
                    figmaId: '',
                    splashId: @entangle('splashId'),
                    myTheme: @entangle('theme'),
                    primaryColor: @entangle('primaryColor'),
                    secondaryColor: @entangle('secondaryColor'),
                    media: @entangle('media'),
                    logo: '',
                    isEditing : @entangle('editingLogo'),
                    initScroll() {
                        this.$watch('step', () => {
                            const el = document.querySelector('.text-custom')
                            if (el) {
                                el.scrollIntoView({behavior: "smooth"})
                            }
                        })
                    }
                }
            }
        </script>
    </div>
</div>

