<?php
// Incluir a configuração do banco de dados
include 'databaseconfig.php';

// Variáveis para armazenar mensagens de sucesso/erro
$successMsg = "";
$errorMsg = "";

// Verificar se o formulário foi enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $_POST['nome'];

    if (!empty($nome)) {
        // Preparar e vincular
        $stmt = $conn->prepare("INSERT INTO clientes (nome) VALUES (?)");
        $stmt->bind_param("s", $nome);

        if ($stmt->execute()) {
            $successMsg = "Cliente cadastrado com sucesso!";
        } else {
            $errorMsg = "Erro ao cadastrar cliente: " . $stmt->error;
        }

        // Fechar a declaração
        $stmt->close();
    } else {
        $errorMsg = "O nome do cliente é obrigatório.";
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
    <title>Cadastrar Cliente</title>
</head>
<body>
    <h1>Cadastrar Cliente</h1>

    <?php if ($successMsg): ?>
        <p style="color: green;"><?php echo $successMsg; ?></p>
    <?php endif; ?>

    <?php if ($errorMsg): ?>
        <p style="color: red;"><?php echo $errorMsg; ?></p>
    <?php endif; ?>

    <form action="clientes.php" method="POST">
        <label for="nome">Nome do Cliente:</label><br>
        <input type="text" id="nome" name="nome"><br><br>
        <input type="submit" value="Cadastrar">
    </form>

    <h2>Clientes Cadastrados</h2>
    <ul>
        <?php
        // Incluir a configuração do banco de dados novamente para listar os clientes
        include 'databaseconfig.php';

        $result = $conn->query("SELECT * FROM clientes ORDER BY data_cadastro DESC");

        if ($result->num_rows > 0) {
            // Exibir os clientes
            while ($row = $result->fetch_assoc()) {
                echo "<li>ID: " . $row['id_cliente'] . " - Nome: " . $row['nome'] . " - Data de Cadastro: " . $row['data_cadastro'] . "</li>";
            }
        } else {
            echo "<li>Nenhum cliente cadastrado.</li>";
        }

        // Fechar a conexão com o banco de dados
        $conn->close();
        ?>
    </ul>
</body>
</html>
