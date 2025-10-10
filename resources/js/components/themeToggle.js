export default () => ({
    darkMode: JSON.parse(localStorage.getItem('darkMode') ?? 'false'),
    toggle() {
        this.darkMode = !this.darkMode;
        localStorage.setItem('darkMode', JSON.stringify(this.darkMode));
        document.documentElement.classList.toggle('dark', this.darkMode);
    },
    init() {
        document.documentElement.classList.toggle('dark', this.darkMode);
    }
});
