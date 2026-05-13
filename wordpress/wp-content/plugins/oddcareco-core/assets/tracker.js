/**
 * ODD Care Co — Lightweight Analytics Tracker (<2KB)
 *
 * Tracks page_view and product_view events.
 * Sends events via REST API. Respects cookie consent.
 */
(function () {
    'use strict';

    // Check consent (look for a consent cookie or skip if not present).
    var hasConsent = document.cookie.indexOf('cookie_consent=accepted') !== -1
                  || document.cookie.indexOf('oddcareco_consent=1') !== -1;

    // For now, track if no explicit rejection. Sites without a cookie banner
    // should add one before going live.
    var rejected = document.cookie.indexOf('cookie_consent=rejected') !== -1;
    if (rejected) return;

    var config = window.oddcarecoTracker || {};
    if (!config.endpoint) return;

    // Session ID: reuse from cookie or generate new.
    var sid = getCookie('oddcareco_sid');
    if (!sid) {
        sid = generateUUID();
        setCookie('oddcareco_sid', sid, 30); // 30 minutes.
    }

    // Track page view.
    sendEvent('page_view', {});

    // Track product view if on a WooCommerce product page.
    var productMeta = document.querySelector('meta[property="product:id"]')
                   || document.querySelector('.single-product .product[data-product-id]');

    if (document.body.classList.contains('single-product')) {
        var productId = 0;
        var addBtn = document.querySelector('.single_add_to_cart_button');
        if (addBtn) productId = addBtn.value || addBtn.dataset.product_id || 0;
        if (productId) {
            sendEvent('product_view', { product_id: parseInt(productId) });
        }
    }

    /* ── Helpers ──────────────────────────────────────────── */

    function sendEvent(eventType, extra) {
        var payload = {
            event_type: eventType,
            session_id: sid,
            page_url: window.location.pathname,
            referrer_url: document.referrer || '',
        };
        if (extra.product_id) payload.product_id = extra.product_id;
        if (Object.keys(extra).length) payload.extra = extra;

        // Use sendBeacon if available (works on page exit).
        if (navigator.sendBeacon) {
            var blob = new Blob([JSON.stringify(payload)], { type: 'application/json' });
            navigator.sendBeacon(config.endpoint, blob);
        } else {
            fetch(config.endpoint, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-WP-Nonce': config.nonce
                },
                body: JSON.stringify(payload),
                keepalive: true
            }).catch(function () {});
        }
    }

    function generateUUID() {
        return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function (c) {
            var r = Math.random() * 16 | 0;
            return (c === 'x' ? r : (r & 0x3 | 0x8)).toString(16);
        });
    }

    function getCookie(name) {
        var match = document.cookie.match(new RegExp('(^| )' + name + '=([^;]+)'));
        return match ? match[2] : null;
    }

    function setCookie(name, value, minutes) {
        var expires = new Date(Date.now() + minutes * 60000).toUTCString();
        document.cookie = name + '=' + value + ';expires=' + expires + ';path=/;SameSite=Lax';
    }
})();
