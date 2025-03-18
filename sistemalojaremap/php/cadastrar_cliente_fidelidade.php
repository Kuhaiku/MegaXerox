<?php
include 'databaseconfig.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = $_POST['nome'];

    if (empty($nome)) {
        echo "O nome do cliente é obrigatório.";
    } else {
        $sql = "INSERT INTO clientes_fidelidade (nome) VALUES ('$nome')";

        if ($conn->query($sql) === TRUE) {
            echo "Cliente cadastrado com sucesso!";
        } else {
            echo "Erro ao cadastrar cliente: " . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Cliente - Programa de Fidelidade</title>
</head>
<body>
    <h1>Cadastrar Novo Cliente</h1>
    <form method="POST">
        <label for="nome">Nome do Cliente:</label>
        <input type="text" name="nome" required><br><br>

        <button type="submit">Cadastrar Cliente</button>
    </form>
</body>
</html>
