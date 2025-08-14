const applicationDetails = document.querySelectorAll('.application-details');
const modal = document.getElementById('application-modal');
const backdrop = document.getElementById('modal-backdrop');

// Datas to display
const applicationOfferTitle = document.getElementById('application-offer-title');
const applicantFirstName = document.getElementById('applicant-first-name');
const applicantLastName = document.getElementById('applicant-last-name');
const applicantEmail = document.getElementById('applicant-email');
const applicantPhone = document.getElementById('applicant-phone');
const applicantDocs = document.getElementById('applicant-docs');
const applicantId = document.getElementById('applicant-profil');

const closeModalButton = document.getElementById('close-modal');

applicationDetails.forEach(link => {
    link.addEventListener('click', (event) => {
        event.preventDefault();

        // Retrieve datas from the view
        const offerTitle = link.getAttribute('data-offer-title');
        const lastName = link.getAttribute('data-lastName');
        const firstName = link.getAttribute('data-firstName');
        const email = link.getAttribute('data-email');
        const phone = link.getAttribute('data-phone');
        const docs = link.getAttribute('data-docs').split(',');
        const id = link.getAttribute('data-id');

        // Put datas content into the modal
        applicationOfferTitle.textContent = offerTitle;
        applicantFirstName.textContent = firstName;
        applicantLastName.textContent = lastName;
        applicantEmail.textContent = email;
        applicantPhone.textContent = phone;

        // Managing of documents
        applicantDocs.innerHTML = '';
        if (docs.length > 0 && docs[0] !== '') {
            docs.forEach(doc => {
                const a = document.createElement('a');
                a.href = `/user/employability/docs/${doc}`;
                a.textContent = doc;
                a.target = '_blank';
                a.style.display = 'block';
                a.style.marginBottom = '5px';
                applicantDocs.appendChild(a);
            });
        }
        else {
            applicantDocs.textContent = 'Aucun document fourni.';
        }

        applicantId.textContent = 'Voir le profil du candidat';
        applicantId.href = '/organization/offer/applicant-details_' + id;

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