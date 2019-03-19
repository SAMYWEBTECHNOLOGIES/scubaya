<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('page-title','Home') - Scubaya.com</title>

    {{-- custom css --}}
    <link rel="shortcut icon" href="{{asset('assets/images/logo/favicon.png')}}">
    <link href="{{asset('plugins/font-awesome-4.7.0/css/font-awesome.min.css')}}" rel="stylesheet">
    <link href="{{asset('plugins/slick-1.8.0/slick.css')}}" rel="stylesheet" type="text/css" >
    <link href="{{asset('plugins/slick-1.8.0/slick-theme.css')}}" rel="stylesheet" type="text/css" >
    <link href="{{asset('assets/semanticui/components/calendar.min.css')}}" rel="stylesheet">
    <link href="{{asset('assets/semanticui/semantic.min.css')}}" rel="stylesheet">

    <link href="{{asset('plugins/leaflet/leaflet.css')}}" rel="stylesheet">
    <script src="{{asset('plugins/leaflet/leaflet.js')}}"></script>

    <link href="{{asset('assets/css/front.css')}}" rel="stylesheet">

    {{--jquery--}}
    <script src="{{ asset('assets/front/js/jquery-3.1.0.min.js') }}" type="text/javascript"></script>

    {{--jquery validation script--}}
    <script src="{{asset('plugins/jquery-validation/dist/jquery.validate.min.js')}}"></script>
    <script src="{{asset('plugins/jquery-validation/dist/additional-methods.js')}}"></script>

    <script src="{{ asset('assets/semanticui/components/calendar.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/semanticui/semantic.min.js') }}" type="text/javascript"></script>
    <script src="{{asset('plugins/slick-1.8.0/slick.min.js')}}"></script>
</head>
<body>

<div class="pusher">
    @yield('content')
    @include('front._partials.footer')
    @include('front._partials.signin')
    @include('front._partials.signup')
</div>

@yield('script-extra')
{{--script and css for cookie consent--}}
<link rel="stylesheet" type="text/css" href="{{asset('plugins/cookieconsent/build/cookieconsent.min.css')}}" />
<script src="{{asset('plugins/cookieconsent/build/cookieconsent.min.js')}}"></script>

<script type="text/javascript">
    @if(env('APP_ENV') == 'production')
        (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
            (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
            m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
        })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');
        ga('create', 'UA-90501307-1', 'auto');
        ga('send', 'pageview');
    @endif

    /*script to ask user for their cookie consent*/
    window.addEventListener("load", function(){
        window.cookieconsent.initialise({
            "palette": {
                "popup": {
                    "background": "#edeff5",
                    "text": "#838391"
                },
                "button": {
                    "background": "#318DE9"
                }
            },
            "content": {
                /*"message": "This website uses cookies to ensure you get the best experience on our website",
                "dismiss": "Got it",
                "link": "Learn more",*/
                "href": "{{route('scubaya::dynamic_content::dynamic_page',['privacy-policy'])}}"
            }
        })
    });

    $(document).ready(function () {
        $(".scu-signin-btn").click(function () {
            $('.ui.modal.scu-signin')
                .modal('setting', 'transition', 'horizontal flip')
                .modal('show')
                .modal('refresh');
        });

        $(".scu-signup-btn").click(function () {
            $('.ui.modal.scu-signup')
                .modal('setting', 'transition', 'horizontal flip')
                .modal('show')
                .modal('refresh');
        });

        $('.ui.right.sidebar').sidebar({
            transition: 'overlay'
        });

        // right is opened by button
        $('.ui.right.sidebar').sidebar('attach events', '#menu');

        @if($errors->any())
            $(function () {
                $('.ui.modal.scu-signup').modal('show');
            });
        @endif

        @if(isset($_GET['show_popup']))
            $(function () {
                $('.ui.modal.scu-signin').modal('show');
            });
        @endif

        $('.message .close').on('click', function() {
            $(this).closest('.message').transition('fade');
        });

        $( "#signup-form" ).validate({
            rules: {
                email:{
                    required: true,
                    email: true
                },
                password: "required",
                password_confirmation: {
                    equalTo: "#sign_up_password"
                }
            },
            messages:{
                password_confirmation:{
                    equalTo:"Password didnt match, enter again"
                }
            }
        });
    });

</script>
</body>
</html>