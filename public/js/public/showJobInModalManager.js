const jobLinks = document.querySelectorAll('.job-link');
const modal = document.getElementById('job-modal');
const backdrop = document.getElementById('modal-backdrop');

const modalJobTitle = document.getElementById('modal-job-title');
const modalOrganizationName = document.getElementById('modal-organization-name');
const modalExpirationDate = document.getElementById('modal-expiration-date');
const modalId = document.getElementById('apply-link');
const closeModalButton = document.getElementById('close-modal');

// Open modal
jobLinks.forEach(link => {
    link.addEventListener('click', (event) => {
        event.preventDefault();

        // Retrieve data from the clicked link
        const title = link.getAttribute('data-title');
        const organization = link.getAttribute('data-organization');
        const expiration = link.getAttribute('data-expiration');
        const id = link.getAttribute('data-id');

        // Update modal content
        modalJobTitle.textContent = title;
        modalOrganizationName.textContent = organization;
        modalExpirationDate.textContent = expiration;
        modalId.textContent = 'Postuler';
        modalId.href = '/organization/job-offer/apply/' + id;

        // Show modal and backdrop
        modal.style.display = 'block';
        backdrop.style.display = 'block';
    });
});

// Close modal
closeModalButton.addEventListener('click', () => {
    modal.style.display = 'none';
    backdrop.style.display = 'none';
});

// Close modal when clicking on backdrop
backdrop.addEventListener('click', () => {
    modal.style.display = 'none';
    backdrop.style.display = 'none';
});
