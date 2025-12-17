export default () => ({
    imageSrc: Alpine.store('assets').defaultCompanyLogo,
    currentLogo: null,
    companyId: null,

    async fetchCompanyLogo(id) {
        try {
            if (!id) {
                return Alpine.store('assets').defaultCompanyLogo;
            }

            const response = await fetch(`/api/company/${id}/logo`, {
                credentials: 'include',
            });

            if (!response.ok) throw new Error('Logo fetch failed');

            const data = await response.json();
            return data.logo_url || Alpine.store('assets').defaultCompanyLogo;

        } catch (e) {
            console.warn('Company logo load error', e);
            return Alpine.store('assets').defaultCompanyLogo;
        }
    },

    async init() {
        const imgEl = this.$refs?.logo;
        const defaultLogo = Alpine.store('assets').defaultCompanyLogo;

        this.imageSrc = defaultLogo;
        this.currentLogo = defaultLogo;

        const currentStore = Alpine.store('current') || { companyId: null };
        this.companyId = currentStore.companyId ?? null;

        if (!this.companyId) {
            this.imageSrc = defaultLogo;
            return;
        }

        const newLogo = await this.fetchCompanyLogo(this.companyId);

        if (newLogo === this.currentLogo) return;

        if (imgEl) {
            imgEl.style.transition = 'opacity 0.3s ease-in-out';
            imgEl.style.opacity = '0.2';
        }

        const preload = new Image();
        preload.src = newLogo;

        preload.onload = () => {
            setTimeout(() => {
                this.imageSrc = newLogo;
                if (imgEl) imgEl.style.opacity = '1';
                this.currentLogo = newLogo;
            }, 100);
        };

        preload.onerror = () => {
            this.imageSrc = defaultLogo;
            if (imgEl) imgEl.style.opacity = '1';
            this.currentLogo = defaultLogo;
        };
    },

    onImageError() {
        const fallback = Alpine.store('assets').defaultCompanyLogo;
        this.imageSrc = fallback;
        this.currentLogo = fallback;
    },

    registerCompanyChangeListener() {
        window.addEventListener('company-changed', async (e) => {
            const newId = e?.detail?.id ?? null;
            this.companyId = newId;

            const current = Alpine.store('current') || {};
            current.companyId = newId;
            Alpine.store('current', current); // safe re-set
            await this.init();
        });
    },

    async start() {
        this.registerCompanyChangeListener();
        await this.init();
    }
});
