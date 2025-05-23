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

            echo "<p style='color:green;'>Caixa do cliente fechado com sucesso!</p>";
            echo "<a href='sua_pagina.php'>Voltar</a>";

        } catch (Exception $e) {
            $conn->rollback();
            echo "<p style='color:red;'>Erro ao fechar o caixa: " . $e->getMessage() . "</p>";
        }
    } else {
        echo "<p style='color:red;'>Não há vendas para este cliente.</p>";
    }
} else {
    echo "<p style='color:red;'>Requisição inválida.</p>";
}

$conn->close();
?>
