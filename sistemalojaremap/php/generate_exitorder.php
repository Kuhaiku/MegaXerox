<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="../css/printorder.css" />
<link rel="icon" type="image/x-icon" href="../src/favicon.ico" />

<title>Sistema Mega-Xerox - Ordem de Saída</title>
</head>
<body>
<?php
include '../php/databaseconfig.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $sql = "SELECT * FROM sistemaloja.entryorder WHERE id = ?";
    
    if ($stmt = mysqli_prepare($conn, $sql)) {
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($row = mysqli_fetch_assoc($result)) {
            echo "<div class='ordemdesaida'>
                <header> 
                    <h2>Mega Xerox</h2> 
                    <p>CNPJ: 12.689.177/0001-26</p>
                    <p>WhatsApp: 2665-5910</p>
                    <h4>2ª via</h4>
                    <br><br>
                    <p>Ass.____________________________________</p>
                </header>
                <fieldset>
                <legend>
                    <b>Ordem de Saída <span id='numberField'> Nº {$row['id']}</span></b>
                </legend>

                <form id='saidaForm'>
                    <input type='hidden' name='entry_id' value='{$row['id']}'>

                    <p><b>Nome:</b> {$row['nome']}</p>
                    <p><b>Telefone:</b> {$row['telefone']}</p>

                    <div class='data'>
                        <p><b>Data de Entrada:</b> <span id='dataFormatada'>{$row['data_entrada']}</span></p>
                        <input type="date" name="data_saida" id="dataEntrega" required value="<?php echo date('Y-m-d'); ?>">
                    </div>           

                    <p><b>Serviço Realizado:</b> <textarea name='servico_realizado' id='servico_realizado' required></textarea></p>

                    <p><b>Método de Pagamento:</b> 
                        <select name='metodo_pagamento' id='metodoPagamento' required>
                            <option value='Dinheiro'>Dinheiro</option>
                            <option value='Cartão de Crédito'>Cartão de Crédito</option>
                            <option value='Cartão de Débito'>Cartão de Débito</option>
                            <option value='Pix'>Pix</option>
                        </select>
                    </p>

                    <p><b>Valor do Serviço:</b> R$ <input type='number' name='valor' id='valorServico' step='0.01' required></p>
                    <p><b>Tempo de Garantia:</b> <span id='garantia'></span></p>

                    <div class='botoes'>
                        <a href='../index.html'>Voltar</a>
                        <button type='button' id='gerarOrdem'>Gerar Ordem de Saída</button>
                        <button type='button' onclick='window.print()'>Imprimir</button>
                    </div>
                </form>
                </fieldset>
            </div>";

        } else {
            echo "Registro não encontrado.";
        }
        
        mysqli_stmt_close($stmt);
    }
} else {
    echo "ID não fornecido.";
}

mysqli_close($conn);
?>

<script>
function formatarDataBR(data) {
    const partesData = data.split(' ')[0].split('-');
    return `${partesData[2]}/${partesData[1]}/${partesData[0]}`;
}

document.getElementById('dataEntrega').addEventListener('change', function() {
    const dataEntrega = new Date(this.value);
    if (!isNaN(dataEntrega.getTime())) {
        dataEntrega.setMonth(dataEntrega.getMonth() + 3);
        const dataGarantia = dataEntrega.toISOString().split('T')[0].split('-').reverse().join('/');
        document.getElementById('garantia').innerText = dataGarantia;
    }
});

document.getElementById('gerarOrdem').addEventListener('click', function() {
    const formData = new FormData(document.getElementById('saidaForm'));

    fetch('../php/salvar_ordem_saida.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.text())
    .then(data => {
        alert(data);
        if (data.includes("sucesso")) {
            window.print();
        }
    })
    .catch(error => console.error('Erro:', error));
});
</script>
</body>
</html>
