@props([
    'locked',
    'title'
])
@if($locked)
    <div {{ $attributes }} class="flex justify-center items-center relative">
    <span class="absolute mx-auto my-auto text-center flex justify-center lg:z-30">
        <a href="{{url('/pricing')}}" class="bg-primary-600 px-3 py-2 rounded-md text-white">
            @if(!empty($title))
                {{$title}}
                @else
                🔒 Upgrade Your Plan
            @endif
        </a>
    </span>
        <div class="blur-[6px] lg:z-[1] w-full  relative pointer-events-none">
            {{$slot}}
        </div>
    </div>
@else
    <div>
        {{$slot}}
    </div>
@endif
