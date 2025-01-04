const flashMsg = document.getElementById('hide-flashMsg');
if(flashMsg !== null) {
    setTimeout(()=> {
        flashMsg.style.display = 'none';
    }, 3000);
}