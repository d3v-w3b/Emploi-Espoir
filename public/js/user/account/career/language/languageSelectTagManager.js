// select tag manager
$(document).ready(function() {
    const selectTag = $('#language_level_language');


    selectTag.select2({
        placeholder: "Saisissez la langue",
        allowClear: true,
        minimumResultsForSearch: Infinity,      // Allows to remove search bar from select tag
        dropdownAutoWidth: true
    });



    selectTag.on('select2:select', function(event) {
        const languageLevelBlock = document.querySelector('.language-level-block');
        languageLevelBlock.style.display = 'block';

        if(languageLevelBlock) {
            const allInputRadio = document.querySelectorAll('input[type="radio"]');
            allInputRadio.forEach(input => {
                input.required = true;
            });
        }
    });



    selectTag.on('select2:unselect', function(event) {
        const languageLevelBlock = document.querySelector('.language-level-block');
        languageLevelBlock.style.display = 'none'; // Masquer si l'option est supprimée

        if(languageLevelBlock) {
            const allInputRadio = document.querySelectorAll('input[type="radio"]');
            allInputRadio.forEach(input => {
                input.required = false;
            });
        }
    });
});