import "./cookie.js";

const confirmationModal = document.createElement('div');
confirmationModal.className = 'confirm-modal';
confirmationModal.setAttribute('aria-hidden', 'true');
confirmationModal.innerHTML = `
    <div class="confirm-modal__backdrop" data-confirm-cancel></div>
    <div class="confirm-modal__dialog" role="alertdialog" aria-modal="true" aria-labelledby="confirm-modal-title" aria-describedby="confirm-modal-message">
        <div class="confirm-modal__icon" aria-hidden="true">
            <svg viewBox="0 0 24 24" fill="none">
                <path d="M3 6h18M8 6V4h8v2m3 0-.75 14H5.75L5 6m5 5v5m4-5v5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </div>
        <div class="confirm-modal__content">
            <p class="confirm-modal__eyebrow">Confirmation</p>
            <h2 id="confirm-modal-title">Confirmer la suppression</h2>
            <p id="confirm-modal-message"></p>
        </div>
        <div class="confirm-modal__actions">
            <button type="button" class="confirm-modal__cancel" data-confirm-cancel>Annuler</button>
            <button type="button" class="confirm-modal__delete" data-confirm-delete>Supprimer</button>
        </div>
    </div>
`;
document.body.appendChild(confirmationModal);

const messageElement = confirmationModal.querySelector('#confirm-modal-message');
const cancelButton = confirmationModal.querySelector('.confirm-modal__cancel');
const deleteButton = confirmationModal.querySelector('[data-confirm-delete]');
let pendingForm = null;
let previousFocus = null;

const closeConfirmation = () => {
    confirmationModal.classList.remove('is-open');
    confirmationModal.setAttribute('aria-hidden', 'true');
    document.body.classList.remove('confirm-modal-open');
    pendingForm = null;
    previousFocus?.focus();
};

window.addEventListener('submit', (event) => {
    const form = event.target;
    const message = form.dataset.confirm;

    if (!message || form.dataset.confirmed === 'true') {
        return;
    }

    event.preventDefault();
    pendingForm = form;
    previousFocus = document.activeElement;
    messageElement.textContent = message;
    confirmationModal.classList.add('is-open');
    confirmationModal.setAttribute('aria-hidden', 'false');
    document.body.classList.add('confirm-modal-open');
    cancelButton.focus();
});

confirmationModal.querySelectorAll('[data-confirm-cancel]').forEach((element) => {
    element.addEventListener('click', closeConfirmation);
});

deleteButton.addEventListener('click', () => {
    if (!pendingForm) {
        return;
    }

    pendingForm.dataset.confirmed = 'true';
    pendingForm.requestSubmit();
});

window.addEventListener('keydown', (event) => {
    if (event.key === 'Escape' && confirmationModal.classList.contains('is-open')) {
        closeConfirmation();
    }
});
