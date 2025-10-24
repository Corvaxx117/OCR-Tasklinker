import DeleteLinkHandler from "./class/DeleteLinkHandler.js";

// Suppression employés
new DeleteLinkHandler({
    selector: "a.delete-employee",
    getConfirmMessage: (link) =>
        link.dataset.confirm || "Supprimer cet employé ?",
    findRemovable: (link) => link.closest("tr"),
});
