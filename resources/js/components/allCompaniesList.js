export default function allCompaniesList() {
    return {
        companies: [],
        loading: true,
        async fetchCompanies() {

            try {

                const res = await fetch('/api/companies', {
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
                } else {
                    console.warn('⚠️ Unauthorized or bad response', data);
                }

            } catch (e) {
                console.error('❌ Error loading companies:', e);
            } finally {
                this.loading = false;
            }
        }
    };
}

