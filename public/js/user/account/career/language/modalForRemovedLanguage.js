document.querySelectorAll(".remove-btn").forEach(button => {
    button.addEventListener("click", function() {
        const languageId = this.getAttribute("data-id");
        const form = document.getElementById(`language-deletion-form-${languageId}`);

        Swal.fire({
            title: "Êtes-vous sûr ?",
            text: "Cette action est irréversible !",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            cancelButtonColor: "#3085d6",
            confirmButtonText: "Supprimer définitivement",
            cancelButtonText: "Annuler"
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });
});