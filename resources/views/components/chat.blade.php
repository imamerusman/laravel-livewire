@php use App\Models\Message; @endphp
<div>
    <div class="flex flex-col flex-auto flex-shrink-0 h-[26rem]">
        <div class="flex sm:items-center justify-between py-3 border-b-2 border-gray-200">
            <div class="relative flex items-center space-x-4">
                <div class="flex flex-col leading-tight">
                    <div class="text-2xl mt-1 flex items-center">
                        <h2 class="text-gray-700 mr-3 dark:text-white">{{$conversation->recipient_user->name}}</h2>
                    </div>
                    <p class="text-lg text-gray-600 dark:text-white">{{$conversation->recipient_user->email}}</p>
                </div>
            </div>
        </div>
        @if(filled($conversation->messages))
            <div id="messages"
                 class="flex flex-col space-y-4 p-3 overflow-y-auto scrollbar-thumb-blue scrollbar-thumb-rounded scrollbar-track-blue-lighter scrollbar-w-2 scrolling-touch">
                @foreach($conversation->messages as $message)
                    @php($media = $message->getFirstMedia(Message::MEDIA_COLLECTION))
                    <div class="chat-message m-4">
                        <div @class(['flex items-end','justify-end' => $message->sender_id == auth()->id(),'' => $message->sender_id != auth()->id()])>
                            <div @class(['flex flex-col space-y-2 text-xs max-w-xs mx-2 order-2','items-end' => $message->sender_id == auth()->id(),'items-start' => $message->sender_id != auth()->id()])>
                                                <span
                                                    class="px-4 py-2 rounded-lg inline-block rounded-bl-none bg-gray-300 text-gray-600">
                                                    @if(blank($media))
                                                        {{$message->content}}
                                                    @endif
                                                    @if(filled($media))
                                                        <a href="{{$media->getUrl()}}" target="_blank">
                                                            <img
                                                                alt="{{$media->getKey()}}"
                                                                src="{{$media->getUrl()}}"
                                                                onerror="this.src='{{asset('not-found.png')}}'"
                                                                class="w-48 h-48 rounded shadow"
                                                            >
                                                        </a>
                                                    @endif
                                                </span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="flex flex-col items-center justify-center h-full">
                <div class="flex flex-col items-center justify-center">
                    <p class="mt-4 text-gray-400">No messages yet</p>
                </div>
            </div>
        @endif
    </div>
    <div class="w-full">
        {{$this->form}}
    </div>
    <div class="w-full">
        <div class="flex flex-row">
            <div class="flex-grow">
                <label for="message" class="block text-sm font-medium leading-6 text-gray-900">Type
                    Message</label>
                @error('message') <span class="text-red-500 text-xs italic">
                                    {{ $message }}</span>
                @enderror
                <div class="mt-2 flex rounded-md shadow-sm">
                    <div class="relative flex flex-grow items-stretch focus-within:z-10">
                        <button wire:click="$set('showAttachment', '{{!$this->showAttachment ?? true}}')"
                                class="cursor-pointer absolute inset-y-0 left-0 flex items-center pl-3">
                            <x-filament::loading-indicator
                                wire:loading="true"
                                wire:target="showAttachment"
                                class="h-6 w-6"
                            />
                            @if($this->showAttachment)
                                {{--X--}}
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                     stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            @else
                                {{--Clip--}}
                                <svg wire:loading.remove wire:target="showAttachment"
                                     xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                     stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M18.375 12.739l-7.693 7.693a4.5 4.5 0 01-6.364-6.364l10.94-10.94A3 3 0 1119.5 7.372L8.552 18.32m.009-.01l-.01.01m5.699-9.941l-7.81 7.81a1.5 1.5 0 002.112 2.13"/>
                                </svg>
                            @endif
                        </button>
                        <input type="text" name="message"
                               wire:keydown.enter="submit"
                               required id="message"
                               autocomplete="false"
                               wire:model="message"
                               @disabled(filled($this->attachment))
                               @class([
                                'ring-red-500 ring-2' => $errors->has('message'),
                                'block w-full rounded-none rounded-l-md border-0 py-1.5 pl-10 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-main sm:text-sm sm:leading-6'
                               ])
                               placeholder="How can we help?"
                        >
                    </div>
                    <button wire:click="submit" wire:target="submit" wire:loading.attr="disabled"
                            class="relative -ml-px inline-flex items-center gap-x-1.5 rounded-r-md px-3 py-2 text-sm font-semibold text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-50">
                        <x-filament::loading-indicator
                            wire:loading="true"
                            wire:target="submit"
                            class="h-6 w-6"
                        />
                        <svg wire:loading.remove="true" wire:target="submit"
                             xmlns="http://www.w3.org/2000/svg"
                             fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                             class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5"/>
                        </svg>
                        <span wire:loading.remove="true" wire:target="submit">Send</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
