<?php
include 'databaseconfig.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $entry_id = intval($_POST['entry_id']);
    $data_saida = trim($_POST['data_saida']);
    $servico_realizado = trim($_POST['servico_realizado']);
    $metodo_pagamento = trim($_POST['metodo_pagamento']);
    $valor = floatval($_POST['valor']);
    $garantia = isset($_POST['garantia']) ? trim($_POST['garantia']) : '';

    // Verifica se todos os campos estão preenchidos
    if (empty($entry_id) || empty($data_saida) || empty($servico_realizado) || empty($metodo_pagamento) || empty($valor) || empty($garantia)) {
        echo "Erro: Todos os campos são obrigatórios!";
        exit;
    }

    // Usando Prepared Statement para evitar SQL Injection
    $sql = "INSERT INTO exit_notes (entry_id, data_saida, servico_realizado, metodo_pagamento, valor, garantia) 
            VALUES (?, ?, ?, ?, ?, ?)";

    if ($stmt = mysqli_prepare($conn, $sql)) {
        mysqli_stmt_bind_param($stmt, "isssds", $entry_id, $data_saida, $servico_realizado, $metodo_pagamento, $valor, $garantia);
        
        if (mysqli_stmt_execute($stmt)) {
            echo "Ordem de saída salva com sucesso!";
        } else {
            echo "Erro ao salvar a ordem de saída: " . mysqli_stmt_error($stmt);
        }

        mysqli_stmt_close($stmt);
    } else {
        echo "Erro na preparação da query: " . mysqli_error($conn);
    }

    mysqli_close($conn);
} else {
    echo "Método de requisição inválido.";
}
?>
