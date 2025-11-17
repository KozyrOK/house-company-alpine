export default () => ({
    imageSrc: Alpine.store('assets').companyImage,
    currentLogo: null,
    companyId: null,

    async fetchCompanyLogo(id) {
        try {

            if (!id) {
                return Alpine.store('assets').companyImage;
            }

            const response = await fetch(`/api/company/${id}/logo`, { credentials: 'include' });
            if (!response.ok) throw new Error();

            const data = await response.json();

            return data.logo_url || Alpine.store('assets').companyImage;

        } catch (e) {
            console.warn('error company logo upload', e);
            return Alpine.store('assets').companyImage;
        }
    },

    async init() {
        const imgEl = this.$refs.logo;
        const defaultLogo = Alpine.store('assets').companyImage;

        this.imageSrc = defaultLogo;
        this.currentLogo = defaultLogo;

        if (Alpine.store('currentCompanyId')) {
            this.companyId = Alpine.store('currentCompanyId');
        }

        const isSuperAdmin = (document.body.dataset.role === 'superadmin');
        if (!this.companyId || isSuperAdmin) return;

        const newLogo = await this.fetchCompanyLogo(this.companyId);

        if (newLogo === this.currentLogo) return;

        imgEl.style.transition = 'opacity 0.3s ease-in-out';
        imgEl.style.opacity = '0.2';

        const preload = new Image();
        preload.src = newLogo;

        preload.onload = () => {
            setTimeout(() => {
                this.imageSrc = newLogo;
                imgEl.style.opacity = '1';
                this.currentLogo = newLogo;
            }, 100);
        };

        preload.onerror = () => {
            this.imageSrc = defaultLogo;
            imgEl.style.opacity = '1';
            this.currentLogo = defaultLogo;
        };
    },

    onImageError() {
        this.imageSrc = Alpine.store('assets').companyImage;
        this.currentLogo = this.imageSrc;
    }
});

// window.addEventListener('company-changed', e => {
//     this.companyId = e.detail.id;
//     this.init();
// });
