<!DOCTYPE html>

<html lang="en">
<!--begin::Head-->

<head>
    <base href="">
    <title>Rozy</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta charset="utf-8" />
    <meta name="description"
        content="The most advanced Bootstrap Admin Theme on Themeforest trusted by 94,000 beginners and professionals. Multi-demo, Dark Mode, RTL support and complete React, Angular, Vue &amp; Laravel versions. Grab your copy now and get life-time updates for free." />
    <meta name="keywords"
        content="Metronic, bootstrap, bootstrap 5, Angular, VueJs, React, Laravel, admin themes, web design, figma, web development, free templates, free admin themes, bootstrap theme, bootstrap template, bootstrap dashboard, bootstrap dak mode, bootstrap button, bootstrap datepicker, bootstrap timepicker, fullcalendar, datatables, flaticon" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta property="og:locale" content="en_US" />
    <meta property="og:type" content="article" />
    <meta property="og:title"
        content="Metronic - Bootstrap 5 HTML, VueJS, React, Angular &amp; Laravel Admin Dashboard Theme" />
    <meta property="og:url" content="https://keenthemes.com/metronic" />
    <meta property="og:site_name" content="Keenthemes | Metronic" />
    <link rel="canonical" href="https://preview.keenthemes.com/metronic8" />
    <link rel="shortcut icon" href="/assets/metronic/media/logos/favicon.ico" />
    <!--begin::Fonts-->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" />
    <!--end::Fonts-->
    <!--begin::Page Vendor Stylesheets(used by this page)-->
    <link href="/assets/metronic/plugins/custom/fullcalendar/fullcalendar.bundle.css" rel="stylesheet"
        type="text/css" />
    <!--end::Page Vendor Stylesheets-->
    <link href="/assets/metronic/plugins/custom/datatables/datatables.bundle.css" rel="stylesheet" type="text/css" />

    <!--begin::Global Stylesheets Bundle(used by all pages)-->
    <link href="/assets/metronic/plugins/global/plugins.bundle.css" rel="stylesheet" type="text/css" />
    <link href="/assets/metronic/css/style.bundle.css" rel="stylesheet" type="text/css" />
    <!--end::Global Stylesheets Bundle-->
</head>
<!--end::Head-->
<!--begin::Body-->

<body id="kt_body" class="header-fixed header-tablet-and-mobile-fixed toolbar-enabled">


    <div class="d-flex flex-column flex-root">
        <!--begin::Page-->
        <div class="page d-flex flex-row flex-column-fluid">
            <!--begin::Wrapper-->
            <div class="wrapper d-flex flex-column flex-row-fluid" id="kt_wrapper">

                @include('admin.layouts.header')

                @yield('content')

                @include('admin.layouts.footer')

            </div>
        </div>
    </div>



    <!--end::Main-->
    <script>
        var hostUrl = "/assets/metronic/";
    </script>
    <!--begin::Javascript-->
    <!--begin::Global Javascript Bundle(used by all pages)-->
    <script src="/assets/metronic/plugins/global/plugins.bundle.js"></script>
    <script src="/assets/metronic/js/scripts.bundle.js"></script>
    <!--end::Global Javascript Bundle-->
    <!--begin::Page Vendors Javascript(used by this page)-->
    <script src="/assets/metronic/plugins/custom/fullcalendar/fullcalendar.bundle.js"></script>
    <!--end::Page Vendors Javascript-->
    <!--begin::Page Custom Javascript(used by this page)-->
    <script src="/assets/metronic/plugins/custom/datatables/datatables.bundle.js"></script>

    {{-- <script src="/assets/metronic/js/custom/widgets.js"></script>
		<script src="/assets/metronic/js/custom/apps/chat/chat.js"></script> --}}
    {{-- <script src="/assets/metronic/js/custom/modals/create-app.js"></script>
		<script src="/assets/metronic/js/custom/modals/upgrade-plan.js"></script> --}}
    <!--end::Page Custom Javascript-->
    <!--end::Javascript-->

    {{-- Menu --}}
    @if (!session('menu'))
        <script>
            var loadingMenu =
                "<button class='btn btn-primary' type='button' disabled><span class='spinner-border spinner-border-sm' role='status' aria-hidden='true'></span>Loading...</button>"

            $('#kt_header_menu').html(loadingMenu)

            menu();

            function menu() {
                $.get("{{ url('/admin/menu/loadmenu/0') }}/" + {{ auth()->user()->role_id }}, {}, function(data, status) {
                    $('#kt_header_menu').html(data)
                })
            }
        </script>
    @endif
    {{-- End Menu --}}


    @yield('js')
</body>
<!--end::Body-->

</html>
