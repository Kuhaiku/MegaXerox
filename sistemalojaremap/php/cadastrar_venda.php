<?php
// Incluir a configuração do banco de dados
include 'databaseconfig.php';

// Variáveis para armazenar mensagens de sucesso/erro
$successMsg = "";
$errorMsg = "";

// Verificar se o formulário foi enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_cliente = $_POST['id_cliente'];
    $descricao = $_POST['descricao'];
    $data_venda = $_POST['data_venda'];
    $valor_total = $_POST['valor_total'];

    if (!empty($id_cliente) && !empty($descricao) && !empty($data_venda) && !empty($valor_total)) {
        // Preparar e vincular
        $stmt = $conn->prepare("INSERT INTO vendas (id_cliente, descricao, data_venda, valor_total) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("issd", $id_cliente, $descricao, $data_venda, $valor_total);

        if ($stmt->execute()) {
            $successMsg = "Venda cadastrada com sucesso!";
        } else {
            $errorMsg = "Erro ao cadastrar venda: " . $stmt->error;
        }

        // Fechar a declaração
        $stmt->close();
    } else {
        $errorMsg = "Todos os campos são obrigatórios.";
    }
}

// Fechar a conexão com o banco de dados
$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar Venda</title>
</head>
<body>
    <h1>Cadastrar Venda</h1>

    <?php if ($successMsg): ?>
        <p style="color: green;"><?php echo $successMsg; ?></p>
    <?php endif; ?>

    <?php if ($errorMsg): ?>
        <p style="color: red;"><?php echo $errorMsg; ?></p>
    <?php endif; ?>

    <form action="cadastrar_venda.php" method="POST">
        <label for="id_cliente">Cliente:</label><br>
        <select id="id_cliente" name="id_cliente">
            <?php
            // Incluir a configuração do banco de dados novamente para listar os clientes
            include 'databaseconfig.php';

            $result = $conn->query("SELECT id_cliente, nome FROM clientes ORDER BY nome ASC");

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<option value='" . $row['id_cliente'] . "'>" . $row['nome'] . "</option>";
                }
            } else {
                echo "<option value=''>Nenhum cliente cadastrado</option>";
            }

            // Fechar a conexão com o banco de dados
            $conn->close();
            ?>
        </select><br><br>

        <label for="descricao">Descrição da Venda:</label><br>
        <input type="text" id="descricao" name="descricao"><br><br>

        <label for="data_venda">Data da Venda:</label><br>
        <input type="date" id="data_venda" name="data_venda"><br><br>

        <label for="valor_total">Valor Total (R$):</label><br>
        <input type="number" step="0.01" id="valor_total" name="valor_total"><br><br>

        <input type="submit" value="Cadastrar">
    </form>
</body>
</html>
