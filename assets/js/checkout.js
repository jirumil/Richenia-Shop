/**
 * Richenia — checkout page.
 * Handles AJAX coupon validation/removal and live total updates.
 * The "Place Order" button is a plain form submission — the actual
 * discount is always recomputed server-side from the session, never
 * trusted from this script.
 */
document.addEventListener('DOMContentLoaded', function () {
  var BASE = window.RICHENIA_BASE || '/';

  var input       = document.getElementById('coupon-code');
  var applyBtn    = document.getElementById('coupon-apply-btn');
  var removeBtn   = document.getElementById('coupon-remove-btn');
  var feedback    = document.getElementById('coupon-feedback');
  var subtotalEl  = document.getElementById('checkout-subtotal');
  var discountRow = document.getElementById('checkout-discount-row');
  var discountEl  = document.getElementById('checkout-discount');
  var shippingEl  = document.getElementById('checkout-shipping');
  var totalEl     = document.getElementById('checkout-total');

  if (!input || !applyBtn) return;

  function setFeedback(message, isSuccess) {
    feedback.textContent = message || '';
    feedback.classList.toggle('is-success', !!isSuccess);
    feedback.classList.toggle('is-error', !isSuccess && !!message);
  }

  function applyTotals(data) {
    if (subtotalEl) subtotalEl.textContent = '$' + data.subtotal;
    if (shippingEl) shippingEl.textContent = (parseFloat(data.shipping) > 0) ? ('$' + data.shipping) : 'Complimentary';
    if (totalEl) totalEl.textContent = '$' + data.total;

    var discountVal = parseFloat(data.discount || '0');
    if (discountRow && discountEl) {
      if (discountVal > 0) {
        discountEl.textContent = '\u2212$' + data.discount;
        discountRow.style.display = '';
      } else {
        discountRow.style.display = 'none';
      }
    }
  }

  applyBtn.addEventListener('click', function () {
    var code = input.value.trim();
    if (!code) {
      setFeedback('Enter a coupon code first.', false);
      return;
    }

    applyBtn.disabled = true;
    applyBtn.textContent = 'Checking…';

    var body = new URLSearchParams({ code: code });

    fetch(BASE + 'api/coupon_apply.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      body: body.toString()
    })
      .then(function (res) { return res.json(); })
      .then(function (data) {
        setFeedback(data.message, data.success);
        if (data.success) {
          applyTotals(data);
          input.setAttribute('readonly', 'readonly');
          applyBtn.style.display = 'none';
          removeBtn.style.display = '';
        }
      })
      .catch(function () {
        setFeedback('Something went wrong validating that code.', false);
      })
      .finally(function () {
        applyBtn.disabled = false;
        applyBtn.textContent = 'Apply';
      });
  });

  if (removeBtn) {
    removeBtn.addEventListener('click', function () {
      fetch(BASE + 'api/coupon_remove.php', { method: 'POST' })
        .then(function (res) { return res.json(); })
        .then(function (data) {
          applyTotals(data);
          setFeedback('', false);
          input.value = '';
          input.removeAttribute('readonly');
          removeBtn.style.display = 'none';
          applyBtn.style.display = '';
        });
    });
  }

  input.addEventListener('keydown', function (e) {
    if (e.key === 'Enter') {
      e.preventDefault();
      applyBtn.click();
    }
  });

  /* Avoid a double-submit if someone double-clicks Place Order */
  var orderForm = document.getElementById('place-order-form');
  if (orderForm) {
    orderForm.addEventListener('submit', function () {
      var btn = orderForm.querySelector('button[type="submit"]');
      if (btn) {
        btn.disabled = true;
        btn.textContent = 'Placing Order…';
      }
    });
  }
});
