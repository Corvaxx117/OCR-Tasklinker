// Gestion fermeture des messages flash
(function () {
    function onClick(e) {
        const btn = e.target.closest(".flash-close");
        if (!btn) return;
        const flash = btn.closest(".flash");
        if (!flash) return;
        flash.classList.add("closing");
        setTimeout(() => {
            flash.remove();
        }, 250);
    }
    document.addEventListener("click", onClick);
})();
