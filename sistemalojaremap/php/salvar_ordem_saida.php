<?php
include 'databaseconfig.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $entry_id = intval($_POST['entry_id']);
    $data_saida = $_POST['data_saida'];
    $servico_realizado = mysqli_real_escape_string($conn, $_POST['servico_realizado']);
    $metodo_pagamento = $_POST['metodo_pagamento'];
    $valor = floatval($_POST['valor']);
    $garantia = $_POST['garantia'];
    
    // Inserir no banco de dados
    $sql = "INSERT INTO exit_notes (entry_id, data_saida, servico_realizado, metodo_pagamento, valor, garantia) 
            VALUES ('$entry_id', '$data_saida', '$servico_realizado', '$metodo_pagamento', '$valor', '$garantia')";

    if (mysqli_query($conn, $sql)) {
        echo "Ordem de saída salva com sucesso!";
    } else {
        echo "Erro ao salvar: " . mysqli_error($conn);
    }

    mysqli_close($conn);
} else {
    echo "Método de requisição inválido.";
}
?>
