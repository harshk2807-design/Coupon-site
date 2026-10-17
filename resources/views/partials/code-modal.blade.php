{{-- Popup that shows the coupon code (filled in by public/js/site.js) --}}
<div class="modal" id="code-modal" hidden>
    <div class="modal__box" role="dialog" aria-modal="true" aria-labelledby="code-modal-title">
        <button type="button" class="modal__close" data-modal-close aria-label="Close">×</button>
        <h3 id="code-modal-title"></h3>
        <p>Copy this code and paste it at checkout:</p>
        <div class="modal__code">
            <span id="code-modal-code"></span>
            <button type="button" class="btn" data-copy>Copy</button>
        </div>
        <p class="muted small">The store has opened in your other tab.</p>
    </div>
</div>
