<?php
require 'databaseconfig.php';

$mensagem = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verifica se o campo "nome" está definido e não está vazio
    $nome = isset($_POST['nome']) ? trim($_POST['nome']) : null;

    if ($nome) {
        $stmt = $conn->prepare("INSERT INTO clientes (nome) VALUES (?)");
        $stmt->bind_param("s", $nome);
        
        if ($stmt->execute()) {
            $mensagem = "<p class='success-message'>Cliente cadastrado com sucesso!</p>";
        } else {
            $mensagem = "<p class='error-message'>Erro ao cadastrar cliente: " . $stmt->error . "</p>";
        }

        $stmt->close();
    } else {
        $mensagem = "<p class='error-message'>O nome do cliente é obrigatório.</p>";
    }
}

// Consulta para obter todos os clientes cadastrados
$result = $conn->query("SELECT id_cliente, nome, data_cadastro FROM clientes ORDER BY data_cadastro DESC");

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

    <input type="submit" value="Cadastrar Cliente">
</form>

<!-- Exibe a mensagem de sucesso ou erro após o envio -->
<?php
//if ($mensagem) {
//    echo $mensagem;
//}
?>

<h2>Clientes Cadastrados</h2>

<?php if ($result->num_rows > 0): ?>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Data de Cadastro</th>
            </tr>
        </thead>
        <tbody>
            <?php while($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['id_cliente']; ?></td>
                    <td><?php echo $row['nome']; ?></td>
                    <td><?php echo $row['data_cadastro']; ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
<?php else: ?>
    <p>Nenhum cliente cadastrado até o momento.</p>
<?php endif; ?>

</body>
</html>
