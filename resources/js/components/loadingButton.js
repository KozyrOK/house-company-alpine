export default () => ({
    loading: false,
    async submit(callback) {
        this.loading = true;
        await callback();
        this.loading = false;
    }
});
