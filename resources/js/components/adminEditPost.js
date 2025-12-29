export default function adminEditPost(postId) {
    return {
        postId,
        post: {},
        loading: true,

        form: {
            title: '',
            content: '',
            status: '',
        },

        file: null,
        previewUrl: null,
        removeExisting: false,

        async init() {
            await this.fetchPost();
        },

        async fetchPost() {
            try {
                const res = await fetch(`/api/posts/${this.postId}`, {
                    credentials: 'include',
                });

                const data = await res.json();
                this.post = data.data ?? data;

                this.form = {
                    title: this.post.title,
                    content: this.post.content,
                    status: this.post.status,
                };

            } catch (e) {
                console.error(e);
            } finally {
                this.loading = false;
            }
        },

        handleFileUpload(e) {
            this.file = e.target.files[0];
            this.removeExisting = false;

            if (this.file) {
                this.previewUrl = URL.createObjectURL(this.file);
            }
        },

        clearPreview() {
            this.file = null;
            this.previewUrl = null;
        },

        removeImage() {
            this.removeExisting = true;
            this.previewUrl = '/images/default-image-company.jpg';
        },

        async save() {
            try {
                const res = await fetch(`/api/posts/${this.postId}`, {
                    method: 'PATCH',
                    credentials: 'include',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(this.form),
                });

                if (!res.ok) throw new Error('Update failed');

                if (this.file) {
                    const fd = new FormData();
                    fd.append('image', this.file);

                    const imgRes = await fetch(`/api/posts/${this.postId}/image`, {
                        method: 'POST',
                        credentials: 'include',
                        body: fd,
                    });

                    if (!imgRes.ok) throw new Error('Image upload failed');
                }

                if (this.removeExisting && !this.file) {
                    await fetch(`/api/posts/${this.postId}/image`, {
                        method: 'DELETE',
                        credentials: 'include',
                    });
                }

                window.location.href = `/admin/posts/${this.postId}`;

            } catch (e) {
                console.error(e);
                alert('Error saving post');
            }
        },
    };
}
