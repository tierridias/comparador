document.querySelector('form').addEventListener('submit', function(e) {
    e.preventDefault(); 

    const nome = document.getElementById('nome').value;
    const email = document.getElementById('email').value;
    const senha = document.getElementById('senha').value;
    const senha_conf = document.getElementById('senha_conf').value;

    if (senha !== senha_conf) {
        alert("As palavras-passe não coincidem!");
        return;
    }

    const formData = new FormData();
    formData.append('nome', nome);
    formData.append('email', email);
    formData.append('senha', senha);

    fetch('../auth/auth_register.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.sucesso) {
            alert("Conta criada! Verifica o teu e-mail.");
            window.location.href = 'verificar_conta.php?email=' + encodeURIComponent(email);
        } else {
            alert("Erro: " + data.mensagem);
        }
    })
    .catch(error => console.error('Erro:', error));
});