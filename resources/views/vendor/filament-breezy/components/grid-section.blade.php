@props(['title','description'])
<div {{ $attributes }} @class(['py-4'])>
    <div class="mx-6">
        <h3 @class(['text-lg font-medium filament-breezy-grid-title'])>{{$title}}</h3>

        <p @class(['mt-1 text-sm text-gray-500 filament-breezy-grid-description'])>
            {{$description}}
        </p>
        <div class="py-4">
            {{ $slot }}
        </div>
    </div>
</div>
