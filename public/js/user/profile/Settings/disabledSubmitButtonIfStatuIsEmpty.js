/*
    This file manages the submitted button during
    the request treatment about removed user account
    bind to an organization
 */

const btn = document.querySelector('button');
const selectTag = document.getElementById('org_account_removal_request_statu');


document.addEventListener('DOMContentLoaded', () => {
    btn.disabled = selectTag.value === '';
});

// If select values are not empty, the submitted btn is clickable
selectTag.addEventListener('change', () => {
    btn.disabled = selectTag.value === '';
});
