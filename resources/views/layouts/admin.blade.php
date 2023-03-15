<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

    @include('partials.admin.head')

    <body class="mod-bg-1 header-function-fixed mod-clean-page-bg nav-function-top nav-function-fixed">

        <div class="page-wrapper">

            <div class="page-inner">

                @include('partials.admin.sidebar')

                <div class="page-content-wrapper">

                    @include('partials.admin.header')

                    <main id="js-page-content" role="main" class="page-content">

                        <div class="subheader">

                            <h1 class="subheader-title">

                                @yield('subheader-title')

                            </h1>

                            <div class="subheader-block d-lg-flex align-items-center">

                                <div class="d-inline-flex flex-column justify-content-center mr-3">

                                    @yield('subheader-right')

                                </div>

                            </div>

                        </div>

                        @yield('content')

                    </main>

                    <!-- this overlay is activated only when mobile menu is triggered -->
                    <div class="page-content-overlay" data-action="toggle" data-class="mobile-nav-on"></div>

                    @include('partials.admin.colors')

                </div>

            </div>

        </div>

        @include('partials.admin.scripts')

    </body>

</html>
