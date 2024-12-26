let selectTag = document.getElementById('external_links_manager_linkType');
let form = document.querySelector('form');

let linkedInInput = document.getElementById('external_links_manager_linkedInLink');
let linkedInBlock = document.getElementById('linkedIn-block');
let githubInput = document.getElementById('external_links_manager_githubLink');
let githubBlock = document.getElementById('github-block');
let urlInput = document.getElementById('external_links_manager_url');
let urlBlock = document.getElementById('url-block');

// inputs error
const linkedFormatErrorSpan = document.querySelector('.linkedFormat-error');
const githubFormatErrorSpan = document.querySelector('.githubFormat-error');

// regex inputs
const linkedFormatRegex = /^https:\/\/www\.linkedin\.com\/in\/[a-zA-Z0-9-]+\/?$/;
const githubFormatRegex = /^https:\/\/github\.com\/[a-zA-Z0-9](?:[a-zA-Z0-9-]*[a-zA-Z0-9])?$/;

// this function contain code to forbidden form
function forbiddenForm()
{
    form.addEventListener('submit', (event) => {
        event.preventDefault();
    });
}



selectTag.addEventListener('input', (event) => {

    // LinkedIn manager
    if(event.target.value === 'LinkedIn') {
        linkedInBlock.style.display = 'block';
        linkedInInput.required = true;

        // event listener for LinkedIn input in case the format LinkedIn
        // is wrong
        linkedInInput.addEventListener('change', (event) => {
            if(!linkedFormatRegex.test(event.target.value)) {

                // forbid form submit if linked format for profil is wrong
                forbiddenForm();

                linkedFormatErrorSpan.style.display = 'inline';
            }
        });
    }
    else {
        linkedInBlock.style.display = 'none';
        linkedInInput.required = false;
    }

    // GitHub manager
    if(event.target.value === 'Github') {
        githubBlock.style.display = 'block';
        githubInput.required = true;

        // event listener for GitHub input in case the format GitHub
        // is wrong
        githubInput.addEventListener('change', (event) => {
            if(!githubFormatRegex.test(event.target.value)) {

                // forbid form submit if GitHub format for profil is wrong
                forbiddenForm();

                githubFormatErrorSpan.style.display = 'inline';
            }
        });

    }
    else {
        githubBlock.style.display = 'none';
        githubInput.required = false;
    }

    // Url manager
    if(event.target.value === 'Autre') {
        urlBlock.style.display = 'block';
        urlInput.required = true;
    }
    else {
        urlBlock.style.display = 'none';
        urlInput.required = false;
    }
});