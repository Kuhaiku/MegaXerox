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
    .generate-btn {
        display: block;
        margin-top: 10px;
        padding: 8px;
        background-color: #F54927;
        color: white;
        text-align: center;
        border: none;
        cursor: pointer;
        width: 100%;
    }
    .generate-btn:hover {
        background-color: #FA2800;
    }
    
    .generate-btn2 {
        display: block;
        margin-top: 10px;
        padding: 8px;
        background-color: #28a745;
        color: white;
        text-align: center;
        border: none;
        cursor: pointer;
        width: 100%;
    }
    .generate-btn2:hover {
        background-color: #218838;
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
            // Adicionando uma classe específica para o campo 'tipo' para facilitar a busca no JS
            $class = ($campo === 'tipo') ? 'tipo-dispositivo' : '';
            echo "<li><strong>{$campo}:</strong> <span class='searchable {$class}'>{$valor}</span></li>";
        }
        echo "<li><a href='print_entryorder.php?id={$row['id']}' target='_blank' class='generate-btn2' >Imprimir</a></li>";
        
        // Botão para gerar ordem de saída
        $queryString = http_build_query($row); // Transforma os dados em parâmetros de URL
        echo "<li><a href='generate_exitorder.php?$queryString' class='generate-btn'>Gerar Ordem de Saída</a></li>";
        
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
// Filtro da barra de pesquisa
document.getElementById('search').addEventListener('input', function() {
    let searchValue = this.value.toLowerCase();
    let entries = document.querySelectorAll('.entry');

    entries.forEach(entry => {
        let text = entry.innerText.toLowerCase();
        let tipoElement = entry.querySelector('.tipo-dispositivo');
        let tipo = tipoElement ? tipoElement.innerText.toUpperCase() : '';

        // Mostra o item se o texto corresponder à pesquisa E o tipo não for CONSOLE ou CONTROLE
        if (text.includes(searchValue) && tipo !== 'CONSOLE' && tipo !== 'CONTROLE') {
            entry.parentElement.style.display = 'block';
        } else {
            entry.parentElement.style.display = 'none';
        }
    });
});

// Filtro para ocultar CONSOLE e CONTROLE ao carregar a página
document.addEventListener('DOMContentLoaded', function() {
    let entries = document.querySelectorAll('.entry');

    entries.forEach(entry => {
        let tipoElement = entry.querySelector('.tipo-dispositivo');
        if (tipoElement) {
            let tipo = tipoElement.innerText.toUpperCase();
            if (tipo === 'CONSOLE' || tipo === 'CONTROLE') {
                entry.parentElement.style.display = 'none';
            }
        }
    });
});
</script>

</body>
</html>
