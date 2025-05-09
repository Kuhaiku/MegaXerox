<?php
require 'databaseconfig.php';

$mensagem = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verifica se os campos estão definidos e não estão vazios
    $nome = isset($_POST['nome']) ? trim($_POST['nome']) : null;
    $telefone = isset($_POST['telefone']) ? trim($_POST['telefone']) : null;

    if ($nome && $telefone) {
        $stmt = $conn->prepare("INSERT INTO clientes (nome, telefone) VALUES (?, ?)");
        $stmt->bind_param("ss", $nome, $telefone);
        
        if ($stmt->execute()) {
            $mensagem = "<p class='success-message'>Cliente cadastrado com sucesso!</p>";
        } else {
            $mensagem = "<p class='error-message'>Erro ao cadastrar cliente: " . $stmt->error . "</p>";
        }

        $stmt->close();
    } else {
        $mensagem = "<p class='error-message'></p>";
    }
}

// Consulta para obter todos os clientes cadastrados
$result = $conn->query("SELECT id_cliente, nome, telefone, data_cadastro FROM clientes ORDER BY data_cadastro DESC");

$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar Clientes</title>
    <style>
        .success-message { color: green; }
        .error-message { color: red; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        table, th, td { border: 1px solid black; }
        th, td { padding: 8px; text-align: left; }
    </style>
</head>
<body>

<h1>Cadastrar Clientes</h1>

<form action="" method="POST">
    <label for="nome">Nome do Cliente:</label>
    <input type="text" id="nome" name="nome" required>
    <label for="telefone">Telefone:</label>
    <input type="text" id="telefone" name="telefone" required>

    <input type="submit" value="Cadastrar Cliente">
</form>

<!-- Exibe a mensagem de sucesso ou erro após o envio -->
<?php
if ($mensagem) {
    echo $mensagem;
}
?>

<h2>Clientes Cadastrados</h2>

<?php if ($result && $result->num_rows > 0): ?>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Telefone</th>
                <th>Data de Cadastro</th>
            </tr>
        </thead>
        <tbody>
            <?php while($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['id_cliente']); ?></td>
                    <td><?php echo htmlspecialchars($row['nome']); ?></td>
                    <td><?php echo htmlspecialchars($row['telefone']); ?></td>
                    <td><?php echo htmlspecialchars($row['data_cadastro']); ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
<?php else: ?>
    <p>Nenhum cliente cadastrado até o momento.</p>
<?php endif; ?>

</body>
</html>
