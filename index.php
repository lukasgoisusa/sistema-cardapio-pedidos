<?php
include 'conexao.php';

$sql = "SELECT * FROM produtos ORDER BY nome";
$resultado = $conexao->query($sql);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cardápio do Restaurante</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f4f4;
            margin: 0;
            padding: 20px;
        }

        h1 {
            text-align: center;
            color: #333;
        }

        nav {
            text-align: center;
            margin: 20px 0;
        }

        nav a {
            display: inline-block;
            background: #16803c;
            color: white;
            text-decoration: none;
            padding: 10px 14px;
            margin: 5px;
            border-radius: 5px;
        }

        .cardapio {
            max-width: 600px;
            margin: 20px auto;
        }

        .produto {
            background: white;
            padding: 15px;
            margin-bottom: 12px;
            border-radius: 8px;
            border: 1px solid #ddd;
        }

        .produto h2 {
            margin-top: 0;
            color: #444;
        }

        .preco {
            color: #16803c;
            font-weight: bold;
        }
    </style>
</head>
<body>

    <h1>Cardápio do Restaurante</h1>

    <nav>
        <a href="index.php">Cardápio</a>
        <a href="cadastrar_produto.php">Cadastrar Produto</a>
        <a href="registrar_pedido.php">Registrar Pedido</a>
        <a href="consultar_pedidos.php">Consultar Pedidos</a>
    </nav>

    <div class="cardapio">
        <?php while ($produto = $resultado->fetch_assoc()) { ?>
            <div class="produto">
                <h2><?= htmlspecialchars($produto['nome']) ?></h2>

                <p><?= htmlspecialchars($produto['descricao'] ?? '') ?></p>

                <p class="preco">
                    R$ <?= number_format(
                        (float)$produto['preco'], 2, ',', '.'
                    ) ?>
                </p>
            </div>
        <?php } ?>
    </div>

</body>
</html>

<?php
$conexao->close();
?>