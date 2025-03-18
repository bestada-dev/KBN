<!DOCTYPE html>
<html lang="en">

{{-- HEAD --}}
<head>

	{{-- META --}}
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    {{-- TITLE --}}
    {{-- <title>HSE - @yield('title', 'Web App')</title> --}}
    <title>KBN - </title>

    {{-- CSS --}}
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;1,100;1,200;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet">
    <!-- Bootstrap CSS -->
    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.5/font/bootstrap-icons.min.css">
    <!-- Select2 -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <!-- Droify -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/css/dropify.min.css">
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.1/css/dataTables.bootstrap5.min.css">
    <!-- DataTables FixedHeader CSS -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/fixedheader/3.3.1/css/fixedHeader.dataTables.min.css">
    <!--  Datepicker   -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker3.min.css">
    <!-- style.css -->
    @if(Session::get('data'))
    <link rel="stylesheet" href="{{ asset('assets/css/style-for-user-loggedin.css') }}">
    @else
    <link rel="stylesheet" href="{{ asset('assets/css/style-for-user-unloggedin.css') }}">
    @endif
   <!-- Style Global -->
    <style>

        * {
            font-family: "IBM Plex Sans", sans-serif;
        }
        /******************** BLOCKUI ******************/
        .blockUI.blockMsg.blockPage b {
            width: auto;
            gap: unset;
        }
        .blockUI.blockMsg.blockPage {
            display:flex;
            flex-direction:column;
            color:black !important;
        }
        .blockUI.blockMsg.blockPage b:first-child{
            display:flex;
            justify-content:center;
            flex-direction:column;
            align-items:center;
        }
        .blockUI.blockMsg.blockPage b:nth-child(2){
            font-weight:bold;
        }
        .blockUI.blockMsg.blockPage b:last-child{
            text-transform:capitalize;
            font-weight:normal;
        }

        .blockUI.blockMsg.blockPage .delete,.blockUI.blockMsg.blockPage .error{
        color:#ED1B30;
        }

        .blockUI.blockMsg.blockPage .success{
        color:#1EB200;
        }
    </style>

    @yield('head')
</head>
 <!--- ######## Body ########### ----->
<body>
    <main>

		@if(Session::get('data'))
            <!--- ######## Sidebar ########### ----->
			@include('layouts.partials.sidebar')
            <!--- ######## Header ########### ----->
			@include('layouts.partials.header', [
                'subtitle' => app()->view->getSections()['breadcumbSubtitle'] ?? '',
                'title' => app()->view->getSections()['breadcumbTitle'] ?? ''
            ])
		@endif

        <!--- ######## Content ########### ----->
        @yield('content')

    </main>
    {{-- JS --}}
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <!-- Global JS -->
    <script src="{{ asset('assets/js/global.js') }}"></script>

    <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
    <!-- blockUI -->
    <script src="{{ asset('assets/js/jquery.blockUI.js') }}"></script>
    <!-- Select2 -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.1/js/dataTables.bootstrap5.min.js"></script>
    <!-- DataTable FixedHeader JS -->
    <script src="https://cdn.datatables.net/fixedheader/3.3.1/js/dataTables.fixedHeader.min.js"></script>
    <!-- Dropify -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/js/dropify.min.js"></script>
    <!-- moment.js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment-with-locales.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/locale/id.min.js"></script>
    <!-- jQueryValidation -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.2/jquery.validate.min.js"></script>
    <!-- sweetalert2@10 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <!--  Datepicker   -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
    <!--  PDFObject.js PDF embedding made easy    -->
    <script src="https://unpkg.com/pdfobject"></script>
    <script>

        /******* Set global AJAX defaults *********/
        $.ajaxSetup({
            data: {
                ...getCurrentToken(),
                userLoggedIn: @json(Auth::id()) // Safely encode the PHP data
            }
        });


        /******* INIT SELECT2 *********/

        $('select').select2();
        $('select').on('select2:close', function (e) {
            $(this).valid();
        })

        /******* INIT DATEPICKER *********/

        var dp_now   = new Date();
        var dp_limit = new Date();

        dp_now.setDate(new Date().getDate());
        dp_limit.setDate(new Date().getDate() + 30);

        var dp_options = {
            format    : 'dd/mm/yyyy',
            // startDate : dp_now,
            // endDate   : dp_limit,
            setDate   : null,
            autoclose : true,
            language  : 'id',
            // datesDisabled: ['20-4-2021'],
        };

        $('#datepicker').datepicker(dp_options);
        //
        $("#datepicker").focus(function(){
            $(".datepicker").addClass('datepicker-custom-1');
        });
        // Trigger date picker on icon click
        $('#datepicker-trigger').on('click', function() {
            $('#datepicker').focus();
        });

        initializeDatepicker();



    /******* INIT DROPIFY *********/

    var drInstances = initializeDropify();
    // Rebind the 'beforeClear' event handler
    bindDropifyEvents(true);
    // Update Dropify instance settings and preview
    drInstances.each(function() {
        var drInstance = $(this).data('dropify');
        if (drInstance) {
            drInstance.settings.defaultFile = ``;
            drInstance.resetPreview();
            drInstance.clearElement();
            drInstance.init();
        }
    });
    bindDropifyEvents(false);

    /******* INIT NOTIFICATION digunakan untuk  Success Email Verfication dll*********/

    @if(Session::has('type') || Session::has('title') || Session::has('message') || Session::has('timeOutSuccess'))
        blockUI(`{{ Session::get('message') }}`, `{{ Session::get('type') }}`, `{{ Session::get('title') }}`, `{{ Session::get('timeOutSuccess') }}`)
    @endif

    /******* INI BUAT SET TOKEN USER LOGIN *********/
    @if(Session::get('data'))
        localStorage.setItem('token', `{{ Session::get('data')['token']}}`);
        localStorage.setItem('user', JSON.stringify({!! json_encode(Session::get('data')['user']) !!}))
    @else
        localStorage.clear();
    @endif


    /******* INIT CUSTOM VALIDATOR untuk jQueryValidation /*******/

    $.validator.addMethod("extension", function(value, element, param) {
        return this.optional(element) || value.match(new RegExp("\\.(" + param + ")$", "i"));
    }, "Invalid file extension.");

    $.validator.addMethod("requiredFile", function(value, element) {
        return element.files.length > 0;
    }, "Please upload a file.");

    $.validator.addMethod("pattern", function(value, element) {
        return (this.optional(element) || new RegExp(element.pattern).test(value));
    }, "Invalid input entered.");

    // $.validator.methods.pattern = function(value, element) {
    //     return (this.optional(element) || new RegExp(element.pattern).test(value));
    // };
    // $.validator.messages.pattern = "Invalid input entered.";

    /******* ALERT VERIFICATION *********/

    var PARAM_ACTION = ___getParam('par_action');

    /******* BUAT HOVER ICON MENU *********/

    const navLinks = __querySelectorAll('.nav-pills .nav-link');
    navLinks.forEach(navLink => {
        navLink.addEventListener('mouseover', () => {
            // Check if browser is Firefox
            if (navigator.userAgent.toLowerCase().indexOf('firefox') > -1) {
                navLink.querySelector('img').style.filter = 'invert(1) sepia(5) saturate(0)';
            } else { // Assume it's Chrome or another browser that supports -webkit-filter
                navLink.querySelector('img').style.webkitFilter = 'invert(0) sepia(1) saturate(1) hue-rotate(180deg)';
            }
        });

        navLink.addEventListener('mouseleave', () => {
        if (!navLink.classList.contains('active')) {
            // Reset styles on mouse leave
            navLink.querySelector('img').style.filter = '';
            navLink.querySelector('img').style.webkitFilter = '';
        }
        });

        // You can also apply styles when the link is active or shown
        if (navLink.classList.contains('active') || navLink.parentElement.classList.contains('show')) {
            if (navigator.userAgent.toLowerCase().indexOf('firefox') > -1) {
                navLink.querySelector('img').style.filter = 'invert(1) sepia(5) saturate(0)';
            } else {
                navLink.querySelector('img').style.webkitFilter = 'invert(0) sepia(1) saturate(1) hue-rotate(180deg)';
            }
        }
    });

    /******* BUAT DROPDOWN MENU SIDEBAR*********/

    __querySelectorAll('[data-bs-toggle="collapse"]').forEach(function (toggle) {
        toggle.addEventListener('click', function () {
            var target = document.querySelector(this.getAttribute('href'));
            var icon = this.querySelector('i');

            target.addEventListener('shown.bs.collapse', function () {
            icon.classList.remove('bi-caret-down-fill');
            icon.classList.add('bi-caret-up-fill');
            });

            target.addEventListener('hidden.bs.collapse', function () {
            icon.classList.remove('bi-caret-up-fill');
            icon.classList.add('bi-caret-down-fill');
            });
        });
    });

    /******* FUNCTION ADDITIONAL *********/

    function blockUI(description, type = _.SUCCESS /*'_.SUCCESS', '_.ERROR', '_.DELETE'*/, title, timeOutSucces){
        title = title ? title : type;
        timeOutSucces = timeOutSucces ? timeOutSucces : 2000;
        // debugger;

        icon = type === _.LOADING ? ___iconLoading('#000000', '50') : `<img src="{{ asset('assets/${type}.png') }}">`

        $.blockUI({
                message: `
                <b>
                ${icon}
                <b class="${type}">${title}</b>
                ${type !== _.LOADING ? `<b>${description}</b>` : ``}
                </b>
                `,
                css: {
                    border: 0,
                    color: '#fff',
                    zIndex: 99999,
                    backgroundColor: 'white',
                    cursor: 'default',
                    padding: '25px 0',
                    borderRadius: '20px',
                    minWidth: '500px',
                    top: '30%',
                    left: '32%',
                    padding:'1.8rem 1.5rem'
                },
                    overlayCSS: {
                    backgroundColor: '#000',
                    opacity: 0.5
                },
                onBlock: function() {
                    // Attach click handler after the blockUI is displayed
                    $(document).on('click.blockui', function(event) {
                        if (!$(event.target).closest('#blockui-message').length) {
                            $.unblockUI();
                        }
                    });
                },
                onUnblock: function() {
                    // Remove click handler when blockUI is removed
                    $(document).off('click.blockui');
                }
            },
        );

        setTimeout(function() {
            $.unblockUI();
        }, type !== _.ERROR ? timeOutSucces : 4000); // Unblock UI after 3 seconds
    }

    // function unblockUI(){
    //     $.unblockUI();
    // }


    function ___getParam($param){
        const url = new URLSearchParams(window.location.search);
        const par = url.get($param)
        return par;
    }

    </script>
    <script>

    // Initialize validation if needed
    $('#form-select').closest('form').validate({
        rules: {
            'form-select': {
                required: true
            }
        },
        messages: {
            'form-select': {
                required: "Please select a route."
            }
        }
    });

    $('#form-select').on('select2:select', function (e) {
        var selectedRoute = $(this).val();

        // Check if the form is valid
        if ($(this).closest('form').valid()) {
            if (selectedRoute) {
                window.location.href = selectedRoute;
            }
        }
    });
    </script>
    <script>
    function onLogout() {
        return __swalConfirmation(async (data) => {
           try {
                window.location.href='{{ url('logout') }}'
           } catch (error) {
               console.error(error);
               blockUI('Ops.. something went wrong!', _.ERROR)
           }
       }, 'Are you sure to logout?', 'You just can access the system once you are logged in. Thank you', 'Yes', 'No')

    }
    </script>
    @yield('js')

</body>

</html>
