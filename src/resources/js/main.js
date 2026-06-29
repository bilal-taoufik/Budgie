import "./cookie.js";
window.addEventListener('submit', (event) => {
    const form = event.target;
    const message = form.dataset.confirm;

    if (message && !confirm(message)) {
        event.preventDefault();
    }
});
