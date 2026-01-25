export default function adminPostsList() {
    return {
        posts: [],
        loading: false,
        page: 1,

        async fetchPosts(page = 1) {
            this.loading = true;
            this.page = page;

            try {
                const res = await fetch(`/api/admin/posts?page=${page}`, {
                    method: 'GET',
                    credentials: 'include',
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                });

                if (!res.ok) {
                    throw new Error(`HTTP ${res.status}`);
                }

                const data = await res.json();

                this.posts = data.data ?? data;

            } catch (e) {
                console.error('❌ Error loading posts:', e);
            } finally {
                this.loading = false;
            }
        }
    };
}
