<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="../css/printorder.css" />
<link rel="icon" type="image/x-icon" href="../src/favicon.ico" />
<title>Sistema Mega-Xerox - Ordem de Entrada</title>
</head>
<body>
<?php
include '../php/databaseconfig.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $sql = "SELECT * FROM sistemaloja.exitorder WHERE id = $id";
    $sqll = "SELECT * FROM sistemaloja.entryorder WHERE id = $id";
    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        echo "<div class='ordemdesaida'>
                    <fieldset>
                        <legend><b>Ordem de Saida <span id='numberField'> Nº {$row['id']}</span></b></legend>
                    
                        <div class='inputBox'>
                        <p>Nome: {$row['nome']}</p>
                        <p>Telefone: {$row['telefone']}</p>
                        <p>Tipo de Dispositivo: {$row['tipo']}</p>
                        <p>Marca do Dispositivo: {$row['marca']}</p>
                        <p>Modelo do Dispositivo: {$row['modelo']}</p>
                        <p>Periféricos: {$row['perifericos']}</p>
                        <p>Defeito Apresentado: {$row['defeito']}</p>
                        <p>Defeito Apresentado: {$row['servico_realizado']}</p>
                        <p>Valor Conserto: R$ {$row['valor_conserto']}</p> 
                        <p>Data de Saida: {$row['data_saida']}</p>
                        </div>
                        
                        <div class='botoes'>
                        <a href='../index.html'>Voltar</a>
                            <button type='button' onclick='print()'>Imprimir</button>
                        </div>
                    </fieldset>
              </div>";
    } else {
        echo "Registro não encontrado.";
    }

    mysqli_free_result($result);
} else {
    echo "ID não fornecido.";
}

mysqli_close($conn);
?>
<script>
function ajustarAltura(elemento) {
    elemento.style.height = "auto"; 
    elemento.style.height = (elemento.scrollHeight) + "px";
}
</script>
</body>
</html>
