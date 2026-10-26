<?php
include 'conexao.php';

$mensagem = '';
$produtos = $conexao->query(
    "SELECT id, nome, preco FROM produtos ORDER BY nome"
);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $produto_id = (int)($_POST['produto_id'] ?? 0);
    $quantidade = (int)($_POST['quantidade'] ?? 0);

    $stmt = $conexao->prepare(
        "SELECT preco FROM produtos WHERE id = ?"
    );
    $stmt->bind_param("i", $produto_id);
    $stmt->execute();
    $produto = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if (!$produto || $quantidade < 1) {
        $mensagem = 'Selecione um produto e uma quantidade válida.';
    } else {
        $preco = (float)$produto['preco'];
        $total = $preco * $quantidade;

        $conexao->begin_transaction();

        try {
            $stmt = $conexao->prepare(
                "INSERT INTO pedidos (total) VALUES (?)"
            );
            $stmt->bind_param("d", $total);
            $stmt->execute();
            $pedido_id = $conexao->insert_id;
            $stmt->close();

            $stmt = $conexao->prepare(
                "INSERT INTO itens_pedido
                (pedido_id, produto_id, quantidade, preco_unitario)
                VALUES (?, ?, ?, ?)"
            );
            $stmt->bind_param(
                "iiid", $pedido_id, $produto_id, $quantidade, $preco
            );
            $stmt->execute();
            $stmt->close();

            $conexao->commit();
            $mensagem = "Pedido #$pedido_id registrado com sucesso! Total: R$ "
                . number_format($total, 2, ',', '.');
        } catch (Throwable $erro) {
            $conexao->rollback();
            $mensagem = 'Não foi possível registrar o pedido.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Pedido</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f4f4;
            padding: 20px;
        }
        form {
            max-width: 450px;
            margin: 30px auto;
            padding: 20px;
            background: white;
            border-radius: 8px;
        }
        select, input, button {
            width: 100%;
            box-sizing: border-box;
            padding: 10px;
            margin: 8px 0 15px;
        }
        button {
            background: #16803c;
            color: white;
            border: none;
            cursor: pointer;
        }
        .mensagem {
            font-weight: bold;
        }
    </style>
</head>
<body>
    <form method="POST">
        <h1>Registrar Pedido</h1>

        <label for="produto_id">Produto</label>
        <select name="produto_id" id="produto_id" required>
            <option value="">Selecione um produto</option>
            <?php while ($p = $produtos->fetch_assoc()) { ?>
                <option value="<?= (int)$p['id'] ?>">
                    <?= htmlspecialchars($p['nome']) ?>
                    — R$ <?= number_format((float)$p['preco'], 2, ',', '.') ?>
                </option>
            <?php } ?>
        </select>

        <label for="quantidade">Quantidade</label>
        <input type="number" name="quantidade" id="quantidade"
               min="1" step="1" value="1" required>

        <button type="submit">Registrar Pedido</button>

        <p class="mensagem">
            <?= htmlspecialchars($mensagem) ?>
        </p>

        <a href="index.php">Voltar ao cardápio</a>
    </form>
</body>
</html>

<?php $conexao->close(); ?>