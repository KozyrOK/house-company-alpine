export default () => ({
    locale: document.documentElement.lang || 'en',

    toggleLocale() {
        const next = this.locale === 'en' ? 'uk' : 'en';
        localStorage.setItem('locale', next);
        document.documentElement.lang = next;

        fetch(`/locale/${next}`, { method: 'POST' })
            .then(() => location.reload())
            .catch(console.error);
    },

    init() {

        const saved = localStorage.getItem('locale');
        if (saved) {
            this.locale = saved;
            document.documentElement.lang = saved;
        }
    }
});
