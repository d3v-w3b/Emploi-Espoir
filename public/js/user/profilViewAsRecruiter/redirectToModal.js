/**
 *
 * const link = document.getElementById('showEmploymentAreaModal');
 * const modal = document.getElementById('employmentAreaModal');
 * const closeModal = document.getElementById('closeModal');
 * const modalContent = document.getElementById('modalContent');
 *
 *
 * // Intercept the click event on the link
 * link.addEventListener('click', function (event) {
 *     event.preventDefault();  // Empêche la redirection
 *
 *     // Récupérer l'URL du lien
 *     const url = link.getAttribute('href');
 *
 *     // Utilisation de Fetch API pour récupérer le contenu du formulaire
 *     fetch(url)
 *         .then(response => response.text())
 *         .then(data => {
 *             // Injecter le contenu du formulaire dans le modal
 *             modalContent.innerHTML = data;
 *
 *             // Afficher le modal
 *             modal.style.display = 'block';
 *
 *             // Sélectionner le formulaire
 *             const form = modalContent.querySelector('form');
 *
 *             // Vérifier si le formulaire est trouvé
 *             console.log('Form found:', form);
 *
 *             form.addEventListener('submit', function (formEvent) {
 *                 formEvent.preventDefault();  // Empêche le comportement par défaut de soumission
 *
 *                 // Création d'un FormData à partir du formulaire
 *                 const formData = new FormData(form);
 *
 *                 // Envoi des données via AJAX
 *                 fetch(url, {
 *                     method: 'POST',
 *                     body: formData
 *                 })
 *                     .then(response => {
 *                         if (response.ok) {
 *                             return response.json();
 *                         } else {
 *                             throw new Error('Erreur serveur');
 *                         }
 *                     })
 *                     .then(responseData => {
 *                         console.log('Form submitted successfully:', responseData); // Afficher la réponse serveur
 *                         // Afficher un message de succès
 *                         modalContent.innerHTML = `<p>${responseData.message}</p>`;
 *
 *                         // Optionnel : Fermer le modal après un certain temps
 *                         setTimeout(() => {
 *                             modal.style.display = 'none';
 *                         }, 2000);
 *                     })
 *                     .catch(error => {
 *                         console.error('Erreur lors de la soumission du formulaire :', error);
 *                         modalContent.innerHTML = `<p>Une erreur est survenue lors de la soumission du formulaire.</p>`;
 *                     });
 *             });
 *         })
 *         .catch(error => {
 *             console.error('Erreur lors du chargement du contenu :', error);
 *         });
 * });
 *
 *
 * // Close the modal when the user clicks the "x"
 * closeModal.addEventListener('click', function () {
 *     modal.style.display = 'none';
 * });
 *
 *
 * // Close the modal when the user clicks anywhere outside the modal
 * window.addEventListener('click', function (event) {
 *     if (event.target === modal) {
 *         modal.style.display = 'none';
 *     }
 * });
 *
 * @type {HTMLElement}
 */
