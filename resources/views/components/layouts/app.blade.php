@props(['title' => ''])
    <!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">

    <meta name="application-name" content="{{ config('app.name') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="facebook-domain-verification" content="ygwym6gr2qpw8orgtzbaa5cb7t31ip"/>
    <title>{{ config('app.name') . ' | '. $title }}</title>

    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
    @livewire('notifications')
    @wireUiScripts
    @filamentStyles
    @vite('resources/css/app.css')
</head>
<body class="antialiased relative">
{{ $slot ?? '' }}

@filamentScripts
@vite('resources/js/app.js')
{{--Sticky Help Button--}}
<div x-data="{ showModal: false }">
    <button @click="showModal = !showModal" type="button"
            class="fixed bottom-5 right-5 hover:shadow-2xl z-50 rounded-full bg-main p-4 text-white hover:bg-main focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
        <svg x-show="!showModal" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
             stroke="currentColor"
             class="w-6 h-6">
            <path stroke-linecap="round" stroke-linejoin="round"
                  d="M2.25 12.76c0 1.6 1.123 2.994 2.707 3.227 1.068.157 2.148.279 3.238.364.466.037.893.281 1.153.671L12 21l2.652-3.978c.26-.39.687-.634 1.153-.67 1.09-.086 2.17-.208 3.238-.365 1.584-.233 2.707-1.626 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0012 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018z"/>
        </svg>
        <svg x-show="showModal" x-cloak xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
             stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
        </svg>
    </button>
</div>
</body>
</html>
