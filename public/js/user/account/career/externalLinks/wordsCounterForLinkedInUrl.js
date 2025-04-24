let inputLinkedInUrl = document.getElementById('external_link_linked_in_edit_manager_linkedInUrl');
console.log(inputLinkedInUrl);
let wordCounter = document.querySelector('.words-counter');
const maxLength = 200;

function updateCounter(count) {
    wordCounter.textContent = `${count}/${maxLength}`;
}

// Mise à jour du compteur au chargement de la page
updateCounter(inputLinkedInUrl.value.length);

inputLinkedInUrl.addEventListener('input', (event) => {
    let textLength = event.target.value.length;

    // Empêcher d'écrire plus de 300 caractères
    if (textLength > maxLength) {
        inputLinkedInUrl.value = inputLinkedInUrl.value.substring(0, maxLength);
        textLength = maxLength;
    }

    updateCounter(textLength);
});