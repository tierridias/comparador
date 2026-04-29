document.getElementById('form-verify').addEventListener('submit', function(e) {
    e.preventDefault();

    const email = document.getElementById('email').value;
    const codigo = document.getElementById('codigo').value;

    const formData = new FormData();
    formData.append('email', email);
    formData.append('codigo', codigo);

    fetch('../auth/auth_verify.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.sucesso) {
            alert("Sucesso! Conta ativada.");
            window.location.href = 'login.php';
        } else {
            alert("Erro: " + data.mensagem);
        }
    })
    .catch(error => console.error('Erro:', error));
});