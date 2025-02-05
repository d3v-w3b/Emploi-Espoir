const removeModal = document.getElementById('remove-modal');
const backdrop = document.getElementById('modal-backdrop');
const closeModalButton = document.getElementById('close-modal');
const removeLink = document.querySelectorAll('.remove-link');
//const form = document.querySelector('form');

removeLink.forEach(link => {
    link.addEventListener('click', (event) => {
        event.preventDefault();

        // Récupérer l'ID de l'offre
        const offerId = link.getAttribute("data-offer-id");
        const formContainer = document.getElementById('form-container-' + offerId);
        const formContent = formContainer.innerHTML;

        // Récupérer le formulaire correspondant
        //const form = document.getElementById(`remove-form-${offerId}`);

        // Masquer tous les autres formulaires avant d'afficher celui-ci
        document.querySelectorAll(".remove-form").forEach(f => {
            if (f !== form) {
                f.style.display = "none";
            }
        });

        // Afficher le bon formulaire
        form.style.display = "block";

    });
});


// Close modal
closeModalButton.addEventListener('click', () => {
    removeModal.style.display = 'none';
    backdrop.style.display = 'none';
});

// Close modal when clicking on backdrop
backdrop.addEventListener('click', () => {
    removeModal.style.display = 'none';
    backdrop.style.display = 'none';
});

const cancelButtons = document.querySelectorAll(".cancel-btn");
cancelButtons.forEach(button => {
    button.addEventListener("click", function () {
        this.closest(".remove-form").style.display = "none";
    });
});
