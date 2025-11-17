export default () => ({

    locale: document.documentElement.lang || 'en',

    async toggleLocale() {
        const locales = ['en', 'uk', 'ru'];
        const currentIndex = locales.indexOf(this.locale);
        const nextIndex = (currentIndex + 1) % locales.length;
        const next = locales[nextIndex];

        localStorage.setItem('locale', next);
        document.documentElement.lang = next;

        try {

            const csrfToken = document
                .querySelector('meta[name="csrf-token"]')
                .getAttribute('content');

            const res = await fetch(`/locale/${next}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                },
            });

            if (!res.ok) {
                throw new Error(`Locale switch failed: ${res.status}`);
            }

            setTimeout(() => location.reload(), 120);

        } catch (err) {
            console.error('❌ Locale switch error:', err);
        }
    },

    init() {
        const saved = localStorage.getItem('locale');

        if (saved) {
            this.locale = saved;
            document.documentElement.lang = saved;
        }
    }
});
