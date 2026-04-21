<?php
require_once "../includes/bd_connect.php";
session_start();

// Feedback visual de sucesso ou erro (opcional, mas bom para a nota)
$mensagem = "";
if (isset($_GET['sucesso'])) {
    $mensagem = "<p style='color: green; text-align: center;'>Produto adicionado ao orçamento!</p>";
}

$sql = "SELECT * FROM produtos ORDER BY categoria, nome_produto";
$resultado = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="pt-PT">
<head>
    <meta charset="UTF-8">
    <title>Selecionar Produto — Comparador</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        .grid-produtos { display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 20px; padding: 20px; }
        .card { border: 1px solid #ddd; padding: 15px; border-radius: 8px; text-align: center; background: #fff; display: flex; flex-direction: column; justify-content: space-between; }
        .card h3 { margin-bottom: 10px; font-size: 1.2rem; }
        .categoria-tag { font-size: 0.8rem; color: #666; text-transform: uppercase; margin-bottom: 5px; display: block; }
        
        /* Estilo do Formulário */
        .form-adicionar { margin-top: 15px; border-top: 1px solid #eee; pt: 10px; }
        .input-qtd { width: 60px; padding: 5px; border-radius: 4px; border: 1px solid #ccc; margin-bottom: 10px; }
        .btn-add { background: #28a745; color: white; padding: 10px; border: none; border-radius: 5px; cursor: pointer; width: 100%; font-weight: bold; }
        .btn-add:hover { background: #218838; }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <h1>O que pretendes comparar hoje?</h1>
            <p>Escolhe os produtos e define a quantidade para o teu orçamento.</p>
            <?php echo $mensagem; ?>
        </header>

        <div class="grid-produtos">
            <?php if ($resultado->num_rows > 0): ?>
                <?php while($row = $resultado->fetch_assoc()): ?>
                    <div class="card">
                        <div>
                            <span class="categoria-tag"><?php echo $row['categoria']; ?></span>
                            <h3><?php echo $row['nome_produto']; ?></h3>
                            <p><?php echo $row['descricao']; ?></p>
                        </div>

                        <form action="api/adicionar_orcamento.php" method="POST" class="form-adicionar">
                            <input type="hidden" name="id_produto" value="<?php echo $row['id_produto']; ?>">
                            
                            <label style="font-size: 0.9rem;">Quantidade:</label><br>
                            <input type="number" name="quantidade" value="1" min="1" class="input-qtd">
                            
                            <button type="submit" class="btn-add">
                                Adicionar ao carrinho
                            </button>
                        </form>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>Ainda não há produtos disponíveis para comparação.</p>
            <?php endif; ?>
        </div>
        
        <div style="text-align: center; margin-top: 30px;">
            <a href="ver_carrinho.php" style="background: #007bff; color: white; padding: 15px 25px; text-decoration: none; border-radius: 5px; font-weight: bold;">
                🛒 Ver Meu Carrinho"
            </a>
        </div>
    </div>
</body>
</html>