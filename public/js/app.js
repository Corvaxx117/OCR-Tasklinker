import DeleteLinkHandler from "./class/DeleteLinkHandler.js";

// Suppression projets (réutilisable pour tags/employés)
new DeleteLinkHandler({
    selector: "a.delete-project",
    getConfirmMessage: (link) =>
        link.dataset.confirm || "Supprimer ce projet ?",
});

// Suppression tâches
new DeleteLinkHandler({
    selector: "a.delete-task",
    getConfirmMessage: (link) =>
        link.dataset.confirm || "Supprimer cette tâche ?",
});

export { DeleteLinkHandler };
