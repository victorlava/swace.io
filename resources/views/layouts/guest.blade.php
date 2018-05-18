<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <meta name="description" content="">
    <meta name="author" content="">
    <meta property="og:site_name" content="Swace" />
    <meta property="og:type" content="website" />
    <meta property="og:title" content="Swace" />
    <meta property="og:image" content="{{ asset('/images/swacoin.svg') }}" />
    <meta property="og:description" content="Project description" />
    <meta property="og:url" content="{{ url()->current() }}" />
    <link rel="icon" href="{{ asset('img/favicon.ico') }}">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Google Tag Manager -->

    <!-- End Google Tag Manager -->

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700" rel="stylesheet">
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">

    @yield('head')
</head>
<body>

    @if(Session::has('message'))
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="alert {!! Session::get('type') == 'success' ? 'alert-success' : 'alert-danger' !!}" role="alert">
                    {{ Session::get("message") }}
                </div>
            </div>
        </div>
    </div>
    @endif

    <main class="">
        <div class="canvas">
            <div class="bubble-1"></div>
            <div class="shape-1"></div>
        </div>

        <div class="container">
            <div class="row mt-3 mx-0">
                <div class="col-md-6 light-block p-4 p-sm-5 p-md-4 p-lg-5">
                    @yield('content')
                </div>

                <div class="col-md-6 text-center text-md-right">
                    <div class="content">
                        @yield('menu')
                    </div>
                </div>

            </div>
        </div>
    </main>

    <footer>
        <div class="container">
            <div class="copyright text-center py-5">&copy; 2016-2018 Swace Ltd.</div>
        </div>
    </footer>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js" integrity="sha384-cs/chFZiN24E4KMATLdqdvsezGxaGsi4hLGOzlXwp5UZB1LY//20VyM2taTB4QvJ" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js" integrity="sha384-uefMccjFJAIv6A+rW+L4AHf99KvxDjWSu1z9VI8SKNVmz4sk7buKt/6v9KI65qnm" crossorigin="anonymous"></script>

</body>
</html>
