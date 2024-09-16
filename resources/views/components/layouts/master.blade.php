{{--
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <!-- End Meta Pixel Code -->
    <meta name="facebook-domain-verification" content="ygwym6gr2qpw8orgtzbaa5cb7t31ip"/>
    @notifyCss
    @include('frontend.layouts.style')
    @vite('resources/css/app.css')
</head>

<body class="selection:bg-textColor selection:text-white">
@include('frontend.layouts.header')
<div class="bg-white relative z-50">
    <x-notify::notify class="absolute top-0 right-0 bg-white p-4 shadow-lg"/>
</div>

@yield('content')
@notifyJs
@include('frontend.layouts.footer')
@include('frontend.layouts.script')
@vite('resources/js/app.js')
</body>
</html>
--}}
<x-layouts.app :title="$title">
    @include('frontend.layouts.header')
    {{$slot ?? ''}}
    @include('frontend.layouts.footer')
    @include('frontend.layouts.script')
</x-layouts.app>
