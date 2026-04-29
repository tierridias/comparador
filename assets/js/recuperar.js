document.getElementById('form-recuperar').addEventListener('submit', function(e) {
    e.preventDefault();
    const email = document.getElementById('email').value;
    const formData = new FormData();
    formData.append('email', email);

    fetch('../auth/auth_recuperar.php', {
        method: 'POST',
        body: formData
    })
    .then(r => r.json())
    .then(data => {
        alert(data.mensagem);
    })
    .catch(err => console.error(err));
});