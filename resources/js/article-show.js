document.addEventListener('DOMContentLoaded', function () {
    // ── Share button ──────────────────────────────────────────────────────────
    const btnShare = document.querySelector('.btn-share');

    if (btnShare) {
        const shareUrl     = window.location.href;
        const shareTitle   = document.title;

        const channels = [
            {
                label : 'Facebook',
                icon  : '<svg viewBox="0 0 24 24" fill="currentColor" class="h-4 w-4"><path d="M22 12c0-5.52-4.48-10-10-10S2 6.48 2 12c0 4.99 3.66 9.12 8.44 9.88v-6.99H7.9v-2.89h2.54V9.77c0-2.5 1.49-3.89 3.77-3.89 1.09 0 2.24.2 2.24.2v2.46h-1.26c-1.24 0-1.63.77-1.63 1.56V12h2.77l-.44 2.89h-2.33v6.99C18.34 21.12 22 17 22 12z"/></svg>',
                href  : `https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(shareUrl)}`,
            },
            {
                label : 'X (Twitter)',
                icon  : '<svg viewBox="0 0 24 24" fill="currentColor" class="h-4 w-4"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>',
                href  : `https://twitter.com/intent/tweet?url=${encodeURIComponent(shareUrl)}&text=${encodeURIComponent(shareTitle)}`,
            },
            {
                label : 'LinkedIn',
                icon  : '<svg viewBox="0 0 24 24" fill="currentColor" class="h-4 w-4"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433a2.062 2.062 0 0 1-2.063-2.065 2.064 2.064 0 1 1 2.063 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>',
                href  : `https://www.linkedin.com/shareArticle?mini=true&url=${encodeURIComponent(shareUrl)}&title=${encodeURIComponent(shareTitle)}`,
            },
            {
                label : 'WhatsApp',
                icon  : '<svg viewBox="0 0 24 24" fill="currentColor" class="h-4 w-4"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51a12.8 12.8 0 0 0-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 0 1-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 0 1-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 0 1 2.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0 0 12.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 0 0 5.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 0 0-3.48-8.413z"/></svg>',
                href  : `https://wa.me/?text=${encodeURIComponent(shareTitle + ' ' + shareUrl)}`,
            },
        ];

        // Build the dropdown panel (hidden by default)
        const dropdown = document.createElement('div');
        dropdown.id = 'share-dropdown';
        dropdown.className = [
            'absolute z-50 mt-2 w-44 rounded-xl border border-slate-200 bg-white py-1.5 shadow-lg',
            'dark:border-slate-700 dark:bg-slate-900',
            'hidden',
        ].join(' ');
        dropdown.setAttribute('role', 'menu');

        channels.forEach(function (ch) {
            const item = document.createElement('a');
            item.href        = ch.href;
            item.target      = '_blank';
            item.rel         = 'noopener noreferrer';
            item.className   = 'flex items-center gap-3 px-4 py-2 text-sm text-slate-700 hover:bg-slate-100 dark:text-slate-200 dark:hover:bg-slate-800';
            item.setAttribute('role', 'menuitem');
            item.innerHTML   = ch.icon + '<span>' + ch.label + '</span>';
            dropdown.appendChild(item);
        });

        // Position dropdown relative to the share button's parent
        const shareWrapper = document.createElement('div');
        shareWrapper.className = 'relative inline-block';
        btnShare.parentNode.insertBefore(shareWrapper, btnShare);
        shareWrapper.appendChild(btnShare);
        shareWrapper.appendChild(dropdown);

        btnShare.addEventListener('click', function (e) {
            e.stopPropagation();
            const isOpen = !dropdown.classList.contains('hidden');
            dropdown.classList.toggle('hidden', isOpen);
            btnShare.setAttribute('aria-expanded', String(!isOpen));
        });

        // Close when clicking outside
        document.addEventListener('click', function () {
            dropdown.classList.add('hidden');
            btnShare.setAttribute('aria-expanded', 'false');
        });

        dropdown.addEventListener('click', function (e) {
            e.stopPropagation();
        });
    }

    // ── Copy link button ──────────────────────────────────────────────────────
    const btnCopy = document.querySelector('.btn-copy-link');

    if (btnCopy) {
        const iconEl    = btnCopy.querySelector('.material-symbols-outlined');
        const origIcon  = iconEl ? iconEl.textContent.trim() : 'link';

        btnCopy.addEventListener('click', function () {
            const url = window.location.href;

            if (!navigator.clipboard) {
                // Fallback for non-secure contexts
                const ta = document.createElement('textarea');
                ta.value = url;
                ta.style.cssText = 'position:fixed;top:-9999px;left:-9999px;opacity:0';
                document.body.appendChild(ta);
                ta.select();
                document.execCommand('copy');
                document.body.removeChild(ta);
                showCopyFeedback();
                return;
            }

            navigator.clipboard.writeText(url).then(showCopyFeedback).catch(function () {
                console.warn('Không thể sao chép liên kết.');
            });
        });

        function showCopyFeedback() {
            if (iconEl) {
                iconEl.textContent = 'check';
            }
            btnCopy.classList.add('bg-green-100', 'text-green-700', 'dark:bg-green-900/40', 'dark:text-green-400');
            btnCopy.title = 'Đã sao chép!';

            setTimeout(function () {
                if (iconEl) {
                    iconEl.textContent = origIcon;
                }
                btnCopy.classList.remove('bg-green-100', 'text-green-700', 'dark:bg-green-900/40', 'dark:text-green-400');
                btnCopy.title = '';
            }, 2000);
        }
    }
});
