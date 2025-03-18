<?php
include 'databaseconfig.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = $_POST['nome'];
    $valor_compra = $_POST['valor_compra'];
    $produto_comprado = $_POST['produto_comprado'];
    $data_compra = $_POST['data_compra'];

    $sql = "INSERT INTO fidelidade_compras (nome, valor_compra, produto_comprado, data_compra)
            VALUES ('$nome', '$valor_compra', '$produto_comprado', '$data_compra')";

    if ($conn->query($sql) === TRUE) {
        echo "Compra cadastrada com sucesso!";
    } else {
        echo "Erro ao cadastrar compra: " . $conn->error;
    }
}

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Compra - Programa de Fidelidade</title>
</head>
<body>
    <h1>Cadastrar Nova Compra</h1>
    <form method="POST">
        <label for="nome">Nome do Cliente:</label>
        <input type="text" name="nome" required><br><br>

        <label for="valor_compra">Valor da Compra:</label>
        <input type="text" name="valor_compra" required><br><br>

        <label for="produto_comprado">O que foi comprado:</label>
        <input type="text" name="produto_comprado" required><br><br>

        <label for="data_compra">Data da Compra:</label>
        <input type="date" name="data_compra" required><br><br>

        <button type="submit">Cadastrar Compra</button>
    </form>

    <h2>Cadastrar Nova Compra</h2>
    <form method="POST">
        <label for="nome">Nome:</label>
        <input type="text" name="nome" id="nome" required><br><br>

        <label for="valor_compra">Valor da Compra:</label>
        <input type="number" step="0.01" name="valor_compra" id="valor_compra" required><br><br>

        <label for="produto_comprado">O que foi Comprado:</label>
        <input type="text" name="produto_comprado" id="produto_comprado" required><br><br>

        <label for="data_compra">Data da Compra:</label>
        <input type="date" name="data_compra" id="data_compra" required><br><br>

        <button type="submit">Cadastrar</button>
    </form>
</body>
</html>
