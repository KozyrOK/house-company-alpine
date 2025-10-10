export default () => ({
    open: false,
    message: 'Are you sure?',
    form: null,

    confirm(formRef) {
        this.form = formRef;
        this.open = true;
    },

    proceed() {
        if (this.form) this.form.submit();
        this.open = false;
    }
});
