let inputPresentation = document.getElementById('about_me_aboutMe');
let wordCounter = document.querySelector('.words-counter');
const maxLength = 300;

function updateCounter(count) {
    wordCounter.textContent = `${count}/${maxLength}`;
}

// Mise à jour du compteur au chargement de la page
updateCounter(inputPresentation.value.length);

inputPresentation.addEventListener('input', (event) => {
    let textLength = event.target.value.length;

    // Empêcher d'écrire plus de 300 caractères
    if (textLength > maxLength) {
        inputPresentation.value = inputPresentation.value.substring(0, maxLength);
        textLength = maxLength;
    }

    updateCounter(textLength);
});