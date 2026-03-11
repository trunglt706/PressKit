<script>
    tailwind.config = {
        darkMode: 'class',
        theme: {
            extend: {
                colors: {
                    primary: '#135bec',
                    'background-light': '#ffffff',
                    'background-dark': '#101622',
                },
                fontFamily: {
                    display: ['Merriweather', 'serif'],
                    sans: ['Be Vietnam Pro', 'system-ui', 'sans-serif'],
                },
            },
        },
    };
</script>
<script>
    (function () {
        const storageKey = 'guest-theme-mode';
        const savedMode = localStorage.getItem(storageKey);
        const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
        const useDarkMode = savedMode ? savedMode === 'dark' : prefersDark;

        document.documentElement.classList.toggle('dark', useDarkMode);

        window.toggleTheme = function toggleTheme() {
            const isDark = document.documentElement.classList.toggle('dark');
            localStorage.setItem(storageKey, isDark ? 'dark' : 'light');
        };

        window.scrollToTop = function scrollToTop() {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        };

        document.addEventListener('DOMContentLoaded', function () {
            const goToTopButton = document.getElementById('goToTopButton');
            const articleImages = document.querySelectorAll('.article-lazy-image');

            const setImageReady = function (img) {
                img.classList.add('is-loaded');
                const shell = img.closest('.article-image-shell');
                if (shell) {
                    shell.classList.add('is-loaded');
                }
            };

            articleImages.forEach(function (img) {
                if (img.complete) {
                    setImageReady(img);
                    return;
                }

                img.addEventListener('load', function () {
                    setImageReady(img);
                }, { once: true });

                img.addEventListener('error', function () {
                    setImageReady(img);
                }, { once: true });
            });

            if (!goToTopButton) {
                return;
            }

            const toggleGoToTopButton = function toggleGoToTopButton() {
                const shouldShow = window.scrollY > 300;

                goToTopButton.classList.toggle('opacity-0', !shouldShow);
                goToTopButton.classList.toggle('translate-y-3', !shouldShow);
                goToTopButton.classList.toggle('pointer-events-none', !shouldShow);
            };

            window.addEventListener('scroll', toggleGoToTopButton, { passive: true });
            toggleGoToTopButton();
        });
    })();
</script>