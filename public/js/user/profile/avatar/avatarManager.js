// get the button "modifier" for show the modal
const btnModifier = document.getElementById('btn-profilePic');

const avatarFormContainer = document.getElementById('avatar-form-container');
const form = document.querySelector('form');

const imgInput = document.getElementById('avatar_manager_inputChoice_1');
const fileInputBlock = document.getElementById('fileInput-block');
const fileInput = document.getElementById('avatar_manager_profilePic');

const profilPicError = document.querySelector('li');

console.log(profilPicError);


btnModifier.addEventListener('click', (event) => {
    event.preventDefault();

    avatarFormContainer.style.display = 'block';

    form.addEventListener('input', (event) => {
        if(imgInput.checked) {
            fileInputBlock.style.display = 'block';
            fileInput.click();
        }
        else {
            fileInputBlock.style.display = 'none';
        }
    });
});

// If error exists during the manage of profil pic, show the form and this error
if (profilPicError) {
    avatarFormContainer.style.display = 'block';
    fileInputBlock.style.display = 'block';
}
else {
    avatarFormContainer.style.display = 'none';
    fileInputBlock.style.display = 'none';
}
