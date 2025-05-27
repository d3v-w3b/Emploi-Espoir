const premiumMsgFlash = document.getElementById('modal-for-premium');

if (premiumMsgFlash) {
    Swal.fire({
        icon: "warning",
        title: "Cette option est disponible uniquement pour les recruteurs Premium",
        html: '<a href="/organization/subscription/premium-subscription">Passer en premium</a>',
        showConfirmButton: false,
    });
}