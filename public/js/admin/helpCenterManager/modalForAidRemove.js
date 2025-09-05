document.querySelectorAll(".btn-remove").forEach(button => {
    button.addEventListener("click", function() {
        const aidId = this.getAttribute("data-id");
        const form = document.getElementById(`form-${aidId}`);

        Swal.fire({
            title: "Vérifier que vous avez traité cette requête avant de la rétirer !",
            text: "Cette action est irréversible !",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            cancelButtonColor: "#3085d6",
            confirmButtonText: "Retirer la requête",
            cancelButtonText: "Annuler"
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });
});