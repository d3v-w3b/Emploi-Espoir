const errorApplyingMsg = document.querySelector('.already-apply');

if(errorApplyingMsg) {
    Swal.fire({
        title: "Oops !",
        text: "Vous avez déjà postuler pour cette offre",
        icon: "info"
    });
}