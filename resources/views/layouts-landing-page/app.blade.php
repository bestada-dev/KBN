<html lang="{{ app()->getLocale() }}">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>@yield('title', 'Official Website') - {{ __('app-kbn.head.title') }}</title>

    <script src="{{ asset('assets/for-landing-page/tailwindcss3.4.5.js') }}"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Vollkorn:ital,wght@0,400..900;1,400..900&display=swap&family=Playfair+Display:wght@700&amp;family=Ubuntu:wght@400;700&amp;display=swap"
        rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;700&amp;display=swap" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.5.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.14.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.4/dist/css/splide.min.css">
    <script src="https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.4/dist/js/splide.min.js"></script>
    <link rel="stylesheet" href="{{ asset('assets/for-landing-page/style.css') }}">
    <!-- @ms -->
    <script>
        function changeLanguage(locale) {
            console.log(locale);
            window.location.href = "{{ route('change_language') }}?locale=" + locale;
        }
    </script>
    <!-- endms -->

    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-ER31B7YNC9"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }
        gtag('js', new Date());

        gtag('config', 'G-ER31B7YNC9');
    </script>
    <style>
        body {
            font-family: 'Nunito', sans-serif;
            scroll-behavior: smooth;
            /* Enables smooth scrolling */
        }

        .test {
            border: 1px solid red;
        }

        .splide__list {
            padding-bottom: 0.7rem !important;
        }

        #splide-gallery .splide__list {
            height: unset;
        }

        #splide-gallery .splide__track {
            padding-left: unset !important;
        }

        .splide__arrow {
            background: white;
            border: 2px solid green;
            border-radius: 8px;
        }

        .splide__arrows.splide__arrows--ltr {
            position: absolute;
            width: 120px;
            right: 0;
            top: -3rem;
        }

        .bg-custom-green-gradient {
            background: linear-gradient(180deg, #07764A 0%, #FFFFFF 278.54%);
        }

        .playfair-700 {
            font-family: 'Playfair Display', serif;
            font-optical-sizing: auto;
            font-weight: 700;
            font-style: normal;
        }

        .vollkorn-400 {
            font-family: "Vollkorn", serif;
            font-optical-sizing: auto;
            font-weight: 400;
            font-style: normal;
        }

        .vollkorn-500 {
            font-family: "Vollkorn", serif;
            font-optical-sizing: auto;
            font-weight: 500;
            font-style: normal;
        }

        .vollkorn-600 {
            font-family: "Vollkorn", serif;
            font-optical-sizing: auto;
            font-weight: 600;
            font-style: normal;
        }

        .vollkorn-700 {
            font-family: "Vollkorn", serif;
            font-optical-sizing: auto;
            font-weight: 700;
            font-style: normal;
        }

        .vollkorn-800 {
            font-family: "Vollkorn", serif;
            font-optical-sizing: auto;
            font-weight: 800;
            font-style: normal;
        }

        .vollkorn-900 {
            font-family: "Vollkorn", serif;
            font-optical-sizing: auto;
            font-weight: 900;
            font-style: normal;
        }

        .poppins-thin {
            font-family: "Poppins", sans-serif;
            font-weight: 100;
            font-style: normal;
        }

        .poppins-extralight {
            font-family: "Poppins", sans-serif;
            font-weight: 200;
            font-style: normal;
        }

        .poppins-light {
            font-family: "Poppins", sans-serif;
            font-weight: 300;
            font-style: normal;
        }

        .poppins-regular {
            font-family: "Poppins", sans-serif;
            font-weight: 400;
            font-style: normal;
        }

        .poppins-medium {
            font-family: "Poppins", sans-serif;
            font-weight: 500;
            font-style: normal;
        }

        .poppins-semibold {
            font-family: "Poppins", sans-serif;
            font-weight: 600;
            font-style: normal;
        }

        .poppins-bold {
            font-family: "Poppins", sans-serif;
            font-weight: 700;
            font-style: normal;
        }

        .poppins-extrabold {
            font-family: "Poppins", sans-serif;
            font-weight: 800;
            font-style: normal;
        }

        .poppins-black {
            font-family: "Poppins", sans-serif;
            font-weight: 900;
            font-style: normal;
        }

        .poppins-thin-italic {
            font-family: "Poppins", sans-serif;
            font-weight: 100;
            font-style: italic;
        }

        .poppins-extralight-italic {
            font-family: "Poppins", sans-serif;
            font-weight: 200;
            font-style: italic;
        }

        .poppins-light-italic {
            font-family: "Poppins", sans-serif;
            font-weight: 300;
            font-style: italic;
        }

        .poppins-regular-italic {
            font-family: "Poppins", sans-serif;
            font-weight: 400;
            font-style: italic;
        }

        .poppins-medium-italic {
            font-family: "Poppins", sans-serif;
            font-weight: 500;
            font-style: italic;
        }

        .poppins-semibold-italic {
            font-family: "Poppins", sans-serif;
            font-weight: 600;
            font-style: italic;
        }

        .poppins-bold-italic {
            font-family: "Poppins", sans-serif;
            font-weight: 700;
            font-style: italic;
        }

        .poppins-extrabold-italic {
            font-family: "Poppins", sans-serif;
            font-weight: 800;
            font-style: italic;
        }

        .poppins-black-italic {
            font-family: "Poppins", sans-serif;
            font-weight: 900;
            font-style: italic;
        }

        div#land-area-range .ui-widget-header,
        div#land-area-range .ui-widget-header,
        .ui-widget-header {
            background: #4caf50;
        }

        .parent {
            width: 250px;
            position: fixed;
            right: -10.5rem;
        }

        .card-carousel {
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            margin-left: 8rem;
            gap: 1rem;
        }

        .card-carousel .my-card {
            height: 20rem;
            width: 12rem;
            position: relative;
            z-index: 1;
            -webkit-transform: scale(0.6) translateY(-2rem);
            transform: scale(0.6) translateY(-2rem);
            opacity: 0;
            cursor: pointer;
            pointer-events: none;
            background: #2e5266;
            background: linear-gradient(to top, #2e5266, #6e8898);
            transition: 1s;
        }

        /* .card-carousel .my-card:after {
      content: '';
      position: absolute;
      height: 2px;
      width: 100%;
      border-radius: 100%;
      background-color: rgba(0, 0, 0, 0.3);
      bottom: -5rem;
      -webkit-filter: blur(4px);
      filter: blur(4px);
    }

    .card-carousel .my-card:nth-child(0):before {
      content: '0';
      position: absolute;
      top: 50%;
      left: 50%;
      -webkit-transform: translateX(-50%) translateY(-50%);
      transform: translateX(-50%) translateY(-50%);
      font-size: 3rem;
      font-weight: 300;
      color: #fff;
    }

    .card-carousel .my-card:nth-child(1):before {
      content: '1';
      position: absolute;
      top: 50%;
      left: 50%;
      -webkit-transform: translateX(-50%) translateY(-50%);
      transform: translateX(-50%) translateY(-50%);
      font-size: 3rem;
      font-weight: 300;
      color: #fff;
    }

    .card-carousel .my-card:nth-child(2):before {
      content: '2';
      position: absolute;
      top: 50%;
      left: 50%;
      -webkit-transform: translateX(-50%) translateY(-50%);
      transform: translateX(-50%) translateY(-50%);
      font-size: 3rem;
      font-weight: 300;
      color: #fff;
    }

    .card-carousel .my-card:nth-child(3):before {
      content: '3';
      position: absolute;
      top: 50%;
      left: 50%;
      -webkit-transform: translateX(-50%) translateY(-50%);
      transform: translateX(-50%) translateY(-50%);
      font-size: 3rem;
      font-weight: 300;
      color: #fff;
    }

    .card-carousel .my-card:nth-child(4):before {
      content: '4';
      position: absolute;
      top: 50%;
      left: 50%;
      -webkit-transform: translateX(-50%) translateY(-50%);
      transform: translateX(-50%) translateY(-50%);
      font-size: 3rem;
      font-weight: 300;
      color: #fff;
    }

    .card-carousel .my-card:nth-child(5):before {
      content: '5';
      position: absolute;
      top: 50%;
      left: 50%;
      -webkit-transform: translateX(-50%) translateY(-50%);
      transform: translateX(-50%) translateY(-50%);
      font-size: 3rem;
      font-weight: 300;
      color: #fff;
    }

    .card-carousel .my-card:nth-child(6):before {
      content: '6';
      position: absolute;
      top: 50%;
      left: 50%;
      -webkit-transform: translateX(-50%) translateY(-50%);
      transform: translateX(-50%) translateY(-50%);
      font-size: 3rem;
      font-weight: 300;
      color: #fff;
    }

    .card-carousel .my-card:nth-child(7):before {
      content: '7';
      position: absolute;
      top: 50%;
      left: 50%;
      -webkit-transform: translateX(-50%) translateY(-50%);
      transform: translateX(-50%) translateY(-50%);
      font-size: 3rem;
      font-weight: 300;
      color: #fff;
    }

    .card-carousel .my-card:nth-child(8):before {
      content: '8';
      position: absolute;
      top: 50%;
      left: 50%;
      -webkit-transform: translateX(-50%) translateY(-50%);
      transform: translateX(-50%) translateY(-50%);
      font-size: 3rem;
      font-weight: 300;
      color: #fff;
    }

    .card-carousel .my-card:nth-child(9):before {
      content: '9';
      position: absolute;
      top: 50%;
      left: 50%;
      -webkit-transform: translateX(-50%) translateY(-50%);
      transform: translateX(-50%) translateY(-50%);
      font-size: 3rem;
      font-weight: 300;
      color: #fff;
    } */

        .card-carousel .my-card.active {
            z-index: 3;
            -webkit-transform: scale(1) translateY(0) translateX(0);
            transform: scale(1) translateY(0) translateX(0);
            opacity: 1;
            pointer-events: auto;
            transition: 1s;
        }

        .card-carousel .my-card.prev {
            opacity: 0.5;
            transform: scale(.8) translateY(-2.5rem) translateX(-2rem) !important;
        }

        .card-carousel .my-card.next {
            opacity: 1;
        }

        .card-carousel .my-card.prev,
        .card-carousel .my-card.next {
            z-index: 2;
            -webkit-transform: scale(0.8) translateY(-2.5rem) translateX(2rem);
            transform: scale(0.8) translateY(-2.5rem) translateX(2rem);

            pointer-events: auto;
            transition: 1s;

        }


        #splide-our-popular-properties .splide__pagination__page.is-active {
            background: #197511 !important;
        }

        #splide-our-popular-properties .splide__pagination__page {
            /* background:#197511 !important; */
        }

        #splide-our-popular-properties .splide__arrows.splide__arrows--ltr {
            display: none !important;
        }

        /******************** BLOCKUI ******************/
        .blockUI.blockMsg.blockPage b {
            width: auto;
            gap: unset;
        }

        .blockUI.blockMsg.blockPage {
            display: flex;
            flex-direction: column;
            color: black !important;
        }

        .blockUI.blockMsg.blockPage b:first-child {
            display: flex;
            justify-content: center;
            flex-direction: column;
            align-items: center;
        }

        .blockUI.blockMsg.blockPage b:nth-child(2) {
            font-weight: bold;
        }

        .blockUI.blockMsg.blockPage b:last-child {
            text-transform: capitalize;
            font-weight: normal;
        }

        .blockUI.blockMsg.blockPage .delete,
        .blockUI.blockMsg.blockPage .error {
            color: #ED1B30;
        }

        .blockUI.blockMsg.blockPage .success {
            color: #1EB200;
        }

        /* notiif */
        #notificationMenu a.is-not-read {
            border-radius: unset;
            padding: .65rem 1.5rem;
            margin: 0;
            border-bottom: 1px solid #ddd;
            font-size: 15px
        }
    </style>
</head>

<body class="overflow-x-hidden">

    @if (Request::is('search-result*') || Request::is('product*'))
        <!--- ######## Navbar White ########### ----->
        @include('layouts-landing-page.partials.navbar-white')
    @endif


    <!--- ######## Content ########### ----->

    @if (!Request::is('login'))
        @yield('content')
    @endif

    <!--- ######## Footer ########### ----->

    @if (!Request::is('login'))
        @include('layouts-landing-page.partials.footer_')
    @endif

    <!-- sweetalert2@10 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    @yield('js')

    <script>
        @if(session('success'))
            Swal.fire({
                toast: true,
                icon: 'success',
                title: "{{ session('success') }}",
                position: 'top-end',
                showConfirmButton: false,
                timer: 1500,
                timerProgressBar: true,
                willOpen: () => {
                    Swal.getPopup().style.animation = 'none';
                },
                showClass: {
                    popup: '',
                },
                hideClass: {
                    popup: '',
                }
            });
        @endif
        @if(session('error'))
            Swal.fire({
                toast: true,
                icon: 'error',
                title: "{{ session('error') }}",
                position: 'top-end',
                showConfirmButton: false,
                timer: 1500,
                timerProgressBar: true,
                willOpen: () => {
                    Swal.getPopup().style.animation = 'none';
                },
                showClass: {
                    popup: '',
                },
                hideClass: {
                    popup: '',
                }
            });
        @endif
    </script>
</body>

</html>
