if (window.localStorage) {
    $('body').on('expanded.pushMenu collapsed.pushMenu', function (e) {
        localStorage.setItem('sidebar_state', e.type);
    });

    (function () {
        var state = localStorage.getItem('sidebar_state');
        if (state && state == 'expanded') {
            $('body').removeClass('sidebar-collapse');
            if ($(window).width() <= ($.AdminLTE.options.screenSizes.sm - 1)) {
                $('body').addClass('sidebar-open');
            }
        }
    })();
}