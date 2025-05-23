<?php
require 'databaseconfig.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_cliente = intval($_POST['id_cliente']);
    $senha = $_POST['senha'];

    // Verifica a senha
    if ($senha !== "8812") {
        header("Location: clientes.php?erro=senha");
        exit;
    }

    // Buscar todas as vendas do cliente
    $result = $conn->query("SELECT * FROM vendas WHERE id_cliente = $id_cliente");

    if ($result && $result->num_rows > 0) {
        // Iniciar transação
        $conn->begin_transaction();

        try {
            while ($venda = $result->fetch_assoc()) {
                // Inserir na tabela vendas_fechadas
                $stmt_insert = $conn->prepare("INSERT INTO vendas_fechadas (id_venda, id_cliente, descricao, data_venda, valor_total) VALUES (?, ?, ?, ?, ?)");
                $stmt_insert->bind_param(
                    "iissd", 
                    $venda['id_venda'], 
                    $venda['id_cliente'], 
                    $venda['descricao'], 
                    $venda['data_venda'], 
                    $venda['valor_total']
                );
                $stmt_insert->execute();
                $stmt_insert->close();
            }

            // Deletar da tabela de vendas
            $conn->query("DELETE FROM vendas WHERE id_cliente = $id_cliente");

            // Commit na transação
            $conn->commit();

            header("Location: clientes.php?status=sucesso");
            exit;

        } catch (Exception $e) {
            $conn->rollback();
            header("Location: clientes.php?erro=transacao");
            exit;
        }
    } else {
        header("Location: clientes.php?erro=semvendas");
        exit;
    }
} else {
    header("Location: clientes.php?erro=metodo");
    exit;
}

$conn->close();
?>
