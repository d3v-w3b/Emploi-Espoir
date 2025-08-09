const requestErrorSpan = document.querySelector('.request_error');

if (requestErrorSpan) {

    Swal.fire({
        title: "Demande déjà envoyé",
        text: "Veuillez patienter. Votre demande est déjà en cours de traitement",
        icon: "warning"
    });
}