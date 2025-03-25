flatpickr(".js-monthpicker", {
    plugins: [
        new monthSelectPlugin({
            shorthand: true, // Affiche les mois en abrégé (ex: Jan, Fév)
            dateFormat: "m/Y", // Format d'affichage MM/YYYY
            altFormat: "F Y", // Affichage alternatif en clair (ex: Janvier 2024)
            theme: "light" // Style du sélecteur

        })
    ],
    allowInput: true // Permet à l'utilisateur d'entrer la date manuellement si nécessaire
});