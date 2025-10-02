<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="icon" type="image/x-icon" href="../src/favicon.ico" />
<link rel="stylesheet" href="../css/entryorderdtbase.css" />
<title>Ordens de Saída</title>
<style>
/* Estilos adicionais para melhorar a visualização dos dados */
.box {
    border-left: 5px solid #005f73; /* Cor primária para destacar */
    transition: box-shadow 0.3s;
}
.box:hover {
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
}
.highlight {
    font-size: 1.1em;
    font-weight: bold;
    color: #005f73;
    margin-bottom: 8px;
    display: block;
    border-bottom: 1px dashed #ddd;
    padding-bottom: 5px;
}
.info-field {
    padding: 3px 0;
}
.action-link {
    display: block; 
    background-color:#007bff; 
    color:white; 
    padding:10px; 
    text-align:center; 
    margin-top:10px; 
    border-radius:5px;
}
.action-link:hover {
    background-color: #0056b3;
}
</style>
</head>
<body>
<?php
include '../php/databaseconfig.php';

echo "<header>
      <h1>Ordem de Saída</h1>
      <a href='../index.html'><h3>inicio</h3></a>
    </header>";

// Consulta com JOIN para obter dados completos da Ordem de Entrada e Saída
$sql = "SELECT 
            en.id AS id_saida, 
            en.data_saida, 
            en.servico_realizado, 
            en.valor, 
            en.metodo_pagamento,
            en.garantia,
            eo.nome AS nome_cliente,
            eo.tipo AS tipo_dispositivo, 
            eo.modelo,
            eo.id AS id_entrada
        FROM exit_notes AS en
        JOIN entryorder AS eo ON en.entry_id = eo.id
        ORDER BY en.id DESC";

$result = mysqli_query($conn, $sql);

if ($result && mysqli_num_rows($result) > 0) {
    echo "<div class='container'>";
    while ($row = mysqli_fetch_assoc($result)) {
        // Formatação dos valores importantes
        $valor_formatado = "R$ " . number_format($row['valor'], 2, ',', '.');
        $data_saida_formatada = date('d/m/Y', strtotime($row['data_saida']));
        $data_garantia_formatada = date('d/m/Y', strtotime($row['garantia']));
        
        echo "<div class='box'>";
        echo "<ul>";
        
        // Exibição de informações destacadas e organizadas
        echo "<li><span class='highlight'>OS Saída Nº {$row['id_saida']} (Ref. Entrada Nº {$row['id_entrada']})</span></li>";
        echo "<li class='info-field'><strong>Cliente:</strong> " . htmlspecialchars($row['nome_cliente']) . "</li>";
        echo "<li class='info-field'><strong>Dispositivo:</strong> " . htmlspecialchars($row['tipo_dispositivo']) . " - " . htmlspecialchars($row['modelo']) . "</li>";
        echo "<li class='info-field'><strong>Serviço:</strong> " . nl2br(htmlspecialchars($row['servico_realizado'])) . "</li>";
        echo "<li class='info-field'><strong>Valor:</strong> <span style='color: green; font-weight: bold;'>{$valor_formatado}</span></li>";
        echo "<li class='info-field'><strong>Pagamento:</strong> " . htmlspecialchars($row['metodo_pagamento']) . "</li>";
        echo "<li class='info-field'><strong>Data Saída:</strong> {$data_saida_formatada}</li>";
        echo "<li class='info-field'><strong>Garantia Válida até:</strong> <span style='color: red;'>{$data_garantia_formatada}</span></li>";
        
        // Link para impressão
        echo "<li><a href='print_exitorder.php?id={$row['id_saida']}' target='_blank' class='action-link'>Imprimir Comprovante</a></li>";
        echo "</ul>";
        echo "</div>";
    }
    echo "</div>";
} else {
    echo "<p style='text-align:center; margin-top:20px;'>Nenhum resultado encontrado na tabela de notas de saída.</p>";
}

if ($result) {
    mysqli_free_result($result);
}
mysqli_close($conn);
?>
</body>
</html>
