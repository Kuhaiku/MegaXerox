<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="icon" type="image/x-icon" href="../src/favicon.ico" />
<link rel="stylesheet" href="../css/entryorderdtbase.css" />
<title>Ordens de Serviços Cadastradas</title>
 
</head>
<body>
<?php
include '../php/databaseconfig.php';

echo "<header>
      <h1>2ª via Ordem de entrada</h1>
      <a href='../index.html'><h3>inicio</h3></a>
    </header>";

$sql = "SELECT * FROM sistemaloja.entryorder";
$result = mysqli_query($conn, $sql);
if (mysqli_num_rows($result) > 0) {
    echo "<div class='container'>";
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<div class='box'><ul>";
        foreach ($row as $campo => $valor) {
            echo "<li><strong>{$campo}:</strong> {$valor}</li>";
        }
        echo "<li><a href='print_entryorder.php?id={$row['id']}' target='_blank'>Imprimir</a></li>";
        echo "</ul></div>";
    }
    echo "</div>";
} else {
    echo "Nenhum resultado encontrado.";
}

mysqli_free_result($result);
mysqli_close($conn);
?>
</body>
</html>
