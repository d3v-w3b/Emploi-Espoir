const modalForForgottenPwd = document.querySelector('.modalForForgottenPwd');

modalForForgottenPwd.addEventListener('click', (event) => {
    event.preventDefault();

    Swal.fire({
        title: "Administration",
        text: "Contactez l'administrateur principal pour effectuer cette modification.",
        icon: "warning",
        confirmButtonColor: "#3085d6",
    });
});
