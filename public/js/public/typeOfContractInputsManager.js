const form = document.getElementById('form_filter');
const allRadioBtn = document.querySelectorAll('input[type="radio"]');

// when a choice is done, form is submitted automatically for
// each button
allRadioBtn.forEach(input => {
    input.addEventListener('input', () => {
        form.submit();
    });
});