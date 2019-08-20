@include('includes/header')

<body class="login login-page">
    <div class="container-scroller">
        <div class="container-fluid page-body-wrapper full-page-wrapper">
            <div class="content-wrapper d-flex align-items-center auth theme-one @if(!empty($bg_class)) {{$bg_class}} @else auth-bg-1 @endif">
                <div class="row w-100">
                    <div class="col-lg-4 mx-auto">
                        @yield('main_container')
                    </div>
                </div>
            </div>
            <!-- content-wrapper ends -->
        </div>
        <!-- page-body-wrapper ends -->
    </div>

    <!-- App Scripts -->
    {{ Html::script(mix('js/app.js')) }}

    <!-- Blade Scripts -->
    @stack('scripts')
</body>
</html>