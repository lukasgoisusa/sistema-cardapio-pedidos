<?php
include 'conexao.php';

$mensagem = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome'] ?? '');
    $descricao = trim($_POST['descricao'] ?? '');
    $preco = str_replace(',', '.', $_POST['preco'] ?? '');

    if ($nome === '' || !is_numeric($preco) || (float)$preco <= 0) {
        $mensagem = 'Informe um nome e um preço válido maior que zero.';
    } else {
        $sql = "INSERT INTO produtos (nome, descricao, preco)
                VALUES (?, ?, ?)";
        $stmt = $conexao->prepare($sql);
        $valor = (float)$preco;
        $stmt->bind_param("ssd", $nome, $descricao, $valor);

        if ($stmt->execute()) {
            $mensagem = 'Produto cadastrado com sucesso!';
        } else {
            $mensagem = 'Não foi possível cadastrar o produto.';
        }

        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar Produto</title>

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

        input, textarea, button {
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
            text-align: center;
            font-weight: bold;
        }
    </style>
</head>
<body>

    <form method="POST">
        <h1>Cadastrar Produto</h1>

        <label for="nome">Nome do produto</label>
        <input type="text" id="nome" name="nome" required>

        <label for="descricao">Descrição</label>
        <textarea id="descricao" name="descricao"></textarea>

        <label for="preco">Preço (ex.: 12,50)</label>
        <input type="text" id="preco" name="preco"
               inputmode="decimal" required>

        <button type="submit">Cadastrar</button>

        <p class="mensagem">
            <?= htmlspecialchars($mensagem) ?>
        </p>

        <a href="index.php">Voltar ao cardápio</a>
    </form>

</body>
</html>

<?php
$conexao->close();
?>