<!DOCTYPE html>
<html lang="en">
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Admin - @yield('title','Admin Scubaya')</title>
        @include('admin.layouts.header')
        @include('admin.layouts.scripts')
        @include('admin.layouts.admin_scripts')
    </head>
    <body>

    @if(Auth::check())
        @include('admin.layouts.partials.navigation_bar')
    @endif

    @if(Auth::check())
        <div class="container admin-breadcrumbs">
            <ul class="breadcrumb breadcrumb-arrow">
                @yield('breadcrumb')
            </ul>
        </div>
    @endif

    {{--to yield the main content--}}
    @yield('content')

    {{--the delete script for the confirmation--}}
    @include('admin.layouts.delete_script')
    </body>

</html>