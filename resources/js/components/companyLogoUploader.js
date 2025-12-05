export default function companyLogoUploader(companyId) {
    return {
        companyId,
        file: null,
        preview: null,
        message: null,
        loading: false,

        init() {

        },

        onFileChange(e) {
            const f = e.target.files[0];
            if (!f) return;
            // basic client validation
            if (!f.type.startsWith('image/')) {
                this.message = 'Please select image file';
                return;
            }
            this.file = f;
            // preview
            const reader = new FileReader();
            reader.onload = (ev) => {
                this.preview = ev.target.result;
            };
            reader.readAsDataURL(f);
        },

        async upload() {
            if (!this.file) {
                this.message = 'No file selected';
                return;
            }

            this.loading = true;
            this.message = null;

            try {
                const fd = new FormData();
                fd.append('logo', this.file);

                const res = await fetch(`/api/admin/${this.companyId}/logo`, {
                    method: 'POST',
                    credentials: 'include',
                    body: fd,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        // Do NOT set Content-Type — browser sets boundary
                    },
                });

                const data = await res.json();

                if (!res.ok) {
                    this.message = data.message || (data.errors ? JSON.stringify(data.errors) : 'Upload failed');
                } else {
                    this.message = 'Uploaded';
                    window.dispatchEvent(new CustomEvent('company-updated', { detail: { id: this.companyId } }));
                }
            } catch (e) {
                console.error(e);
                this.message = 'Upload failed';
            } finally {
                this.loading = false;
            }
        },

        async remove() {
            if (!confirm('Delete company logo?')) return;

            try {
                const res = await fetch(`/api/admin/${this.companyId}/logo`, {
                    method: 'DELETE',
                    credentials: 'include',
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });
                const data = await res.json();
                if (!res.ok) {
                    this.message = data.message || 'Delete failed';
                } else {
                    this.message = 'Deleted';
                    window.dispatchEvent(new CustomEvent('company-updated', { detail: { id: this.companyId } }));
                    this.preview = null;
                    this.$refs.file.value = null;
                }
            } catch (e) {
                console.error(e);
                this.message = 'Delete failed';
            }
        }
    }
}
