<!-- Vendor -->
<script src="{{ asset("$asset_template/js/vendors.bundle.js") }}"></script>
<script src="{{ asset("$asset_template/js/app.bundle.js") }}"></script>
<script src="{{ asset("$asset_template/js/notifications/sweetalert2/sweetalert2.bundle.js") }}"></script>
<script src="{{ asset("$asset_template/js/statistics/easypiechart/easypiechart.bundle.js") }}"></script>
<script src="https://cdn.amcharts.com/lib/4/core.js"></script>
<script src="https://cdn.amcharts.com/lib/4/charts.js"></script>
<script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.8.2/dist/alpine.min.js" defer></script>

<script>
    'use strict';

    var classHolder = document.getElementsByTagName("BODY")[0],
        /**
         * Load from localstorage
         **/
        themeSettings = (localStorage.getItem('themeSettings')) ? JSON.parse(localStorage.getItem('themeSettings')) :
            {},
        themeURL = themeSettings.themeURL || '',
        themeOptions = themeSettings.themeOptions || '';

    myapp_config.debugState = false;
</script>

@stack('vendor_js')

@livewireScripts

<!-- Core -->
<script src="{{ asset("/js/app.js") }}"></script>

@stack('page_script')
