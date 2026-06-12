<div id="confirm-modal" class="confirm-modal-overlay hidden">
    <div class="confirm-modal-card">
        <p id="confirm-modal-message" class="confirm-modal-message">{{__('app.tables.are_you_sure')}}</p>
        <div class="confirm-modal-actions">
            <button type="button" id="confirm-modal-cancel" class="button-list">{{__('app.buttons.cancel')}}</button>
            <button type="button" id="confirm-modal-ok" class="button-delete">{{__('app.buttons.ok')}}</button>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const modal = document.getElementById('confirm-modal');
        const message = document.getElementById('confirm-modal-message');
        const cancel = document.getElementById('confirm-modal-cancel');
        const ok = document.getElementById('confirm-modal-ok');
        let targetForm = null;

        document.querySelectorAll('form.confirmable-form').forEach((form) => {
            form.addEventListener('submit', (event) => {
                event.preventDefault();
                targetForm = form;
                message.textContent = form.dataset.confirmMessage || 'Are you sure?';
                modal.classList.remove('hidden');
            });
        });

        cancel.addEventListener('click', () => {
            modal.classList.add('hidden');
            targetForm = null;
        });

        ok.addEventListener('click', () => {
            if (targetForm) {
                targetForm.submit();
            }
            modal.classList.add('hidden');
            targetForm = null;
        });
    });
</script>
