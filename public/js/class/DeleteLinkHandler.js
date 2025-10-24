// Classe générique de gestion des liens de suppression (projets, tags, employés...).
// selector: sélecteur CSS des liens à intercepter
// method: méthode HTTP (POST par défaut)
// getConfirmMessage(link): fonction retournant le message de confirmation (string ou null pour désactiver)
// findRemovable(link): fonction qui retourne le noeud à retirer du DOM (par défaut .card-project/.deletable-item)
// onSuccess(link, response): callback succès (DOM déjà retiré par défaut si cible trouvée)
// onError(link, error): callback erreur

export default class DeleteLinkHandler {
    constructor({
        selector = "a.delete-item",
        method = "POST",
        getConfirmMessage = (link) =>
            link.dataset.confirm || "Confirmer la suppression ?",
        findRemovable = (link) =>
            link.closest(".card-project, .deletable-item"),
        onSuccess = (link, response) => {},
        onError = (link, error) =>
            alert("Erreur suppression: " + error.message),
    } = {}) {
        this.selector = selector;
        this.method = method;
        this.getConfirmMessage = getConfirmMessage;
        this.findRemovable = findRemovable;
        this.onSuccess = onSuccess;
        this.onError = onError;
        this._bind();
    }

    _bind() {
        document.querySelectorAll(this.selector).forEach((element) => {
            element.addEventListener("click", (e) => {
                e.preventDefault();
                const link = e.target;
                const url = link.dataset.action;

                // Verification si l'attr data-action existe
                if (!url) {
                    return;
                }

                const msg = this.getConfirmMessage(link);

                if (msg && !window.confirm(msg)) {
                    return;
                }

                fetch(url, { method: this.method })
                    .then((r) => {
                        if (!r.ok) {
                            throw new Error("Serveur (" + r.status + ")");
                        }

                        const removable = this.findRemovable(link);

                        if (removable) {
                            removable.remove();
                        }

                        this.onSuccess(link, r);
                    })
                    .catch((err) => this.onError(link, err));
            });
        });
    }
}
