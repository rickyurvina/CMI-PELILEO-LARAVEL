<header class="page-header" role="banner">
    <!-- we need this logo when user switches to nav-function-top -->
    <div class="page-logo">
        <img src="{{ asset("/img/logo.png") }}" alt="{{ trans('footer.software') }}" aria-roledescription="logo">
        <span class="page-logo-text mr-1">{{ trans('footer.software') }}</span>
        <span class="position-absolute text-white opacity-50 small pos-top pos-right mr-2 mt-n2"></span>
    </div>
    <!-- DOC: nav menu layout change shortcut -->
    <div class="hidden-md-down dropdown-icon-menu position-relative">
        <a href="#" class="header-btn btn js-waves-off" data-action="toggle" data-class="nav-function-hidden" title="{{ trans('header.hide_avigation') }}">
            <i class="ni ni-menu"></i>
        </a>
        <ul>
            <li>
                <a href="#" class="btn js-waves-off" data-action="toggle" data-class="nav-function-minify" title="{{ trans('header.minify_avigation') }}">
                    <i class="ni ni-minify-nav"></i>
                </a>
            </li>
            <li>
                <a href="#" class="btn js-waves-off" data-action="toggle" data-class="nav-function-fixed" title="{{ trans('header.lock_avigation') }}">
                    <i class="ni ni-lock-nav"></i>
                </a>
            </li>
        </ul>
    </div>
    <!-- DOC: mobile button appears during mobile width -->
    <div class="hidden-lg-up">
        <a href="#" class="header-btn btn press-scale-down" data-action="toggle" data-class="mobile-nav-on">
            <i class="ni ni-menu"></i>
        </a>
    </div>
    <div class="ml-auto d-flex">
        <!-- app user menu -->
        <div>
            <a href="#" data-toggle="dropdown" title="{{ user()->name }}" class="header-icon d-flex align-items-center justify-content-center ml-2">
                @if (is_object(user()->picture))
                    <img src="{{ Storage::url(user()->picture->id) }}" class="profile-image rounded-circle" alt="{{ user()->name }}">
                @else
                    <img src="{{ asset("img/user.svg") }}" class="profile-image rounded-circle" alt="{{ user()->name }}">
                @endif
            </a>
            <div class="dropdown-menu dropdown-menu-animated dropdown-lg">
                <div class="dropdown-header bg-trans-gradient d-flex flex-row py-4 rounded-top">
                    <div class="d-flex flex-row align-items-center mt-1 mb-1 color-white">
                        <span class="mr-2">
                            @if (is_object(user()->picture))
                                <img src="{{ Storage::url(user()->picture->id) }}" class="rounded-circle profile-image" alt="{{ user()->name }}">
                            @else
                                <img src="{{ asset("img/user.svg") }}" class="rounded-circle profile-image" alt="{{ user()->name }}">
                            @endif
                        </span>
                        <div class="info-card-text">
                            <div class="fs-lg text-truncate text-truncate-lg">{{ user()->name }}</div>
                            <span class="text-truncate text-truncate-md opacity-80">{{ user()->email }}</span>
                        </div>
                    </div>
                </div>
                <div class="dropdown-divider m-0"></div>
                <a class="dropdown-item fw-500 pt-3 pb-3" href="{{ route('logout') }}">
                    <span>{{ trans('auth.logout') }} <i class="fas fa-sign-out float-right color-primary-500"></i></span>
                </a>
            </div>
        </div>
    </div>
</header>