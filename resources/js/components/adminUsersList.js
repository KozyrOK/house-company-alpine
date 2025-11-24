export default function adminUsersList() {
    return {
        users: [],
        loading: true,
        async fetchUsers(page = 1) {
            this.loading = true;

            try {
                const res = await fetch(`/api/superadmin/users?page=${page}`, {
                    method: 'GET',
                    credentials: 'include',
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                });

                const data = await res.json();

                if (res.ok) {
                    this.users = data.data ?? data;
                } else {
                    console.warn('⚠️ Unauthorized or bad response', data);
                }
            } catch (e) {
                console.error('❌ Error loading users:', e);
            } finally {
                this.loading = false;
            }
        }
    };
}
