document.querySelector("a.js-delete-task").addEventListener("click", (e) => {
    if (!confirm(e.target.dataset.confirm)) {
        e.preventDefault();
    }
});
