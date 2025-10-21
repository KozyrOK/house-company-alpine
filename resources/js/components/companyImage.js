export default () => ({
    imageSrc: Alpine.store('assets').companyImage,
    companyId: null,

    async fetchCompanyLogo(id) {
        try {
            const response = await fetch(`/api/company/${id}/logo`);
            if (!response.ok) throw new Error();
            const data = await response.json();
            this.imageSrc = data.logo_url || Alpine.store('assets').companyImage;
        } catch {
            this.imageSrc = Alpine.store('assets').companyImage;
        }
    },

    onImageError() {
        this.imageSrc = Alpine.store('assets').companyImage;
    },

    init() {
        this.imageSrc = Alpine.store('assets').companyImage;

        // global event about change company:
        // window.addEventListener('company-changed', e => this.fetchCompanyLogo(e.detail.id));
    }
});

