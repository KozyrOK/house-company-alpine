export default () => ({
    locale: document.documentElement.lang || 'en',

    toggleLocale() {
        const locales = ['en', 'uk', 'ru'];
        const currentIndex = locales.indexOf(this.locale);
        const nextIndex = (currentIndex + 1) % locales.length;
        const next = locales[nextIndex];

        localStorage.setItem('locale', next);
        document.documentElement.lang = next;

        fetch(`/locale/${next}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json',
            },
        })
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
