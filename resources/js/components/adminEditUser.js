export default function adminEditUser(userId) {
    return {
        userId,
        user: {},
        loading: true,

        form: {
            first_name: '',
            second_name: '',
            email: '',
            phone: '',
            status_account: '',
        },

        file: null,
        previewUrl: null,

        async init() {
            await this.fetchUser();
        },

        async fetchUser() {
            try {
                const res = await fetch(`/api/admin/users/${this.userId}`, {
                    credentials: 'include',
                });

                const data = await res.json();
                this.user = data.data ?? data;

                this.form = {
                    first_name: this.user.first_name,
                    second_name: this.user.second_name,
                    email: this.user.email,
                    phone: this.user.phone,
                    status_account: this.user.status_account,
                };

            } catch (e) {
                console.error(e);
            } finally {
                this.loading = false;
            }
        },

        async save() {
            try {
                const res = await fetch(`/api/admin/users/${this.userId}`, {
                    method: 'PATCH',
                    credentials: 'include',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(this.form),
                });

                if (!res.ok) throw new Error('Update failed');

                window.location.href = `/admin/users/${this.userId}`;

            } catch (e) {
                console.error(e);
                alert('Error saving user');
            }
        },
    };
}
