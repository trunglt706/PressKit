document.addEventListener('DOMContentLoaded', function () {
    const nav = document.getElementById('policy-nav');
    if (!nav) {
        return;
    }

    const offsetTop = 80;
    const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    const navLinks = Array.from(nav.querySelectorAll('a[href^="#"]'));
    const sections = navLinks
        .map((link) => document.querySelector(link.getAttribute('href')))
        .filter(Boolean);

    const setActiveLink = function (hash) {
        navLinks.forEach((link) => {
            const isActive = link.getAttribute('href') === hash;
            link.classList.toggle('is-active', isActive);
            link.setAttribute('aria-current', isActive ? 'true' : 'false');
        });
    };

    const initialHash = window.location.hash;
    if (initialHash && nav.querySelector('a[href="' + initialHash + '"]')) {
        setActiveLink(initialHash);
    }

    navLinks.forEach((link) => {
        link.addEventListener('click', function (event) {
            const hash = link.getAttribute('href');
            const target = hash ? document.querySelector(hash) : null;
            if (!target) {
                return;
            }

            event.preventDefault();
            setActiveLink(hash);

            const targetTop = target.getBoundingClientRect().top + window.pageYOffset - offsetTop;
            window.scrollTo({
                top: Math.max(targetTop, 0),
                behavior: prefersReducedMotion ? 'auto' : 'smooth',
            });

            history.replaceState(null, '', hash);
        });
    });

    if ('IntersectionObserver' in window && sections.length > 0) {
        const observer = new IntersectionObserver(
            (entries) => {
                const visibleEntry = entries
                    .filter((entry) => entry.isIntersecting)
                    .sort((a, b) => a.boundingClientRect.top - b.boundingClientRect.top)[0];

                if (visibleEntry && visibleEntry.target.id) {
                    setActiveLink('#' + visibleEntry.target.id);
                }
            },
            {
                root: null,
                rootMargin: '-110px 0px -55% 0px',
                threshold: 0.15,
            }
        );

        sections.forEach((section) => observer.observe(section));
    }
});
