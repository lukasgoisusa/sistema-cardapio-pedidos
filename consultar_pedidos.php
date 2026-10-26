<?php
include 'conexao.php';

$sql = "SELECT
            p.id AS pedido_id,
            p.data_pedido,
            p.total,
            pr.nome AS produto,
            ip.quantidade,
            ip.preco_unitario
        FROM pedidos p
        INNER JOIN itens_pedido ip ON p.id = ip.pedido_id
        INNER JOIN produtos pr ON ip.produto_id = pr.id
        ORDER BY p.id DESC";

$resultado = $conexao->query($sql);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consultar Pedidos</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f4f4;
            padding: 20px;
        }

        h1 {
            text-align: center;
        }

        .pedido {
            max-width: 600px;
            margin: 20px auto;
            padding: 18px;
            background: white;
            border: 1px solid #ddd;
            border-radius: 8px;
        }

        .total {
            color: #16803c;
            font-weight: bold;
        }

        a {
            display: block;
            text-align: center;
            margin: 20px;
        }
    </style>
</head>
<body>

    <h1>Pedidos Registrados</h1>

    <?php if ($resultado->num_rows > 0) { ?>
        <?php while ($pedido = $resultado->fetch_assoc()) { ?>
            <div class="pedido">
                <h2>Pedido #<?= (int)$pedido['pedido_id'] ?></h2>

                <p>
                    <strong>Data:</strong>
                    <?= htmlspecialchars($pedido['data_pedido']) ?>
                </p>

                <p>
                    <strong>Produto:</strong>
                    <?= htmlspecialchars($pedido['produto']) ?>
                </p>

                <p>
                    <strong>Quantidade:</strong>
                    <?= (int)$pedido['quantidade'] ?>
                </p>

                <p>
                    <strong>Preço unitário:</strong>
                    R$ <?= number_format(
                        (float)$pedido['preco_unitario'], 2, ',', '.'
                    ) ?>
                </p>

                <p class="total">
                    Total do pedido: R$ <?= number_format(
                        (float)$pedido['total'], 2, ',', '.'
                    ) ?>
                </p>
            </div>
        <?php } ?>
    <?php } else { ?>
        <p>Nenhum pedido registrado.</p>
    <?php } ?>

    <a href="index.php">Voltar ao cardápio</a>

</body>
</html>

<?php
$conexao->close();
?>