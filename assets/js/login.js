// Espera que o documento carregue totalmente
document.addEventListener('DOMContentLoaded', function() {
    const loginForm = document.querySelector('form');

    if (loginForm) {
        loginForm.addEventListener('submit', function(e) {
            e.preventDefault(); 
            console.log("JS detetou o clique no login!");

            const email = document.getElementById('email').value;
            const senha = document.getElementById('senha').value;

            const formData = new FormData();
            formData.append('email', email);
            formData.append('senha', senha);

            fetch('../auth/auth_login.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.sucesso) {
                    alert("Login efetuado com sucesso!");
                    window.location.href = 'index.php';
                } else {
                    alert("Erro: " + data.mensagem);
                }
            })
            .catch(error => {
                console.error('Erro no fetch:', error);
                alert("Erro de ligação ao servidor.");
            });
        });
    } else {
        console.error("Formulário não encontrado!");
    }
});