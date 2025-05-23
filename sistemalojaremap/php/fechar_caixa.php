<?php
require 'databaseconfig.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_cliente = intval($_POST['id_cliente']);

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

            // Redireciona para clientes.php
            header("Location: clientes.php");
            exit;

        } catch (Exception $e) {
            $conn->rollback();
            // No catch, só redireciona sem mostrar erro
            header("Location: clientes.php");
            exit;
        }
    } else {
        // Se não tem vendas, também redireciona direto
        header("Location: clientes.php");
        exit;
    }
} else {
    // Requisição inválida, redireciona direto
    header("Location: clientes.php");
    exit;
}

$conn->close();
