<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>User:@yield('title','Scubaya')</title>
    @include('user.layouts.header')
    @include('user.layouts.scripts')
</head>

@if(!Auth::check())
    @yield('content')
@else
<body class="hold-transition skin-blue sidebar-mini">

    <div class="wrapper">

        @include('user.layouts.partials.mainheader')

        @include('user.layouts.partials.sidebar')

        <div class="content-wrapper">
            <section class="content-header">
                @yield('contentheader')
            </section>

            @yield('content')
        </div>

        @include('user.layouts.partials.footer')
        @include('user.layouts.partials.settings-bar')
    </div>

</body>
@endif

</html>