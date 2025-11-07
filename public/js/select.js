// Initialisation simple de Select2 comme dans la maquette prototype
// Progressive enhancement: si Select2 absent, le select multiple reste fonctionnel.
(function () {
    function enhanceSelectMultiple() {
        if (!window.jQuery || !window.jQuery.fn || !window.jQuery.fn.select2) {
            return; // Select2 pas disponible, on laisse le select natif.
        }
        jQuery("select[multiple]").each(function () {
            const $el = jQuery(this);
            $el.select2({
                width: "100%",
                placeholder: $el.attr("data-placeholder") || "Sélectionner",
                minimumResultsForSearch: 5,
            });
        });
    }

    function initMultiButtons(selector, btnClass, inputName) {
        const container = document.querySelector(selector);
        if (!container) return;
        container.addEventListener("click", function (e) {
            const btn = e.target.closest("." + btnClass);
            if (!btn) return;
            const id = btn.getAttribute("data-id");
            if (!id) return;
            const existing = container.querySelector(
                'input[type="hidden"][name="' +
                    inputName +
                    '"][value="' +
                    id +
                    '"]'
            );
            if (existing) {
                existing.remove();
                btn.classList.remove("selected");
            } else {
                const input = document.createElement("input");
                input.type = "hidden";
                input.name = inputName;
                input.value = id;
                container.appendChild(input);
                btn.classList.add("selected");
            }
        });
    }

    function init() {
        enhanceSelectMultiple();
        // Multi sélection par boutons (projet futur / tâches)
        initMultiButtons(".members-selector", "member-btn", "members[]");
        initMultiButtons(".assignees-selector", "assignee-btn", "assignees[]");
    }

    if (document.readyState === "loading") {
        document.addEventListener("DOMContentLoaded", init);
    } else {
        init();
    }
})();
