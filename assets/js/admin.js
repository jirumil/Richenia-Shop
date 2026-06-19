/**
 * Richenia — admin dashboard.
 * No AJAX needed here: every panel is already server-rendered, so this
 * file only handles tab switching, populating the product form for
 * edits, and confirming destructive actions.
 */
document.addEventListener('DOMContentLoaded', function () {

  /* ---------- Sidebar tab switching ---------- */
  var navLinks = document.querySelectorAll('.admin-nav-link');
  var panels   = document.querySelectorAll('.admin-panel');

  function activateTab(name) {
    navLinks.forEach(function (link) {
      link.classList.toggle('active', link.dataset.target === name);
    });
    panels.forEach(function (panel) {
      panel.classList.toggle('is-active', panel.dataset.panel === name);
    });
  }

  navLinks.forEach(function (link) {
    link.addEventListener('click', function () {
      activateTab(link.dataset.target);
      var url = new URL(window.location);
      url.searchParams.set('tab', link.dataset.target);
      history.replaceState(null, '', url);
    });
  });

  activateTab(window.RICHENIA_ADMIN_TAB || 'dashboard');

  /* ---------- Reuse the product form for Add + Edit ---------- */
  var form        = document.getElementById('product-form');
  var formTitle    = document.getElementById('product-form-title');
  var submitBtn    = document.getElementById('product-form-submit');
  var cancelBtn    = document.getElementById('product-form-cancel');
  var idInput      = document.getElementById('product_id');

  if (form) {
    document.querySelectorAll('.product-edit-btn').forEach(function (btn) {
      btn.addEventListener('click', function () {
        idInput.value = btn.dataset.id;
        document.getElementById('p_name').value = btn.dataset.name || '';
        document.getElementById('p_category').value = btn.dataset.category || '';
        document.getElementById('p_price').value = btn.dataset.price || '';
        document.getElementById('p_stock').value = btn.dataset.stock || '';
        document.getElementById('p_image').value = btn.dataset.image || '';
        document.getElementById('p_description').value = btn.dataset.description || '';
        document.getElementById('p_featured').checked = btn.dataset.featured === '1';

        formTitle.textContent = 'Edit Product — ' + btn.dataset.name;
        submitBtn.textContent = 'Update Product';
        cancelBtn.style.display = '';

        activateTab('products');
        form.scrollIntoView({ behavior: 'smooth', block: 'start' });
      });
    });

    if (cancelBtn) {
      cancelBtn.addEventListener('click', function () {
        form.reset();
        idInput.value = '';
        formTitle.textContent = 'Add a Product';
        submitBtn.textContent = 'Add Product';
        cancelBtn.style.display = 'none';
      });
    }
  }

  /* ---------- Confirm before destructive actions ---------- */
  document.querySelectorAll('.admin-delete-form').forEach(function (deleteForm) {
    deleteForm.addEventListener('submit', function (e) {
      if (!window.confirm('This cannot be undone. Delete this item?')) {
        e.preventDefault();
      }
    });
  });
});
