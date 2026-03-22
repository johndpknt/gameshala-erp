/**
 * Gameshala ERP – layout scripts
 * Bootstrap handles navbar toggler and dropdowns; add any extra behaviour here.
 */

(function () {
    'use strict';

    // Close navbar collapse when a dropdown link is clicked (mobile)
    document.querySelectorAll('#navbarMain .dropdown-item').forEach(function (el) {
        el.addEventListener('click', function () {
            var collapse = document.getElementById('navbarMain');
            if (collapse && window.innerWidth < 992) {
                var bsCollapse = bootstrap.Collapse.getOrCreateInstance(collapse);
                bsCollapse.hide();
            }
        });
    });
})();
