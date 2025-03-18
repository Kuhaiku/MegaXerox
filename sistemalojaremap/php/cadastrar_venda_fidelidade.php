<?php
include 'databaseconfig.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $cliente_id = $_POST['cliente_id'];
    $unidade = $_POST['unidade'];
    $descricao_compra = $_POST['descricao_compra'];
    $valor = str_replace(',', '.', $_POST['valor']);
    $data_compra = $_POST['data_compra'];

    // Validar valor da compra
    if (!is_numeric($valor)) {
        echo "O valor da compra precisa ser um número válido.";
    } else {
        $sql = "INSERT INTO vendas_fidelidade (cliente_id, unidade, descricao_compra, valor, data_compra)
                VALUES ('$cliente_id', '$unidade', '$descricao_compra', '$valor', '$data_compra')";

        if ($conn->query($sql) === TRUE) {
            echo "Venda cadastrada com sucesso!";
        } else {
            echo "Erro ao cadastrar venda: " . $conn->error;
        }
    }
}

// Buscar clientes cadastrados
$result = $conn->query("SELECT * FROM clientes_fidelidade");
$clientes = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Venda - Programa de Fidelidade</title>
</head>
<body>
    <h1>Cadastrar Venda</h1>
    <form method="POST">
        <label for="cliente_id">Cliente:</label>
        <select name="cliente_id" required>
            <?php foreach ($clientes as $cliente): ?>
                <option value="<?= $cliente['id']; ?>"><?= $cliente['nome']; ?></option>
            <?php endforeach; ?>
        </select><br><br>

        <label for="unidade">Unidade:</label>
        <input type="number" name="unidade" required><br><br>

        <label for="descricao_compra">Descrição da Compra:</label>
        <input type="text" name="descricao_compra" required><br><br>

        <label for="valor">Valor:</label>
        <input type="text" name="valor" required><br><br>

        <label for="data_compra">Data da Compra:</label>
        <input type="date" name="data_compra" required><br><br>

        <button type="submit">Cadastrar Venda</button>
    </form>
</body>
</html>
