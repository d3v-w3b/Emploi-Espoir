$(document).ready(function() {
    $('#current_professional_situation_currentProfessionalSituation').select2({
        minimumResultsForSearch: Infinity,                           // cache la barre de recherche
        placeholder: "Sélectionnez une situation professionnelle",  // le placeholder
        allowClear: true
    });
});