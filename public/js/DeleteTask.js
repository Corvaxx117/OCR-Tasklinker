import DeleteLinkHandler from "./class/DeleteLinkHandler.js";

// Suppression tâches
new DeleteLinkHandler({
    selector: "a.js-delete-task",
    getConfirmMessage: (link) =>
        link.dataset.confirm || "Supprimer cette tâche ?",
});
