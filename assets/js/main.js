/**
 * Richenia — global JS
 * Covers: cart drawer, FAQ accordion, legal ToC active state,
 * newsletter form, and scroll-reveal animations.
 */
document.addEventListener('DOMContentLoaded', function () {
  var BASE = window.RICHENIA_BASE || '/';

  /* =====================================================
     CART DRAWER
     ===================================================== */
  var cartDrawer   = document.getElementById('cart-drawer');
  var cartOverlay  = document.getElementById('cart-overlay');
  var cartToggle   = document.getElementById('cart-toggle');
  var cartClose    = document.getElementById('cart-close');
  var cartCountEl  = document.getElementById('cart-count');
  var cartItemsEl  = document.getElementById('cart-items');
  var cartSubtotal = document.getElementById('cart-subtotal');

  function openCart() {
    if (!cartDrawer) return;
    cartDrawer.classList.add('open');
    cartOverlay.classList.add('open');
    cartDrawer.setAttribute('aria-hidden', 'false');
    document.body.classList.add('cart-lock');
  }

  function closeCart() {
    if (!cartDrawer) return;
    cartDrawer.classList.remove('open');
    cartOverlay.classList.remove('open');
    cartDrawer.setAttribute('aria-hidden', 'true');
    document.body.classList.remove('cart-lock');
  }

  if (cartToggle)  cartToggle.addEventListener('click', openCart);
  if (cartClose)   cartClose.addEventListener('click', closeCart);
  if (cartOverlay) cartOverlay.addEventListener('click', closeCart);

  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') closeCart();
  });

  function pulseBadge() {
    if (!cartCountEl) return;
    cartCountEl.classList.remove('pulse');
    void cartCountEl.offsetWidth;
    cartCountEl.classList.add('pulse');
  }

  function applyCartResponse(data) {
    if (!data) return;
    if (cartCountEl)  cartCountEl.textContent = data.count;
    if (cartSubtotal) cartSubtotal.textContent = '$' + data.subtotal;
    if (cartItemsEl && typeof data.html === 'string') cartItemsEl.innerHTML = data.html;
    pulseBadge();
  }

  function postCart(endpoint, params) {
    var body = new URLSearchParams(params);
    return fetch(BASE + endpoint, {
      method: 'POST',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      body: body.toString()
    })
      .then(function (res) { return res.json(); })
      .catch(function () {
        return { success: false, message: 'Something went wrong.' };
      });
  }

  /* Delegated click handler — covers drawer, cart page, and product cards */
  document.addEventListener('click', function (e) {

    /* Quick-add (product card) */
    var addBtn = e.target.closest('.quick-add');
    if (addBtn) {
      addBtn.classList.add('is-adding');
      postCart('api/cart_add.php', { product_id: addBtn.dataset.id, qty: 1 })
        .then(function (data) {
          applyCartResponse(data);
          openCart();
        })
        .finally(function () {
          setTimeout(function () { addBtn.classList.remove('is-adding'); }, 450);
        });
      return;
    }

    /* Qty buttons (drawer + cart page) */
    var qtyBtn = e.target.closest('.qty-btn');
    if (qtyBtn) {
      var row   = qtyBtn.closest('.cart-item, .cart-table-row');
      var qtyEl = row ? row.querySelector('.qty-value') : null;
      var qty   = qtyEl ? (parseInt(qtyEl.textContent, 10) || 1) : 1;
      qty = qtyBtn.dataset.action === 'increase' ? qty + 1 : qty - 1;

      postCart('api/cart_update.php', { product_id: qtyBtn.dataset.id, qty: qty })
        .then(function (data) {
          applyCartResponse(data);
          // If on the cart page, reload to update table totals server-side
          if (document.querySelector('.cart-page-layout') && qty <= 0) {
            window.location.reload();
          } else if (document.querySelector('.cart-page-layout')) {
            syncCartPage(data, qtyBtn.dataset.id, qty);
          }
        });
      return;
    }

    /* Remove buttons */
    var removeBtn = e.target.closest('.cart-item-remove');
    if (removeBtn) {
      var removeRow = removeBtn.closest('.cart-item, .cart-table-row');
      if (removeRow) removeRow.classList.add('removing');
      postCart('api/cart_remove.php', { product_id: removeBtn.dataset.id })
        .then(function (data) {
          applyCartResponse(data);
          if (document.querySelector('.cart-page-layout')) {
            if (removeRow) removeRow.remove();
            if (data.count === 0) window.location.reload();
            updatePageSummary(data);
          }
        });
      return;
    }
  });

  /* Sync cart page qty display without full reload */
  function syncCartPage(data, productId, newQty) {
    var row = document.querySelector('.cart-table-row[data-id="' + productId + '"]');
    if (!row) return;
    var qtyEl  = row.querySelector('.qty-value');
    var totEl  = row.querySelector('.cart-table-total');
    if (qtyEl) qtyEl.textContent = Math.max(1, newQty);
    // Update row total from server data not available directly; refresh subtotal
    updatePageSummary(data);
    if (newQty <= 0) row.remove();
  }

  function updatePageSummary(data) {
    var sub = document.getElementById('page-subtotal');
    if (sub) sub.textContent = '$' + data.subtotal;
    var total = document.getElementById('page-total');
    if (total) {
      // Mirrors includes/pricing.php::calculate_shipping() — kept in sync manually
      // since this runs client-side without a server round-trip.
      var subtotalNum = parseFloat(data.subtotal);
      var shipping    = subtotalNum >= 300 ? 0 : (subtotalNum > 0 ? 18 : 0);
      total.textContent = '$' + (subtotalNum + shipping).toFixed(2);
    }
  }

  /* =====================================================
     ACCOUNT MENU — click toggle (CSS handles hover on desktop;
     this makes it tap-friendly on touch devices too)
     ===================================================== */
  var accountMenu   = document.querySelector('.account-menu');
  var accountToggle = document.querySelector('.account-toggle');

  if (accountMenu && accountToggle) {
    accountToggle.addEventListener('click', function (e) {
      e.stopPropagation();
      accountMenu.classList.toggle('is-open');
    });
    document.addEventListener('click', function () {
      accountMenu.classList.remove('is-open');
    });
  }

  /* =====================================================
     FAQ ACCORDION
     ===================================================== */
  var faqItems = document.querySelectorAll('.faq-item');

  faqItems.forEach(function (item) {
    var btn    = item.querySelector('.faq-question');
      var answer = item.querySelector('.faq-answer');
      if (!btn || !answer) return;

    btn.addEventListener('click', function () {
      var isOpen = item.classList.contains('open');

      // Close all
      faqItems.forEach(function (other) {
        other.classList.remove('open');
        var otherBtn = other.querySelector('.faq-question');
        if (otherBtn) otherBtn.setAttribute('aria-expanded', 'false');
      });

      // Toggle current
      if (!isOpen) {
        item.classList.add('open');
        btn.setAttribute('aria-expanded', 'true');
      }
    });
  });

  /* =====================================================
     LEGAL TOC — highlight active section on scroll
     ===================================================== */
  var tocLinks = document.querySelectorAll('.legal-toc a');

  if (tocLinks.length) {
    var sections = [];
    tocLinks.forEach(function (link) {
      var id  = link.getAttribute('href').replace('#', '');
      var sec = document.getElementById(id);
      if (sec) sections.push({ id: id, el: sec, link: link });
    });

    function onScroll() {
      var scrollY = window.scrollY + 140;
      var active  = sections[0];

      sections.forEach(function (s) {
        if (s.el.offsetTop <= scrollY) active = s;
      });

      tocLinks.forEach(function (l) { l.classList.remove('active'); });
      if (active) active.link.classList.add('active');
    }

    window.addEventListener('scroll', onScroll, { passive: true });
    onScroll();
  }

  /* =====================================================
     NEWSLETTER FORM — inline feedback
     ===================================================== */
  document.querySelectorAll('.newsletter-form').forEach(function (form) {
    form.addEventListener('submit', function (e) {
      e.preventDefault();
      var input = form.querySelector('input[type="email"]');
      var btn   = form.querySelector('button');
      if (!input || !input.value) return;

      var orig = btn.textContent;
      btn.textContent = 'Subscribed ✦';
      btn.disabled    = true;
      input.value     = '';
      setTimeout(function () {
        btn.textContent = orig;
        btn.disabled    = false;
      }, 3000);
    });
  });

  /* =====================================================
     SCROLL-REVEAL — simple intersection observer for
     .philosophy-card, .team-card, .blog-card
     ===================================================== */
  if ('IntersectionObserver' in window) {
    var revealEls = document.querySelectorAll(
      '.philosophy-card, .team-card, .blog-card, .support-card, .story-stat-row'
    );

    var observer = new IntersectionObserver(function (entries) {
      entries.forEach(function (entry) {
        if (entry.isIntersecting) {
          entry.target.style.animation = 'fadeUp 0.55s var(--ease, cubic-bezier(.22,.61,.36,1)) backwards';
          observer.unobserve(entry.target);
        }
      });
    }, { threshold: 0.12 });

    revealEls.forEach(function (el) {
      el.style.opacity = '0';
      observer.observe(el);
    });
  }

});
