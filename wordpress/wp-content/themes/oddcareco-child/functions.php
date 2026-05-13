<?php
/**
 * ODD Care Co Child Theme — Functions
 *
 * Enqueues parent (Kadence) styles and child theme overrides.
 */

// Enhance Products dropdown with rich product info
add_action('wp_footer', function () { ?>
<script>
(function () {
  var products = {
    'clear-first':       { code: 'ODD 01', name: 'Clear First',       type: 'Facewash',        price: '₹499'   },
    'foam-rinse':        { code: 'ODD 02', name: 'Foam Rinse',        type: 'Body Wash',       price: '₹449'   },
    'dawn-shield':       { code: 'ODD 03', name: 'Dawn Shield',       type: 'AM Cream',        price: '₹399'   },
    'deep-dusk':         { code: 'ODD 04', name: 'Deep Dusk',         type: 'PM Cream',        price: '₹549'   },
    'the-whole-routine': { code: 'ALL 4',  name: 'The Whole Routine', type: 'Complete Bundle', price: '₹1,499' }
  };

  document.querySelectorAll('.sub-menu .menu-item a').forEach(function (link) {
    var parts = link.href.replace(/\/$/, '').split('/');
    var slug  = parts[parts.length - 1];
    var data  = products[slug];
    if (!data) return;

    if (slug === 'the-whole-routine') {
      link.closest('li').classList.add('nav-bundle-item');
    }

    link.innerHTML =
      '<span class="nav-prod-code">' + data.code + '</span>' +
      '<span class="nav-prod-name">' + data.name + '</span>' +
      '<span class="nav-prod-meta">' +
        '<span class="nav-prod-type">'  + data.type  + '</span>' +
        '<span class="nav-prod-price">' + data.price + '</span>' +
      '</span>';
  });
})();
</script>
<?php });

// Homepage v2: scroll-reveal stagger + smooth scroll (homepage only)
add_action('wp_footer', function () {
    if (!is_front_page()) return;
    ?>
<script>
(function () {
  /* ── Smooth scroll for anchor links ── */
  document.querySelectorAll('a[href^="#"]').forEach(function (link) {
    link.addEventListener('click', function (e) {
      var target = document.querySelector(this.getAttribute('href'));
      if (target) {
        e.preventDefault();
        target.scrollIntoView({ behavior: 'smooth', block: 'start' });
      }
    });
  });

  /* ── Scroll-reveal: staggered cascade ── */
  var observer = new IntersectionObserver(function (entries) {
    entries.forEach(function (entry) {
      if (entry.isIntersecting) {
        entry.target.classList.add('revealed');
        observer.unobserve(entry.target);
      }
    });
  }, { threshold: 0.1 });

  document.querySelectorAll('.sys-card, .routine-step, .review-card, .trust-badge').forEach(function (el) {
    el.style.opacity = '0';
    el.style.transform = 'translateY(30px)';
    el.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
    observer.observe(el);
  });

  /* Stagger children within containers */
  document.querySelectorAll('.system-grid, .routine-steps, .reviews-grid, .trust-row').forEach(function (container) {
    var childObs = new IntersectionObserver(function (entries) {
      entries.forEach(function (entry) {
        if (entry.isIntersecting) {
          var children = entry.target.children;
          for (var i = 0; i < children.length; i++) {
            children[i].style.transitionDelay = (i * 0.1) + 's';
            children[i].classList.add('revealed');
          }
          childObs.unobserve(entry.target);
        }
      });
    }, { threshold: 0.1 });
    childObs.observe(container);
  });

  /* Inject revealed style */
  var s = document.createElement('style');
  s.textContent = '.revealed { opacity: 1 !important; transform: translateY(0) !important; }';
  document.head.appendChild(s);

  /* ── "THE SYSTEM" section label: add hand-underline spans ── */
  var sysLabel = document.querySelector('#wf-products');
  if (sysLabel) {
    // Wrap "THE SYSTEM" text in section-eyebrow
    var eyebrow = sysLabel.querySelector('.section-eyebrow');
    if (eyebrow) {
      var numSpan = eyebrow.querySelector('.section-num');
      var txt = eyebrow.textContent.replace(numSpan.textContent, '').trim();
      eyebrow.innerHTML = numSpan.outerHTML + ' <span class="hand-underline">' + txt + '</span>';
    }
    // Wrap "That's it." in h2
    var h2 = sysLabel.querySelector('h2');
    if (h2) {
      h2.innerHTML = '4 steps.<br><span class="hand-underline">That\u2019s it.</span>';
    }
  }

})();
</script>
<?php });

// Footer nav — Manifesto + Our Mission (non-homepage pages only; homepage has its own footer)
add_action('kadence_before_footer', function () {
    if (is_front_page()) return; // Homepage v2 has a full footer built into the page
    ?>
    <footer class="odd-footer">
        <div class="odd-footer-inner">
            <div class="odd-footer-brand">
                <span class="odd-footer-logo">ODD Care Co.</span>
                <span class="odd-footer-tagline">Skincare for people who have better things to do.</span>
            </div>
            <nav class="odd-footer-nav" aria-label="Footer navigation">
                <a href="<?php echo esc_url( home_url( '/manifesto/' ) ); ?>">Manifesto</a>
                <a href="<?php echo esc_url( home_url( '/our-mission/' ) ); ?>">Our Mission</a>
            </nav>
        </div>
    </footer>
    <?php
});

// One-time setup: Add "GET ALL 4" button + Cart icon + Account icon to header
add_action('after_setup_theme', function () {
    if (get_option('odd_header_icons_v4')) return;
    // Force re-run: clear old flag
    delete_option('odd_header_icons_v3');

    // Update header builder layout: add HTML (button + icons) to main right
    $items = get_theme_mod('header_desktop_items', []);
    if (is_array($items) && isset($items['main'])) {
        $items['main']['main_right'] = ['navigation', 'html'];
        set_theme_mod('header_desktop_items', $items);
    }

    // HTML widget: GET ALL 4 button + account icon + cart icon
    $icon_style = 'display:inline-flex;align-items:center;color:inherit;';
    $html  = '<div style="display:flex;align-items:center;gap:18px;">';
    // GET ALL 4 button
    $html .= '<a href="/the-whole-routine/" class="odd-get-all-btn">Get All 4</a>';
    // Account icon (person)
    $html .= '<a href="/my-account/" aria-label="Account" style="' . $icon_style . '">';
    $html .= '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">';
    $html .= '<circle cx="12" cy="8" r="4"/><path d="M20 21a8 8 0 1 0-16 0"/></svg></a>';
    // Cart icon (shopping bag)
    $html .= '<a href="/cart/" aria-label="Cart" style="' . $icon_style . '">';
    $html .= '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">';
    $html .= '<path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z"/><path d="M3 6h18"/><path d="M16 10a4 4 0 0 1-8 0"/></svg></a>';
    $html .= '</div>';
    set_theme_mod('header_html_content', $html);

    update_option('odd_header_icons_v4', true);
});

// Remove "Downloads" from My Account navigation (physical products only)
add_filter('woocommerce_account_menu_items', function ($items) {
    unset($items['downloads']);
    return $items;
});

// Customize My Account page dashboard greeting
add_filter('woocommerce_my_account_my_orders_columns', function ($columns) {
    unset($columns['order-actions']); // clean up — re-add below
    return $columns;
});

// Custom dashboard welcome text
add_action('woocommerce_account_dashboard', function () {
    $user = wp_get_current_user();
    $name = $user->first_name ?: $user->display_name;
    echo '<div class="odd-account-welcome">';
    echo '<p class="odd-account-eyebrow">YOUR ACCOUNT</p>';
    echo '<p class="odd-account-greeting">Hey, ' . esc_html($name) . '.</p>';
    echo '<p class="odd-account-sub">Your orders, addresses, and account details — all in one place. Nothing extra.</p>';
    echo '</div>';
}, 5);

// Hide default WooCommerce dashboard text and avatar
add_action('wp_head', function () {
    if (!is_account_page()) return;
    ?>
    <style>
    /* Hide Kadence avatar block in account sidebar */
    .woo-account-navigation-avatar,
    .kadence-account-avatar { display: none !important; }
    /* Hide default WooCommerce greeting paragraphs on dashboard */
    .woocommerce-MyAccount-content > p:first-of-type,
    .woocommerce-MyAccount-content > p:nth-of-type(2) { display: none; }
    /* Style custom welcome block */
    .odd-account-welcome { margin-bottom: 2rem; }
    .odd-account-eyebrow { font-size: 10px; text-transform: uppercase; letter-spacing: 0.16em; color: #9CAF88; font-weight: 500; margin-bottom: 0.5rem; }
    .odd-account-greeting { font-size: 28px; font-weight: 600; color: #1a1a1a; letter-spacing: -0.02em; margin-bottom: 0.5rem; line-height: 1.2; }
    .odd-account-sub { font-size: 14px; color: #555; line-height: 1.6; }
    </style>
    <?php
});

// Enqueue parent and child theme styles + Google Fonts
add_action('wp_enqueue_scripts', function () {
    // Google Fonts: Caveat (handwritten style for homepage)
    wp_enqueue_style(
        'oddcareco-google-fonts',
        'https://fonts.googleapis.com/css2?family=Caveat:wght@400;600&display=swap',
        [],
        null
    );

    // Parent theme style
    wp_enqueue_style(
        'kadence-parent-style',
        get_template_directory_uri() . '/style.css',
        [],
        wp_get_theme('kadence')->get('Version')
    );

    // Child theme style (loads after parent)
    wp_enqueue_style(
        'oddcareco-child-style',
        get_stylesheet_directory_uri() . '/style.css',
        ['kadence-parent-style'],
        wp_get_theme()->get('Version')
    );
});

// Disable wpautop on homepage (page 26) — it breaks inline <style> blocks
add_filter('the_content', function ($content) {
    if (is_page(26)) {
        remove_filter('the_content', 'wpautop');
    }
    return $content;
}, 9);
