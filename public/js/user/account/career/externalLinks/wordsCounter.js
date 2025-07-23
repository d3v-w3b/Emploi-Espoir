/**
 * This file is use for all inputs with counter into the edit of
 * external links
 */


let input = document.querySelector('.input-counter');
let wordCounter = document.querySelector('.words-counter');
const maxLength = 200;

function updateCounter(count) {
    wordCounter.textContent = `${count}/${maxLength}`;
}

// Mise à jour du compteur au chargement de la page
updateCounter(input.value.length);

input.addEventListener('input', (event) => {
    let textLength = event.target.value.length;

    // Empêcher d'écrire plus de 200 caractères
    if (textLength > maxLength) {
        input.value = input.value.substring(0, maxLength);
        textLength = maxLength;
    }

    updateCounter(textLength);
});