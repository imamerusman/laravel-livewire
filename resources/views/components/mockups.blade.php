<x-filament::card>
    <div class="grid min-h-screen place-items-center">
        <!-- The iPhone shell -->
        <div class="relative mx-auto h-[712px] w-[350px] overflow-hidden rounded-[60px] border-[14px] border-black bg-black shadow-xl ring ring-purple-400">
            <img class="absolute inset-0 h-full w-full object-cover"
                 src="https://wallpapers.hector.me/wavey/Rainbow%20iPhone%20P3.jpg"/>
            <div class="absolute inset-x-0 top-0">
                <div class="mx-auto h-6 w-40 rounded-b-3xl bg-black"></div>
            </div>
            <div class="relative">
                <!-- Small icons on top right -->
                <div class="mr-5 mt-2 flex justify-end space-x-1">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-white" viewBox="0 0 20 20"
                         fill="currentColor">
                        <path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z"/>
                    </svg>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-white" viewBox="0 0 20 20"
                         fill="currentColor">
                        <path fill-rule="evenodd"
                              d="M17.778 8.222c-4.296-4.296-11.26-4.296-15.556 0A1 1 0 01.808 6.808c5.076-5.077 13.308-5.077 18.384 0a1 1 0 01-1.414 1.414zM14.95 11.05a7 7 0 00-9.9 0 1 1 0 01-1.414-1.414 9 9 0 0112.728 0 1 1 0 01-1.414 1.414zM12.12 13.88a3 3 0 00-4.242 0 1 1 0 01-1.415-1.415 5 5 0 017.072 0 1 1 0 01-1.415 1.415zM9 16a1 1 0 011-1h.01a1 1 0 110 2H10a1 1 0 01-1-1z"
                              clip-rule="evenodd"/>
                    </svg>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-white" viewBox="0 0 20 20"
                         fill="currentColor">
                        <path d="M8 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM15 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0z"/>
                        <path d="M3 4a1 1 0 00-1 1v10a1 1 0 001 1h1.05a2.5 2.5 0 014.9 0H10a1 1 0 001-1V5a1 1 0 00-1-1H3zM14 7a1 1 0 00-1 1v6.05A2.5 2.5 0 0115.95 16H17a1 1 0 001-1v-5a1 1 0 00-.293-.707l-2-2A1 1 0 0015 7h-1z"/>
                    </svg>
                </div>
                <div class="ml-auto mr-7 mt-1.5 h-0.5 w-10 rounded bg-white"></div>
                <!-- Date & time -->
                <div class="mt-2 flex flex-col items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" viewBox="0 0 20 20"
                         fill="currentColor">
                        <path d="M10 2a5 5 0 00-5 5v2a2 2 0 00-2 2v5a2 2 0 002 2h10a2 2 0 002-2v-5a2 2 0 00-2-2H7V7a3 3 0 015.905-.75 1 1 0 001.937-.5A5.002 5.002 0 0010 2z"/>
                    </svg>
                    <p class="mt-3 text-6xl font-extralight text-white">9:41</p>
                    <p class="mt-1 text-lg font-light text-white">Monday, June 7</p>
                </div>
                <!-- Notification Summary -->
                <div class="relative mx-2 mt-4">
                    <!-- Stacked panels (sitting below) -->
                    <div class="absolute inset-x-0 -bottom-4 h-full w-full origin-bottom scale-[0.85] rounded-2xl bg-white/10 backdrop-blur-md"></div>
                    <div class="absolute inset-x-0 -bottom-2 h-full w-full origin-bottom scale-95 rounded-3xl bg-white/30 shadow-sm backdrop-blur-md"></div>
                    <!-- Main, current panel -->
                    <div class="rounded-3xl bg-white/40 p-4 shadow backdrop-blur-md">
                        <div class="flex items-start justify-between">
                            <div>
                                <div x-data="{ scheduled_at: @entangle('data.scheduled_at').live}">
                                    <p class="text-xs font-bold" x-text="formatTime(scheduled_at ?? Date.now())"></p>
                                </div>
                                <script>
                                    /**
                                     * @param {number|string|Date|VarDate} datetime
                                     */
                                    const formatTime = (datetime) => new Date(datetime).toLocaleTimeString([], {
                                        hour: 'numeric',
                                        minute: 'numeric',
                                        hour12: true
                                    });
                                </script>
                                <div x-data="{ title: @entangle('data.title').live }">
                                    <h2 class="text-lg font-bold" x-text="title ?? 'Your Title Here'"></h2>
                                </div>
                                <div x-data="{ body: @entangle('data.body').live }">
                                    <p class="mt-0.5 text-xs" x-text="body ?? 'Body Text Goes Here'"></p>
                                </div>
                                <div x-data="{
                                        url: window.location.origin + '/livewire-tmp/',
                                        mediaToString(media) {
                                            for (const key in media) {
                                                if (media.hasOwnProperty(key)) {
                                                    const value = media[key];
                                                    if (value.startsWith('livewire-file:')) {
                                                        return  this.url + value.substring('livewire-file:'.length);
                                                    } else {
                                                        return fetch('/api/get-media/' + value)
                                                            .then(response => response.json())
                                                    }
                                                }
                                            }
                                        }
                                    }" class="flex items-center justify-between mt-2">
                                    <img class="mt-2 text-xs rounded-lg shadow-lg object-cover h-20 w-full"
                                         :src="mediaToString(@entangle('data.media').initialValue)
                                         ?? 'https://placehold.co/400x100?text=Your+Image+Here'"
                                    />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Flashlight, camera and bottom swipe menu -->
            <div class="absolute inset-x-0 bottom-0 h-20">
                <div class="flex justify-between px-10">
                    <button class="rounded-full bg-gray-800/40 p-2 text-white backdrop-blur-md">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M11 3a1 1 0 10-2 0v1a1 1 0 102 0V3zM15.657 5.757a1 1 0 00-1.414-1.414l-.707.707a1 1 0 001.414 1.414l.707-.707zM18 10a1 1 0 01-1 1h-1a1 1 0 110-2h1a1 1 0 011 1zM5.05 6.464A1 1 0 106.464 5.05l-.707-.707a1 1 0 00-1.414 1.414l.707.707zM5 10a1 1 0 01-1 1H3a1 1 0 110-2h1a1 1 0 011 1zM8 16v-1h4v1a2 2 0 11-4 0zM12 14c.015-.34.208-.646.477-.859a4 4 0 10-4.954 0c.27.213.462.519.476.859h4.002z"/>
                        </svg>
                    </button>
                    <button class="rounded-full bg-gray-800/40 p-2 text-white backdrop-blur-md">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                  d="M4 5a2 2 0 00-2 2v8a2 2 0 002 2h12a2 2 0 002-2V7a2 2 0 00-2-2h-1.586a1 1 0 01-.707-.293l-1.121-1.121A2 2 0 0011.172 3H8.828a2 2 0 00-1.414.586L6.293 4.707A1 1 0 015.586 5H4zm6 9a3 3 0 100-6 3 3 0 000 6z"
                                  clip-rule="evenodd"/>
                        </svg>
                    </button>
                </div>
                <div class="absolute inset-x-0 bottom-1">
                    <div class="mx-auto h-1 w-28 rounded bg-white"></div>
                </div>
            </div>
        </div>
    </div>
</x-filament::card>
