<?php
include 'databaseconfig.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // --- COLETA DOS DADOS DO FORMULÁRIO ---
    $entry_id = intval($_POST['entry_id']);
    $data_saida = trim($_POST['data_saida']); // Já vem como YYYY-MM-DD do input type="date"
    $servico_realizado = trim($_POST['servico_realizado']);
    $metodo_pagamento = trim($_POST['metodo_pagamento']);
    $valor = floatval($_POST['valor']);
    $garantia_br = trim($_POST['garantia']); // Vem como DD/MM/YYYY do JavaScript

    // --- VERIFICAÇÃO DE CAMPOS VAZIOS ---
    if (empty($entry_id) || empty($data_saida) || empty($servico_realizado) || empty($metodo_pagamento) || empty($valor) || empty($garantia_br)) {
        echo "Erro: Todos os campos são obrigatórios!";
        exit;
    }

    // --- INÍCIO DA CORREÇÃO ---
    // Converte a data da garantia do formato DD/MM/YYYY para YYYY-MM-DD
    $date_obj = DateTime::createFromFormat('d/m/Y', $garantia_br);
    if ($date_obj === false) {
        echo "Erro: Formato de data da garantia inválido. Esperado DD/MM/YYYY.";
        exit;
    }
    $garantia_mysql = $date_obj->format('Y-m-d');
    // --- FIM DA CORREÇÃO ---

    // --- PREPARAÇÃO E EXECUÇÃO DA QUERY SQL ---
    $sql = "INSERT INTO exit_notes (entry_id, data_saida, servico_realizado, metodo_pagamento, valor, garantia) 
            VALUES (?, ?, ?, ?, ?, ?)";

    if ($stmt = mysqli_prepare($conn, $sql)) {
        // Note que o último parâmetro agora é a variável corrigida: $garantia_mysql
        mysqli_stmt_bind_param($stmt, "isssds", $entry_id, $data_saida, $servico_realizado, $metodo_pagamento, $valor, $garantia_mysql);
        
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
