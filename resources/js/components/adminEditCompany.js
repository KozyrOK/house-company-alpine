export default function adminEditCompany(companyId) {
    return {
        companyId,
        company: {},
        loading: true,

        form: {
            name: '',
            address: '',
            city: '',
            description: '',
        },

        file: null,
        previewUrl: null,
        removeExisting: false,

        async init() {
            await this.fetchCompany();
        },

        async fetchCompany() {
            try {
                const res = await fetch(`/api/admin/${this.companyId}`, {
                    credentials: 'include',
                });

                const data = await res.json();
                this.company = data.data ?? data;

                this.form = {
                    name: this.company.name,
                    address: this.company.address,
                    city: this.company.city,
                    description: this.company.description,
                };

            } catch (e) {
                console.error('Error loading:', e);
            } finally {
                this.loading = false;
            }
        },

        handleFileUpload(event) {
            this.file = event.target.files[0];
            this.removeExisting = false;

            if (this.file) {
                this.previewUrl = URL.createObjectURL(this.file);
            }
        },

        clearPreview() {
            this.file = null;
            this.previewUrl = null;
        },

        removeLogo() {
            this.removeExisting = true;
            this.previewUrl = '/images/default-image-company.jpg';
        },

        async save() {
            try {

                const res = await fetch(`/api/admin/${this.companyId}`, {
                    method: 'PATCH',
                    credentials: 'include',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(this.form),
                });

                if (!res.ok) throw new Error('Failed to update');

                if (this.file) {
                    const fd = new FormData();
                    fd.append('logo', this.file);

                    const uploadRes = await fetch(`/api/admin/${this.companyId}/logo`, {
                        method: 'POST',
                        credentials: 'include',
                        body: fd,
                    });

                    if (!uploadRes.ok) throw new Error('Logo upload failed');
                }

                if (this.removeExisting && !this.file) {
                    await fetch(`/api/admin/${this.companyId}/logo`, {
                        method: 'DELETE',
                        credentials: 'include',
                    });
                }

                window.location.href = `/admin/companies/${this.companyId}`;

            } catch (e) {
                console.error(e);
                alert('Error saving company');
            }
        },

    };
}
