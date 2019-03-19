<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Scubaya your dive buddy - @yield('title')</title>
    @include('merchant.layouts.header')
    @include('merchant.layouts.scripts')
</head>

<body>
    @if(Auth::check())
        <div class="merchant-breadcrumbs">
            <ol class="breadcrumb breadcrumb-arrow">
                @yield('breadcrumb')
            </ol>
        </div>
    @endif
    @yield('content')
</body>
</html>
