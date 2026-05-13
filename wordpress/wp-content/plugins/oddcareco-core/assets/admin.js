/**
 * ODD Care Co — Admin Dashboard JS
 *
 * Handles interactive elements in the admin dashboard.
 */
(function () {
    'use strict';

    // Animate funnel bars on page load.
    document.addEventListener('DOMContentLoaded', function () {
        var bars = document.querySelectorAll('.funnel-bar');
        bars.forEach(function (bar) {
            var targetWidth = bar.style.width;
            bar.style.width = '0%';
            requestAnimationFrame(function () {
                requestAnimationFrame(function () {
                    bar.style.width = targetWidth;
                });
            });
        });
    });
})();
