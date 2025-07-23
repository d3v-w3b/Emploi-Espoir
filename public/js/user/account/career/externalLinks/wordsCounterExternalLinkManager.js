const inputs = document.querySelectorAll('.input-counter');
const maxLength = 200;

inputs.forEach((input) => {
    // Cherche le compteur associé à cet input
    const wordCounter = input.parentElement.querySelector('.words-counter');

    function updateCounter() {
        const textLength = input.value.length;
        wordCounter.textContent = `${textLength}/${maxLength}`;
    }

    // Mise à jour initiale du compteur
    updateCounter();

    input.addEventListener('input', () => {
        if (input.value.length > maxLength) {
            input.value = input.value.substring(0, maxLength);
        }
        updateCounter();
    });
});