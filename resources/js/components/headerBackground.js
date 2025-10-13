export default () => ({
    light: Alpine.store('assets').headerPattern,
    dark: Alpine.store('assets').headerPatternDark,

    get image() {
        return document.documentElement.classList.contains('dark') ? this.dark : this.light;
    }
});
