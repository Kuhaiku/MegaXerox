<?php
require 'databaseconfig.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id_cliente = isset($_POST['id_cliente']) ? intval($_POST['id_cliente']) : null;

    if ($id_cliente) {
        // Iniciar uma transação para garantir integridade dos dados
        $conn->begin_transaction();

        try {
            // 1. Inserir os registros da tabela vendas na tabela vendas_fechadas,
            // incluindo a data de fechamento
            $sqlInsert = "
                INSERT INTO vendas_fechadas (id_venda, id_cliente, descricao, data_venda, valor_total, data_fechamento)
                SELECT id_venda, id_cliente, descricao, data_venda, valor_total, NOW()
                FROM vendas
                WHERE id_cliente = ?
            ";

            $stmtInsert = $conn->prepare($sqlInsert);
            if (!$stmtInsert) {
                throw new Exception("Erro na preparação da consulta de inserção: " . $conn->error);
            }
            $stmtInsert->bind_param("i", $id_cliente);

            if (!$stmtInsert->execute()) {
                throw new Exception("Erro ao arquivar vendas: " . $stmtInsert->error);
            }

            $stmtInsert->close();

            // 2. Deletar os registros da tabela vendas
            $stmtDelete = $conn->prepare("DELETE FROM vendas WHERE id_cliente = ?");
            if (!$stmtDelete) {
                throw new Exception("Erro na preparação da consulta de deleção: " . $conn->error);
            }
            $stmtDelete->bind_param("i", $id_cliente);

            if (!$stmtDelete->execute()) {
                throw new Exception("Erro ao deletar vendas: " . $stmtDelete->error);
            }

            $stmtDelete->close();

            // 3. Confirmar as operações
            $conn->commit();

            echo "<script>alert('Caixa fechado e vendas arquivadas com sucesso!'); window.location.href='../clientes.php';</script>";

        } catch (Exception $e) {
            // Se houve erro, desfaz tudo
            $conn->rollback();
            // Escapa a mensagem de erro para evitar erros de sintaxe no JavaScript
            $errorMessage = addslashes($e->getMessage());
            echo "<script>alert('Erro: " . $errorMessage . "'); window.history.back();</script>";
        }

    } else {
        echo "<script>alert('ID de cliente inválido.'); window.history.back();</script>";
    }
}

$conn->close();
?>
