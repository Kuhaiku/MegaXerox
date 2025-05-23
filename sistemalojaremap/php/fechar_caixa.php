<?php
require 'databaseconfig.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id_cliente = isset($_POST['id_cliente']) ? intval($_POST['id_cliente']) : null;

    if ($id_cliente) {
        // Exemplo: Deletar vendas deste cliente (fechar caixa)
        $stmt = $conn->prepare("DELETE FROM vendas WHERE id_cliente = ?");
        $stmt->bind_param("i", $id_cliente);

        if ($stmt->execute()) {
            echo "<script>alert('Caixa fechado com sucesso!'); window.location.href='pagina_anterior.php';</script>";
        } else {
            echo "<script>alert('Erro ao fechar o caixa: {$stmt->error}'); window.history.back();</script>";
        }

        $stmt->close();
    } else {
        echo "<script>alert('ID de cliente inválido.'); window.history.back();</script>";
    }
}

$conn->close();
?>
