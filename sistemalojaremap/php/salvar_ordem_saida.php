<?php
include 'databaseconfig.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // --- COLETA DOS DADOS DO FORMULÁRIO ---
    $entry_id = intval($_POST['entry_id']);
    $data_saida = trim($_POST['data_saida']); // Já vem como YYYY-MM-DD do input type="date"
    $servico_realizado = trim($_POST['servico_realizado']);
    $metodo_pagamento = trim($_POST['metodo_pagamento']);
    
    // Converte vírgula em ponto para tratar o valor
    $valor_str = str_replace(',', '.', $_POST['valor']);
    $valor = floatval($valor_str);

    $garantia_br = trim($_POST['garantia']); // Vem como DD/MM/YYYY do JavaScript

    // --- VERIFICAÇÃO DE CAMPOS VAZIOS (permite valor 0) ---
    if (empty($entry_id) || empty($data_saida) || empty($servico_realizado) || empty($metodo_pagamento) || !isset($_POST['valor']) || $_POST['valor'] === '' || empty($garantia_br)) {
        echo "Erro: Todos os campos são obrigatórios!";
        exit;
    }

    // --- CONVERSÃO DA DATA DE GARANTIA ---
    $date_obj = DateTime::createFromFormat('d/m/Y', $garantia_br);
    if ($date_obj === false) {
        echo "Erro: Formato de data da garantia inválido. Esperado DD/MM/YYYY.";
        exit;
    }
    $garantia_mysql = $date_obj->format('Y-m-d');

    // --- PREPARAÇÃO E EXECUÇÃO DA QUERY SQL ---
    $sql = "INSERT INTO exit_notes (entry_id, data_saida, servico_realizado, metodo_pagamento, valor, garantia) 
            VALUES (?, ?, ?, ?, ?, ?)";

    if ($stmt = mysqli_prepare($conn, $sql)) {
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
