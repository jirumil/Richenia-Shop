/**
 * Richenia — shop page filtering.
 * Combines live search, category pills, and a min/max price range
 * into a single AJAX call to search.php, debounced so rapid typing
 * doesn't spam the server.
 */
document.addEventListener('DOMContentLoaded', function () {
  var BASE = window.RICHENIA_BASE || '/';

  var grid        = document.getElementById('product-grid');
  var tabs         = document.querySelectorAll('.filter-tab');
  var searchInput  = document.getElementById('live-search');
  var minInput     = document.getElementById('price-min');
  var maxInput     = document.getElementById('price-max');
  var clearBtn     = document.getElementById('price-clear');
  var resultsCount = document.getElementById('results-count');

  if (!grid) return;

  var activeCategory = 'All';
  tabs.forEach(function (t) {
    if (t.classList.contains('active')) activeCategory = t.dataset.category;
  });

  var debounceTimer = null;

  function currentParams() {
    var params = new URLSearchParams();
    if (searchInput && searchInput.value.trim()) params.set('q', searchInput.value.trim());
    if (activeCategory && activeCategory !== 'All') params.set('category', activeCategory);
    if (minInput && minInput.value !== '') params.set('min_price', minInput.value);
    if (maxInput && maxInput.value !== '') params.set('max_price', maxInput.value);
    return params;
  }

  function runFilter() {
    var params = currentParams();
    grid.classList.add('is-loading');

    fetch(BASE + 'search.php?' + params.toString())
      .then(function (res) { return res.text(); })
      .then(function (html) {
        grid.innerHTML = html;
        grid.classList.remove('is-loading');
        grid.classList.add('fade-in');
        setTimeout(function () { grid.classList.remove('fade-in'); }, 450);

        if (resultsCount) {
          var count = grid.querySelectorAll('.product-card').length;
          resultsCount.textContent = count + (count === 1 ? ' piece found' : ' pieces found');
        }

        // Keep the URL bookmarkable / shareable without a full reload.
        var url = new URL(window.location);
        params.forEach(function (value, key) { url.searchParams.set(key, value); });
        ['q', 'category', 'min_price', 'max_price'].forEach(function (key) {
          if (!params.has(key)) url.searchParams.delete(key);
        });
        history.replaceState(null, '', url);
      })
      .catch(function () {
        grid.classList.remove('is-loading');
        grid.innerHTML = '<p class="empty-state">Something went wrong loading this collection.</p>';
      });
  }

  function debouncedFilter() {
    clearTimeout(debounceTimer);
    debounceTimer = setTimeout(runFilter, 300);
  }

  tabs.forEach(function (tab) {
    tab.addEventListener('click', function () {
      if (tab.classList.contains('active')) return;
      tabs.forEach(function (t) { t.classList.remove('active'); });
      tab.classList.add('active');
      activeCategory = tab.dataset.category;
      runFilter();
    });
  });

  if (searchInput) searchInput.addEventListener('input', debouncedFilter);
  if (minInput) minInput.addEventListener('input', debouncedFilter);
  if (maxInput) maxInput.addEventListener('input', debouncedFilter);

  if (clearBtn) {
    clearBtn.addEventListener('click', function () {
      if (minInput) minInput.value = '';
      if (maxInput) maxInput.value = '';
      runFilter();
    });
  }
});
