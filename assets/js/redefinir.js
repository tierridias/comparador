document.getElementById('form-redefinir').addEventListener('submit', function(e) {
    e.preventDefault();

    const token = document.getElementById('token').value;
    const senha = document.getElementById('nova_senha').value;
    const conf = document.getElementById('conf_senha').value;

    if (senha !== conf) {
        alert("As passwords não coincidem!");
        return;
    }

    const formData = new FormData();
    formData.append('token', token);
    formData.append('senha', senha);

    fetch('auth_redefinir.php', {
        method: 'POST',
        body: formData
    })
    .then(r => r.json())
    .then(data => {
        if (data.sucesso) {
            alert(data.mensagem);
            window.location.href = 'login.php'; // Volta automático para o login
        } else {
            alert("Erro: " + data.mensagem);
        }
    })
    .catch(err => console.error(err));
});