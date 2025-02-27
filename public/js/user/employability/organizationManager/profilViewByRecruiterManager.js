let modal = document.getElementById("profileModal");
let openModalBtn = document.getElementById("open-modal");
let closeModalBtn = document.querySelector(".close");

openModalBtn.addEventListener("click", function(event) {
    event.preventDefault();
    modal.style.display = "flex";
});

closeModalBtn.addEventListener("click", function() {
    modal.style.display = "none";
});

window.addEventListener("click", function(event) {
    if (event.target === modal) {
        modal.style.display = "none";
    }
});