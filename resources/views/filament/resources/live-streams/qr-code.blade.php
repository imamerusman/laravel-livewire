<div x-data="{ activeTab: 'instructions' }">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <div class="sm:hidden">
        <label for="tabs" class="sr-only">Select a tab</label>
        <!-- Use an "x-model" directive to bind the selected tab to the Alpine.js data -->
        <select id="tabs" name="tabs"
                class="block w-full rounded-md border-gray-300 focus:border-indigo-500 pb-4 focus:ring-indigo-500"
                x-model="activeTab">
            <option value="instructions">Instructions</option>
            <option value="preview">Preview</option>
        </select>
    </div>
    <div class="hidden sm:block">
        <nav class="flex space-x-4 pb-4" aria-label="Tabs">
            <a href="#"
               x-on:click.prevent="activeTab = 'instructions'"
               class="text-gray-500 hover:text-gray-700 rounded-md px-3 py-2 text-sm font-medium"
               x-bind:class="{ 'bg-gray-100 text-gray-700': activeTab === 'instructions' }">Instructions</a>
            <a href="#"
               x-on:click.prevent="activeTab = 'preview'"
               class="text-gray-500 hover:text-gray-700 rounded-md px-3 py-2 text-sm font-medium"
               x-bind:class="{ 'bg-gray-100 text-gray-700': activeTab === 'preview' }">Preview</a>
        </nav>
    </div>
    <div class="space-y-2 card" x-show="activeTab === 'instructions'">
     <span class="flex items-start space-x-3 mb-2">
        Step: 1
     </span>
        <div class="rounded-md bg-blue-50 p-4">
            <p class="text-sm">
                You will need to download and install our
                <a href="https://play.com/" target="_blank"
                   class="text-blue-600 underline hover:text-indigo-500">App</a>
                to go live.
            </p>
        </div>
        <span class="flex items-start space-x-3 mb-2">
                              Step: 2
                            </span>
        <div class="rounded-md bg-blue-50 p-4">
            <p class="text-sm">
                Scan the QR code with our app to go live.
            </p>
        </div>
        <div class="flex flex-col items-center justify-center space-y-2">
            <div id="qr" class="p-2">{{$qr}}</div>
        </div>
    </div>

    <div x-show="activeTab === 'preview'">
        <div class="flex flex-col items-center justify-center space-y-2 mb-2 h-[21rem] shadow-lg rounded-2xl">
            <iframe src="{{$liveStream->stream_link}}"
                    width="100%"
                    height="100%"
                    frameborder="0"
                    scrolling="no"
                    allowfullscreen
            >
            </iframe>
        </div>
    </div>
</div>
