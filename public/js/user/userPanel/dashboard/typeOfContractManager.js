const allInputRadio = document.querySelectorAll('input[type="radio"]');
const form = document.querySelector('form');

// submit automatically form when an input is checked
allInputRadio.forEach(input => {
    input.addEventListener('input', () => {
        if(input.checked) {
            form.submit();
        }
    });
});