export default () => ({
    init() {
        const bg = Alpine.store('assets').mainBackground;

        const preload = new Image();
        preload.src = bg;

        if (this.$el.style.backgroundImage !== `url(${bg})`) {
            this.$el.style.transition = 'opacity 0.3s ease-in-out';
            this.$el.style.opacity = 0.15;

            setTimeout(() => {
                this.$el.style.backgroundImage = `url(${bg})`;
                this.$el.style.opacity = 0.20;
            }, 150);
        }
    },
});
