let selectTag = document.getElementById('external_links_manager_linkType');
let form = document.querySelector('form');

let linkedInInput = document.getElementById('external_links_manager_linkedInUrl');
let linkedInBlock = document.getElementById('linkedIn-block');
let githubInput = document.getElementById('external_links_manager_githubUrl');
let githubBlock = document.getElementById('github-block');
let urlInput = document.getElementById('external_links_manager_websiteUrl');
let urlBlock = document.getElementById('url-block');

// inputs error
const linkedFormatErrorSpan = document.querySelector('.linkedFormat-error');
const githubFormatErrorSpan = document.querySelector('.githubFormat-error');
const urlFormatErrorSpan = document.querySelector('.urlFormat-error');

// regex inputs
const linkedFormatRegex = /^https:\/\/www\.linkedin\.com\/in\/[a-zA-Z0-9%_\-]+\/?$/;
const githubFormatRegex = /^https:\/\/github\.com\/[a-zA-Z0-9](?:[a-zA-Z0-9-]*[a-zA-Z0-9])?$/;
const urlFormatRegex = /^https?:\/\/(www\.)?[a-zA-Z0-9-]+(\.[a-zA-Z]{2,})(\/[a-zA-Z0-9._~:\/?#\[\]@!$&'()*+,;=-]*)?$/;

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
            else {
                linkedFormatErrorSpan.style.display = 'none';

                form.submit();
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
            else {
                githubFormatErrorSpan.style.display = 'none';

                form.submit();
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

        // event listener for url is case the url format is wrong
        urlInput.addEventListener('change', (event) => {
            if(!urlFormatRegex.test(event.target.value)) {

                // forbid form submit if url format si wrong
                forbiddenForm();

                urlFormatErrorSpan.style.display = 'inline';
            }
            else {
                urlFormatErrorSpan.style.display = 'none';

                form.submit();
            }
        });
    }
    else {
        urlBlock.style.display = 'none';
        urlInput.required = false;
    }
});