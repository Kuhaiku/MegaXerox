<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="../css/printorder.css" />
<link rel="icon" type="image/x-icon" href="../src/favicon.ico" />
<title>Ordem de Saída</title>
</head>
<body>

<?php
include '../php/databaseconfig.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $sql = "SELECT id FROM sistemaloja.entryorder WHERE id = $id";
    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
?>

<div class='ordemdesaida'>
    <fieldset>
        <legend><b>Ordem de Saída <span id='numberField'> Nº <?= $row['id'] ?></span></b></legend>

        <div class='data'>
            <p><b>Data de Saída:</b> <input type='date' id='dataSaida' required></p>
        </div>           

        <div class='inputBox'>
            <label for='servico_realizado'><b>Serviço Realizado:</b></label>
            <textarea id='servico_realizado' required></textarea>
        </div>

        <p><b>Método de Pagamento:</b> 
            <select id='metodoPagamento' required>
                <option value='Dinheiro'>Dinheiro</option>
                <option value='Cartão de Crédito'>Cartão de Crédito</option>
                <option value='Cartão de Débito'>Cartão de Débito</option>
                <option value='Pix'>Pix</option>
            </select>
        </p>

        <p><b>Valor do Serviço:</b> R$ <input type='number' id='valorServico' required></p>
        <p><b>Tempo de Garantia:</b> <span id='garantia'></span></p>

        <div class='botoes'>
            <a href='../index.html'>Voltar</a>
            <button type='button' onclick='gerarOrdemSaida(<?= $id ?>)'>Gerar Ordem de Saída</button>
        </div>
    </fieldset>
</div>

<?php
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
document.getElementById('dataSaida').addEventListener('change', function() {
    const dataSaida = new Date(this.value);
    if (!isNaN(dataSaida.getTime())) {
        dataSaida.setMonth(dataSaida.getMonth() + 3);
        const dataGarantia = dataSaida.toISOString().split('T')[0].split('-').reverse().join('/');
        document.getElementById('garantia').innerText = dataGarantia;
    }
});

function gerarOrdemSaida(entryId) {
    const servicoRealizado = document.getElementById('servico_realizado').value;
    const dataSaida = document.getElementById('dataSaida').value;
    const metodoPagamento = document.getElementById('metodoPagamento').value;
    const valorServico = document.getElementById('valorServico').value;
    const garantia = document.getElementById('garantia').innerText;

    if (!servicoRealizado || !dataSaida || !metodoPagamento || !valorServico) {
        alert("Preencha todos os campos antes de gerar a ordem de saída.");
        return;
    }

    const formData = new FormData();
    formData.append("entry_id", entryId);
    formData.append("data_saida", dataSaida);
    formData.append("servico_realizado", servicoRealizado);
    formData.append("metodo_pagamento", metodoPagamento);
    formData.append("valor", valorServico);
    formData.append("garantia", garantia.split('/').reverse().join('-'));

    fetch("../php/salvar_ordem_saida.php", {
        method: "POST",
        body: formData
    })
    .then(response => response.text())
    .then(data => {
        alert(data);
        window.location.href = "../index.html";
    })
    .catch(error => console.error("Erro:", error));
}
</script>

</body>
</html>
