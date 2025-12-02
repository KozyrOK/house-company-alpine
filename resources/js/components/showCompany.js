export default function showCompany(companyId) {
    return {
        companyId,
        company: {},
        loading: true,

        async fetchCompany() {

            const companyId = window.location.pathname.split('/').pop();

            try {
                const res = await fetch(`/api/admin/${this.companyId}`, {
                    method: 'GET',
                    credentials: 'include',
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                });

                const data = await res.json();

                if (res.ok) {
                    this.company = data.data ?? data;
                }

            } catch (e) {
                console.error("❌ Error loading company:", e);
            } finally {
                this.loading = false;
            }
        }
    };
}
