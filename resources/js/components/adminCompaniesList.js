export default function adminCompaniesList() {
    return {
        companies: [],
        loading: true,
        async fetchCompanies(page = 1) {

            try {
                const res = await fetch(`/api/admin`, {
                    method: 'GET',
                    credentials: 'include',
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                });

                const data = await res.json();

                if (res.ok) {
                    this.companies = data.data ?? data;
                }
            } catch (e) {
                console.error('❌ Error loading companies:', e);
            } finally {
                this.loading = false;
            }
        }
    };
}
