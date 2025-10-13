export default () => ({
    darkMode: JSON.parse(localStorage.getItem('darkMode') ?? 'false'),

    toggleDark() {
        this.darkMode = !this.darkMode;
        localStorage.setItem('darkMode', this.darkMode);
        document.documentElement.classList.toggle('dark', this.darkMode);
        this.loadTheme();
    },

    loadTheme() {
        const theme = this.darkMode ? 'dark' : 'light';
        let link = document.getElementById('theme-css');

        if (!link) {
            link = document.createElement('link');
            link.id = 'theme-css';
            link.rel = 'stylesheet';
            document.head.appendChild(link);
        }

        link.href = `/build/assets/${theme}.css`;
    },

    init() {
        this.loadTheme();
    }
});
