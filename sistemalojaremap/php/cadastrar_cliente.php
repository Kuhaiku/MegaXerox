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

$conn->close();
?>

<form action="" method="POST">
    <label for="nome">Nome do Cliente:</label>
    <input type="text" id="nome" name="nome" required>

    <input type="submit" value="Cadastrar Cliente">
</form>

<!-- Exibe a mensagem de sucesso ou erro após o envio -->
<?php
if ($mensagem) {
    echo $mensagem;
}
?>
