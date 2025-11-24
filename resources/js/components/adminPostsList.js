export default function adminPostsList() {
    return {
        posts: [],
        loading: true,
        async fetchPosts(page = 1) {

            try {
                const res = await fetch(`/api/superadmin/posts?page=${page}`, {
                    method: 'GET',
                    credentials: 'include',
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                });

                const data = await res.json();

                if (res.ok) {
                    this.posts = data.data ?? data;
                }
            } catch (e) {
                console.error('❌ Error loading posts:', e);
            } finally {
                this.loading = false;
            }
        }
    };
}
