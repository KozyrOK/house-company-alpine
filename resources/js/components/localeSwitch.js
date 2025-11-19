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
            const xsrf = document.cookie
                .split('; ')
                .find(row => row.startsWith('XSRF-TOKEN='))
                ?.split('=')[1];

            const res = await fetch(`/locale/${next}`, {
                method: 'POST',
                credentials: 'include',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-XSRF-TOKEN': decodeURIComponent(xsrf),
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
