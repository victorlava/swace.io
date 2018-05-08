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
    <link rel="icon" href="favicon.ico">

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

    <nav class="navbar py-3 navbar-expand-sm navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="{{ route('dashboard.index') }}">
                <img src="{{ asset('/images/swace-logo-color-white-type.svg') }}" alt="">
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNavDropdown">
                <ul class="navbar-nav my-4 my-sm-0 ml-auto ">
                    @guest
                    @else
                    <li class="nav-item mb-1 mb-sm-auto mr-sm-1 dropdown">
                        <a class="nav-link px-4 py-3 text-right dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            {{ Auth::user()->email }}
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                            <a class="dropdown-item" href="{{ route('profile.index') }}">Account settings</a>
                            <a class="dropdown-item" href="#">KYC Verification</a>
                        </div>
                    </li>
                    <li class="nav-item ">
                        <a class="nav-link px-4 py-3 text-right text-uppercase"
                            href="{{ route('logout') }}"
                            onclick="event.preventDefault();
                                    document.getElementById('logout-form').submit();">
                            {{ __('Sign out') }}
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    </li>
                    @endif
                </ul>
            </div>
        </div>
    </nav>

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

    @yield('content')

    <footer>
        <div class="container">
            <div class="copyright text-center py-5">&copy; 2016-2018 Swace Ltd.</div>
        </div>
    </footer>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js" integrity="sha384-cs/chFZiN24E4KMATLdqdvsezGxaGsi4hLGOzlXwp5UZB1LY//20VyM2taTB4QvJ" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js" integrity="sha384-uefMccjFJAIv6A+rW+L4AHf99KvxDjWSu1z9VI8SKNVmz4sk7buKt/6v9KI65qnm" crossorigin="anonymous"></script>
    <!-- <script src="js/rangeslider.min.js" type="text/javascript"></script> -->
    <script src="{{ asset('js/nouislider.min.js') }}" type="text/javascript"></script>

    <script type="text/javascript">

        var html5Slider = document.getElementById('SWASlider');

        noUiSlider.create(html5Slider, {
            connect: [true, false],
            behaviour: 'tap',
            start: 10000,
            // step: 1000,
            range: {
                'min': 1000,
                'max': 50000
            },
        });

        var inputNumber = document.getElementById('swaAmount');

        html5Slider.noUiSlider.on('update', function( values, handle ) {
            var value = values[handle];
            inputNumber.value = Math.round(value);
        });

        inputNumber.addEventListener('change', function(){
            html5Slider.noUiSlider.set([this.value]);
        });

    </script>

    <script src="{{ asset('js/main.js') }}" type="text/javascript"></script>

</body>
</html>
