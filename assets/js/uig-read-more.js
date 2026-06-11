/**
 * UIG Read More – Frontend JS
 *
 * Uses a single <dialog>-style modal appended to <body> so it is never
 * clipped by overflow:hidden on gallery items.
 */
(function () {
    'use strict';

    var modal      = null; // singleton modal element
    var triggerBtn = null; // button that opened the modal

    /* ------------------------------------------------------------------ */
    /*  Build modal once, append to <body>                                  */
    /* ------------------------------------------------------------------ */
    function buildModal() {
        var m = document.createElement('div');
        m.className = 'uig-readmore-modal';
        m.setAttribute('role', 'dialog');
        m.setAttribute('aria-modal', 'true');
        m.setAttribute('aria-label', 'Full description');
        m.setAttribute('hidden', '');

        m.innerHTML =
            '<div class="uig-readmore-modal-backdrop"></div>' +
            '<div class="uig-readmore-modal-box">' +
                '<button type="button" class="uig-readmore-modal-close" aria-label="Close">&times;</button>' +
                '<div class="uig-readmore-modal-body"></div>' +
            '</div>';

        document.body.appendChild(m);
        return m;
    }

    function getModal() {
        if (!modal) {
            modal = buildModal();
        }
        return modal;
    }

    /* ------------------------------------------------------------------ */
    /*  Open / close                                                         */
    /* ------------------------------------------------------------------ */
    function openModal(description, btn) {
        var m = getModal();
        m.querySelector('.uig-readmore-modal-body').textContent = description;
        m.removeAttribute('hidden');

        triggerBtn = btn || null;
        if (triggerBtn) {
            triggerBtn.setAttribute('aria-expanded', 'true');
        }

        // Focus close button for a11y
        var closeBtn = m.querySelector('.uig-readmore-modal-close');
        if (closeBtn) {
            closeBtn.focus();
        }

        document.body.style.overflow = 'hidden';
    }

    function closeModal() {
        var m = getModal();
        m.setAttribute('hidden', '');

        if (triggerBtn) {
            triggerBtn.setAttribute('aria-expanded', 'false');
            triggerBtn.focus();
        }
        triggerBtn = null;
        document.body.style.overflow = '';
    }

    /* ------------------------------------------------------------------ */
    /*  Event listeners                                                      */
    /* ------------------------------------------------------------------ */
    document.addEventListener('DOMContentLoaded', function () {

        // Open: Read More button clicked
        document.addEventListener('click', function (e) {
            var btn = e.target.closest('.uig-read-more-btn');
            if (!btn) return;
            e.preventDefault();
            e.stopPropagation(); // prevent gallery viewer from opening
            var description = btn.getAttribute('data-description') || '';
            openModal(description, btn);
        });

        // Link mode: let the anchor navigate without also opening the gallery viewer.
        document.addEventListener('click', function (e) {
            var link = e.target.closest('.uig-read-more-link');
            if (!link) return;
            e.stopPropagation();
        }, true);

        // Close: close button
        document.addEventListener('click', function (e) {
            if (!e.target.closest('.uig-readmore-modal-close')) return;
            closeModal();
        });

        // Close: backdrop click
        document.addEventListener('click', function (e) {
            if (!e.target.closest('.uig-readmore-modal-backdrop')) return;
            closeModal();
        });

        // Close: Escape key
        document.addEventListener('keydown', function (e) {
            if (e.key !== 'Escape') return;
            var m = modal;
            if (m && !m.hasAttribute('hidden')) {
                closeModal();
            }
        });

    });

})();
