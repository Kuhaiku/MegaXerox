<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="icon" type="image/x-icon" href="../src/favicon.ico" />
<link rel="stylesheet" href="../css/entryorderdtbase.css" />
<title>Ordens de Saída</title>
</head>
<body>
<?php
include '../php/databaseconfig.php';

echo "<header>
      <h1>Ordem de Saida</h1>
      <a href='../index.html'><h3>inicio</h3></a>
    </header>";

// CORREÇÃO: Usando a tabela correta 'exit_notes' que você indicou.
$sql = "SELECT * FROM sistemaloja.exit_notes ORDER BY id DESC";
$result = mysqli_query($conn, $sql);

if ($result && mysqli_num_rows($result) > 0) {
    echo "<div class='container'>";
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<div class='box'><ul>";
        foreach ($row as $campo => $valor) {
            echo "<li><strong>" . htmlspecialchars($campo) . ":</strong> " . htmlspecialchars($valor) . "</li>";
        }
        // O link para impressão aponta para o ID correto da nota de saída
        echo "<li><a href='print_exitorder.php?id={$row['id']}' target='_blank' style='display:block; background-color:#007bff; color:white; padding:10px; text-align:center; margin-top:10px; border-radius:5px;'>Imprimir</a></li>";
        echo "</ul></div>";
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
