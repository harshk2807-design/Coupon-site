/* Coupon site JavaScript: Show Code popup, coupon filters, categories menu. */
(function () {
  var modal = document.getElementById('code-modal');

  function showCode(title, code) {
    if (!modal) return;
    document.getElementById('code-modal-title').textContent = title || '';
    document.getElementById('code-modal-code').textContent = code || '';
    modal.hidden = false;
  }

  document.addEventListener('click', function (e) {
    /* "Show Code" works like worthepenny:
       1. NEW tab  -> this store's page with ?offer=ID (shows the code popup)
       2. CURRENT tab -> the store / affiliate link */
    var btn = e.target.closest('[data-reveal]');
    if (btn) {
      var go = btn.dataset.go;
      if (!go) { showCode(btn.dataset.title, btn.dataset.code); return; }
      var page = new URL(btn.dataset.page, window.location.href);
      page.searchParams.set('offer', btn.dataset.id);
      var tab = window.open(page.toString(), '_blank');
      if (tab) window.location.href = go;
      else showCode(btn.dataset.title, btn.dataset.code); // pop-up blocked
      return;
    }

    if (e.target.closest('[data-modal-close]') || e.target === modal) { modal.hidden = true; return; }

    var copy = e.target.closest('[data-copy]');
    if (copy) {
      var text = document.getElementById('code-modal-code').textContent;
      var done = function () { copy.textContent = 'Copied!'; setTimeout(function () { copy.textContent = 'Copy'; }, 1500); };
      if (navigator.clipboard) navigator.clipboard.writeText(text).then(done, done); else done();
      return;
    }

    // Categories menu: click to open/close (for phones)
    var toggle = e.target.closest('[data-menu-toggle]');
    var menu = document.querySelector('[data-menu]');
    if (toggle) { menu.classList.toggle('is-open'); toggle.setAttribute('aria-expanded', menu.classList.contains('is-open')); return; }
    if (menu && !e.target.closest('[data-menu]')) menu.classList.remove('is-open');
  });

  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape' && modal) modal.hidden = true;
  });

  // Page opened with ?offer=ID -> show that coupon's code
  var offer = new URLSearchParams(window.location.search).get('offer');
  if (offer) {
    var b = document.querySelector('[data-reveal][data-id="' + CSS.escape(offer) + '"]');
    if (b) showCode(b.dataset.title, b.dataset.code);
  }

  // Store page filter: All / Coupon Codes / Online Sales
  document.addEventListener('change', function (e) {
    if (!e.target.closest('[data-filters]')) return;
    var v = e.target.value;
    document.querySelectorAll('.coupon-list .coupon').forEach(function (c) {
      c.style.display = (v === 'all' || c.dataset.type === v) ? '' : 'none';
    });
  });
})();
