export default () => ({
    darkMode: window.__darkMode ?? (typeof localStorage !== 'undefined' && localStorage.getItem('darkMode') === 'true') ?? false,

    init() {
        this.applyTheme();
    },

    toggleDark() {
        this.darkMode = !this.darkMode;
        localStorage.setItem('darkMode', this.darkMode);
        this.applyTheme();
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
