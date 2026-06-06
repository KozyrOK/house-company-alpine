export default () => ({
    theme: window.__theme ?? 'system',
    darkMode: window.__darkMode ?? false,

    init() {
        this.applyTheme();
    },

    async toggleDark() {
        this.theme = this.darkMode ? 'light' : 'dark';
        this.darkMode = this.theme === 'dark';
        localStorage.setItem('theme', this.theme);
        this.applyTheme();

        try {
            const xsrf = document.cookie
                .split('; ')
                .find(row => row.startsWith('XSRF-TOKEN='))
                ?.split('=')[1];

            await fetch(`/theme/${this.theme}`, {
                method: 'POST',
                credentials: 'include',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-XSRF-TOKEN': decodeURIComponent(xsrf ?? ''),
                },
            });
        } catch (err) {
            console.error('❌ Theme switch error:', err);
        }
    },

    applyTheme() {
        document.documentElement.classList.toggle('dark', this.darkMode);
        const header = document.querySelector('.header-background');
        if (!header) return;

        const assets = Alpine.store('assets');
        const image = this.darkMode ? assets.headerPatternDark : assets.headerPattern;

        header.style.transition = 'background-image 0.5s ease-in-out, background-color 0.3s';
        header.style.backgroundImage = `url(${image})`;
        header.style.backgroundRepeat = 'repeat-x';
        header.style.backgroundPosition = 'top';
    }
});
