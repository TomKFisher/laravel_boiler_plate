<!-- partial:partials/header.html -->
@include('includes/header')

<body>
    <div class="container-scroller">
        <!-- partial:partials/topbar.blade.php -->
        @include('includes/topbar')
        <!-- partial -->
        <div class="container-fluid page-body-wrapper">
            <!-- partial:partials/sidebar.blade.php -->
            @include('includes/sidebar')
            <!-- partial -->
            <div class="main-panel">
                <div class="content-wrapper">
                    <!-- yeild page_header -->
                    @yield('page_header')
                    <!-- yeild -->
                    <!-- partial:partials/flash.blade.php -->
                    @include('includes/flash')
                    <!-- partial -->
                    @yield('main_container')
                    <!-- yeild page_header -->
                    @yield('page_footer')
                    <!-- yeild -->
                </div>
                <!-- content-wrapper ends -->
                <!-- partial:partials/footer.blade.php -->
                @include('includes/footer')
                <!-- partial -->
            </div>
            <!-- main-panel ends -->
        </div>
        <!-- container-fluid page-body-wrapper ends -->
    </div>
    <!-- container-scroller ends -->

    <!-- App Scripts -->
    {{ Html::script(mix('js/app.js')) }}

    <!-- Blade Scripts -->
    @stack('scripts')
</body>
</html>