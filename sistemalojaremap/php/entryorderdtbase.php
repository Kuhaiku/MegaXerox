<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="icon" type="image/x-icon" href="../src/favicon.ico" />
<link rel="stylesheet" href="../css/entryorderdtbase.css" />
<title>Ordens de Serviços Cadastradas</title>
<style>
    .search-box {
        margin: 10px auto;
        text-align: center;
    }
    .search-box input {
        width: 80%;
        padding: 8px;
        font-size: 16px;
    }
</style>
</head>
<body>

<?php
include '../php/databaseconfig.php';

echo "<header>
      <h1>2ª via Ordem de entrada</h1>
      <a href='../index.html'><h3>Inicio</h3></a>
    </header>";

// Campo de pesquisa
echo "<div class='search-box'>
        <input type='text' id='search' placeholder='Pesquisar por Nome, Modelo, Data ou ID...'>
      </div>";

$sql = "SELECT * FROM sistemaloja.entryorder";
$result = mysqli_query($conn, $sql);
if (mysqli_num_rows($result) > 0) {
    echo "<div class='container' id='entries'>";
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<div class='box'>";
        echo "<ul class='entry'>";
        foreach ($row as $campo => $valor) {
            echo "<li><strong>{$campo}:</strong> <span class='searchable'>{$valor}</span></li>";
        }
        echo "<li><a href='print_entryorder.php?id={$row['id']}' target='_blank'>Imprimir</a></li>";
        echo "</ul>";
        echo "</div>";
    }
    echo "</div>";
} else {
    echo "Nenhum resultado encontrado.";
}

mysqli_free_result($result);
mysqli_close($conn);
?>

<script>
document.getElementById('search').addEventListener('input', function() {
    let searchValue = this.value.toLowerCase();
    let entries = document.querySelectorAll('.entry');

    entries.forEach(entry => {
        let text = entry.innerText.toLowerCase();
        if (text.includes(searchValue)) {
            entry.parentElement.style.display = 'block';
        } else {
            entry.parentElement.style.display = 'none';
        }
    });
});
</script>

</body>
</html>
