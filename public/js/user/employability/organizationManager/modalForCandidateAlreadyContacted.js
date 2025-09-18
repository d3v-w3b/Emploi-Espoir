/*
    This file manages the modal for the candidate already contacted
    It is used by two files:
    - applicantDetails.html.twig
    - specificProfilDetails.html.twig
 */

const candidateAlreadyContacted = document.getElementById('candidate-already-contacted-msg');
const backdrop = document.getElementById('modal-backdrop');
const closeModalButton = document.getElementById('close-modal');
const modal = document.getElementById('modal');
const contactCandidateLink = document.querySelector('.contact-candidat');
const applicantModalTitle = document.getElementById('modal-title');
const applicantEmail = document.getElementById('candidat-email')
const applicantPhone = document.getElementById('candidat-phone');
const applicantOrgResponse = document.getElementById('org-response');

if (candidateAlreadyContacted)  {

    // Get data attribute from the view
    const title = contactCandidateLink.getAttribute('data-modal-title');
    const email = contactCandidateLink.getAttribute('data-applicant-email');
    const phone = contactCandidateLink.getAttribute('data-applicant-phone');
    const orgResponse = contactCandidateLink.getAttribute('data-org-response');


    // Put data into the modal
    applicantModalTitle.textContent = title;
    applicantEmail.textContent = email;
    applicantPhone.textContent = phone;
    applicantOrgResponse.textContent = orgResponse;

    modal.style.display = 'block';
    backdrop.style.display = 'block';
}




// Close modal
closeModalButton.addEventListener('click', () => {
    modal.style.display = 'none';
    backdrop.style.display = 'none';
});

// Close modal when clicking on the backdrop
backdrop.addEventListener('click', () => {
    modal.style.display = 'none';
    backdrop.style.display = 'none';
});