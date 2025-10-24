import DeleteLinkHandler from "./class/DeleteLinkHandler.js";

// Suppression projets
new DeleteLinkHandler({
    selector: "a.delete-project",
    method: "DELETE",
    getConfirmMessage: (link) =>
        link.dataset.confirm || "Supprimer ce projet ?",
});
