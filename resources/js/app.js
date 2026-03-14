import "./bootstrap";

const toDataTransfer = (files) => {
    const dataTransfer = new DataTransfer();

    files.forEach((file) => dataTransfer.items.add(file));

    return dataTransfer;
};

const createPreviewCard = (file, index, onRemove) => {
    const wrapper = document.createElement("div");
    wrapper.className =
        "relative overflow-hidden rounded-lg border border-slate-200 bg-white";

    const img = document.createElement("img");
    img.className = "h-24 w-full object-cover";
    img.alt = `Selected photo ${index + 1}`;

    const url = URL.createObjectURL(file);
    img.src = url;

    const button = document.createElement("button");
    button.type = "button";
    button.className =
        "absolute right-2 top-2 rounded bg-white/90 px-2 py-1 text-[10px] font-semibold text-red-600";
    button.textContent = "Remove";

    button.addEventListener("click", () => {
        URL.revokeObjectURL(url);
        onRemove();
    });

    wrapper.appendChild(img);
    wrapper.appendChild(button);

    return wrapper;
};

const initPhotoUploader = (container) => {
    const fileInput = container.querySelector("[data-photo-input]");
    const dropzone = container.querySelector("[data-dropzone]");
    const previewGrid = container.querySelector("[data-preview-grid]");
    const countElement = container.querySelector("[data-photo-count]");
    const totalCountElement = container.querySelector("[data-total-count]");
    const existingKeptElement = container.querySelector("[data-existing-kept]");
    const emptyPreview = container.querySelector("[data-empty-preview]");

    if (!fileInput || !dropzone || !previewGrid || !countElement) {
        return;
    }

    const maxPhotos = Number.parseInt(container.dataset.maxPhotos ?? "3", 10);
    const initialExistingCount = Number.parseInt(
        container.dataset.existingCount ?? "0",
        10,
    );
    const removeExistingCheckboxes = container.querySelectorAll(
        "[data-remove-existing-photo]",
    );
    let selectedFiles = [];

    const getExistingRemovedCount = () => {
        return Array.from(removeExistingCheckboxes).filter(
            (checkbox) => checkbox.checked,
        ).length;
    };

    const syncCounters = () => {
        const existingKept = Math.max(
            initialExistingCount - getExistingRemovedCount(),
            0,
        );
        countElement.textContent = String(selectedFiles.length);

        if (existingKeptElement) {
            existingKeptElement.textContent = String(existingKept);
        }

        if (totalCountElement) {
            totalCountElement.textContent = String(
                existingKept + selectedFiles.length,
            );
        }

        if (emptyPreview) {
            emptyPreview.classList.toggle("hidden", selectedFiles.length > 0);
        }
    };

    const renderPreviews = () => {
        const dynamicPreviews =
            previewGrid.querySelectorAll("[data-new-preview]");
        dynamicPreviews.forEach((node) => node.remove());

        selectedFiles.forEach((file, index) => {
            const card = createPreviewCard(file, index, () => {
                selectedFiles = selectedFiles.filter(
                    (_, fileIndex) => fileIndex !== index,
                );
                fileInput.files = toDataTransfer(selectedFiles).files;
                renderPreviews();
            });

            card.dataset.newPreview = "true";
            previewGrid.appendChild(card);
        });

        syncCounters();
    };

    const addFiles = (files) => {
        const incoming = Array.from(files);
        const existingKept = Math.max(
            initialExistingCount - getExistingRemovedCount(),
            0,
        );
        const availableSlots = Math.max(
            maxPhotos - existingKept - selectedFiles.length,
            0,
        );

        const accepted = incoming.slice(0, availableSlots);

        if (accepted.length === 0) {
            return;
        }

        selectedFiles = [...selectedFiles, ...accepted];
        fileInput.files = toDataTransfer(selectedFiles).files;
        renderPreviews();
    };

    dropzone.addEventListener("dragover", (event) => {
        event.preventDefault();
        dropzone.classList.add("border-primary", "bg-primary/10");
    });

    dropzone.addEventListener("dragleave", () => {
        dropzone.classList.remove("border-primary", "bg-primary/10");
    });

    dropzone.addEventListener("drop", (event) => {
        event.preventDefault();
        dropzone.classList.remove("border-primary", "bg-primary/10");

        if (event.dataTransfer?.files) {
            addFiles(event.dataTransfer.files);
        }
    });

    dropzone.addEventListener("click", (event) => {
        if (event.target !== fileInput) {
            fileInput.click();
        }
    });

    fileInput.addEventListener("change", () => {
        if (!fileInput.files?.length) {
            return;
        }

        addFiles(fileInput.files);
    });

    removeExistingCheckboxes.forEach((checkbox) => {
        checkbox.addEventListener("change", () => {
            const existingKept = Math.max(
                initialExistingCount - getExistingRemovedCount(),
                0,
            );
            const allowedNewCount = Math.max(maxPhotos - existingKept, 0);

            if (selectedFiles.length > allowedNewCount) {
                selectedFiles = selectedFiles.slice(0, allowedNewCount);
                fileInput.files = toDataTransfer(selectedFiles).files;
                renderPreviews();
            } else {
                syncCounters();
            }
        });
    });

    syncCounters();
};

const initItemGallery = (container) => {
    const mainImage = container.querySelector("[data-main-image]");
    const thumbnails = container.querySelectorAll("[data-gallery-thumb]");

    if (!mainImage || thumbnails.length === 0) {
        return;
    }

    thumbnails.forEach((thumb) => {
        thumb.addEventListener("click", () => {
            const fullSrc = thumb.dataset.fullSrc;
            const fullAlt = thumb.dataset.fullAlt;

            if (!fullSrc) {
                return;
            }

            mainImage.src = fullSrc;
            mainImage.alt = fullAlt ?? mainImage.alt;

            thumbnails.forEach((node) => {
                node.classList.remove(
                    "border-primary",
                    "ring-2",
                    "ring-primary/30",
                );
                node.classList.add("border-slate-200");
            });

            thumb.classList.remove("border-slate-200");
            thumb.classList.add("border-primary", "ring-2", "ring-primary/30");
        });
    });
};

const dismissAlert = (alertElement) => {
    if (!alertElement || alertElement.dataset.dismissed === "true") {
        return;
    }

    alertElement.dataset.dismissed = "true";
    alertElement.style.transition = "opacity 220ms ease, transform 220ms ease";
    alertElement.style.opacity = "0";
    alertElement.style.transform = "translateY(-6px)";

    window.setTimeout(() => {
        alertElement.remove();
    }, 240);
};

const initTimedAlerts = () => {
    const alerts = document.querySelectorAll("[data-alert]");

    alerts.forEach((alertElement) => {
        const dismissButton = alertElement.querySelector("[data-alert-close]");

        if (dismissButton) {
            dismissButton.addEventListener("click", () => {
                dismissAlert(alertElement);
            });
        }

        const autoDismissMs = Number.parseInt(
            alertElement.dataset.autoDismiss ?? "0",
            10,
        );

        if (autoDismissMs > 0) {
            window.setTimeout(() => {
                dismissAlert(alertElement);
            }, autoDismissMs);
        }
    });
};

const initDeleteConfirmModal = () => {
    const modal = document.getElementById("delete-confirm-modal");

    if (!modal) {
        return;
    }

    const messageElement = modal.querySelector("#delete-confirm-message");
    const confirmButton = modal.querySelector("[data-modal-confirm]");
    const cancelButton = modal.querySelector("[data-modal-cancel]");
    const closeButton = modal.querySelector("[data-modal-close]");
    const backdrop = modal.querySelector("[data-modal-backdrop]");
    const deleteForms = document.querySelectorAll("form[data-delete-form]");

    let pendingForm = null;

    const openModal = (form) => {
        pendingForm = form;

        const itemLabel = form.dataset.itemLabel?.trim();
        messageElement.textContent = itemLabel
            ? `Are you sure you want to permanently delete "${itemLabel}"? This action cannot be undone.`
            : "Are you sure you want to delete this item permanently? This action cannot be undone.";

        modal.classList.remove("hidden");
        modal.setAttribute("aria-hidden", "false");
        document.body.classList.add("overflow-hidden");
    };

    const closeModal = () => {
        pendingForm = null;
        modal.classList.add("hidden");
        modal.setAttribute("aria-hidden", "true");
        document.body.classList.remove("overflow-hidden");
    };

    deleteForms.forEach((form) => {
        form.addEventListener("submit", (event) => {
            event.preventDefault();
            openModal(form);
        });
    });

    confirmButton?.addEventListener("click", () => {
        if (pendingForm) {
            const formToSubmit = pendingForm;
            closeModal();
            formToSubmit.submit();
        }
    });

    [cancelButton, closeButton, backdrop].forEach((node) => {
        node?.addEventListener("click", closeModal);
    });

    document.addEventListener("keydown", (event) => {
        if (event.key === "Escape" && !modal.classList.contains("hidden")) {
            closeModal();
        }
    });
};

document.addEventListener("DOMContentLoaded", () => {
    const uploaders = document.querySelectorAll("[data-photo-uploader]");
    uploaders.forEach((uploader) => initPhotoUploader(uploader));

    const galleries = document.querySelectorAll("[data-item-gallery]");
    galleries.forEach((gallery) => initItemGallery(gallery));

    initTimedAlerts();
    initDeleteConfirmModal();
});
