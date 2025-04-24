let jobDescription = document.getElementById('professional_experiences_edit_jobDescription');
let wordCounter = document.querySelector('.words-counter');
const maxLength = 1000;

function updateCounter(count) {
    wordCounter.textContent = `${count}/${maxLength}`;
}

// Mise à jour du compteur au chargement de la page
updateCounter(jobDescription.value.length);

jobDescription.addEventListener('input', (event) => {
    let textLength = event.target.value.length;

    // Empêcher d'écrire plus de 1000 caractères
    if (textLength > maxLength) {
        jobDescription.value = jobDescription.value.substring(0, maxLength);
        textLength = maxLength;
    }

    updateCounter(textLength);
});