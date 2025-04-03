
<?php
// Conexão com o banco
include '../php/databaseconfig.php';

// Pegando os dados da ordem de entrada via GET
$id = $_GET['id'] ?? '';
$cliente = $_GET['cliente'] ?? '';
$modelo = $_GET['modelo'] ?? '';
$data_entrada = $_GET['data_entrada'] ?? '';

// Calculando a data de garantia (3 meses a partir da data de entrega)
$data_entrega = date('Y-m-d'); // Data atual como padrão
$data_garantia = date('Y-m-d', strtotime('+3 months', strtotime($data_entrega)));

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Gerar Ordem de Saída</title>
<link rel="stylesheet" href="../css/exitorder.css">
<style>
    body {
        font-family: Arial, sans-serif;
        margin: 20px;
    }
    form {
        max-width: 500px;
        margin: auto;
    }
    label {
        font-weight: bold;
        display: block;
        margin-top: 10px;
    }
    input, select {
        width: 100%;
        padding: 8px;
        margin-top: 5px;
    }
    .submit-btn {
        margin-top: 15px;
        background-color: #007bff;
        color: white;
        padding: 10px;
        border: none;
        cursor: pointer;
    }
    .submit-btn:hover {
        background-color: #0056b3;
    }
</style>
</head>
<body>

<h2>Gerar Ordem de Saída</h2>

<form action="save_exitorder.php" method="POST">
    <label>ID da Ordem de Entrada:</label>
    <input type="text" name="id_entrada" value="<?= htmlspecialchars($id) ?>" readonly>

    <label>Cliente:</label>
    <input type="text" name="cliente" value="<?= htmlspecialchars($cliente) ?>" readonly>

    <label>Modelo:</label>
    <input type="text" name="modelo" value="<?= htmlspecialchars($modelo) ?>" readonly>

    <label>Data de Entrada:</label>
    <input type="date" name="data_entrada" value="<?= htmlspecialchars($data_entrada) ?>" readonly>

    <label>Serviço Realizado:</label>
    <input type="text" name="servico" required>

    <label>Data de Entrega:</label>
    <input type="date" name="data_entrega" value="<?= htmlspecialchars($data_entrega) ?>" required>

    <label>Método de Pagamento:</label>
    <select name="metodo_pagamento" required>
        <option value="Dinheiro">Dinheiro</option>
        <option value="Cartão">Cartão</option>
        <option value="Pix">Pix</option>
    </select>

    <label>Valor:</label>
    <input type="number" name="valor" step="0.01" required>

    <label>Garantia (3 meses):</label>
    <input type="date" name="garantia" value="<?= htmlspecialchars($data_garantia) ?>" readonly>

    <button type="submit" class="submit-btn">Salvar Ordem de Saída</button>
</form>

</body>
</html>
