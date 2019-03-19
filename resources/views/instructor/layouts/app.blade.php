<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title','Scubaya')</title>
    @include('instructor.layouts.header')
    @include('instructor.layouts.scripts')
</head>

<body>
{{--mainheader will when the merchant is logged in--}}
@if(Auth::id())
    @include('instructor.layouts.mainheader')
@endif

{{--here the extended html came--}}
@yield('content')
</body>
</html>