document.addEventListener('DOMContentLoaded', () => {
    // 1. Active Page Highlight Logic
    const page = document.body.dataset.page;
    document.querySelectorAll('.menu [data-page]').forEach(a => {
        if (a.dataset.page === page) a.classList.add('active');
    });

    // 2. Theme Selection Logic
    const settingsBtn = document.getElementById('settings-btn');
    const themeMenu = document.getElementById('theme-menu');
    const body = document.body;

    // Toggle Menu
    if (settingsBtn && themeMenu) {
        settingsBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            themeMenu.classList.toggle('show');
        });

        // Close menu when clicking outside
        document.addEventListener('click', () => {
            themeMenu.classList.remove('show');
        });
    }

    // Set Initial Theme
    const currentTheme = localStorage.getItem('theme') || 'light';
    if (currentTheme === 'dark') {
        body.setAttribute('data-theme', 'dark');
    }

    // Theme Switching
    document.querySelectorAll('.theme-option').forEach(option => {
        option.addEventListener('click', function() {
            const theme = this.dataset.theme;
            if (theme === 'dark') {
                body.setAttribute('data-theme', 'dark');
            } else {
                body.removeAttribute('data-theme');
            }
            localStorage.setItem('theme', theme);
            themeMenu.classList.remove('show');
        });
    });
});
