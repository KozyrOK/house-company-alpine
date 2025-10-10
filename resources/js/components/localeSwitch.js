export default () => ({
    async switchLocale() {
        const current = document.documentElement.lang;
        const next = current === 'en' ? 'uk' : 'en';
        await fetch(`/locale/${next}`, { method: 'POST' });
        location.reload();
    }
});
