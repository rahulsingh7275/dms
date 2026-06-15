<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>@section('title') DSMNRU @show</title>

    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="{{ asset('assets/admin/img/dms-icon.png') }}" type="image/png">
    <link rel="stylesheet" href="{{asset('assets/admin/css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/admin/css/plugins/overlayScrollbars/css/OverlayScrollbars.min.css')}}">
    <link rel="stylesheet" href="//cdn.materialdesignicons.com/5.4.55/css/materialdesignicons.min.css">
    <link href="{{asset('assets/admin/css/font-awesome.css')}}" rel="stylesheet">
    <link href="{{asset('assets/admin/css/style.css')}}" rel="stylesheet">
    <link href="{{asset('assets/admin/css/responsive.css')}}" rel="stylesheet">
    <link href="{{asset('assets/admin/css/bootstrap-select.css')}}" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <style>
        .eyebtn {
            cursor: pointer;
        }

        #loading-image {
            background: rgb(217 214 214 / 30%);
            position: fixed;
            z-index: 999999999999999999;
            height: 100%;
            width: 100%;
            top: 0px;
            text-align: center;
        }

        #loading-image img {
            width: 30%;
        }

        .sidebar-toggle-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 42px;
            height: 42px;
            border-radius: 12px;
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.14);
            margin-right: 0.75rem;
            cursor: pointer;
        }

        .sidebar-toggle-btn img {
            width: 22px;
            height: auto;
        }
    </style>
    @yield('styles')
</head>


<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">

         @yield('content')

        @yield('form-model')

        <!-- </div> -->


        <script src="/assets/admin/js/jquery.min.js"></script>
        <script src="/assets/admin/js/jquery-ui.min.js"></script>
        <script src="/assets/admin/js/bootstrap.bundle.min.js"></script>
        <script src="/assets/admin/css/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
        <script src="/assets/admin/js/adminlte.js"></script>
        <script src="/assets/admin/js/bootstrap-select.js"></script>
        <script src="/assets/admin/js/custom-app.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

        @yield('scripts')


        </script>

</body>

</html>