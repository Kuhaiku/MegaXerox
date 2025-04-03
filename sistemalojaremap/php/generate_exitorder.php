<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema Mega-Xerox - Ordem de Saída</title>
    <link rel="stylesheet" href="../css/printorder.css">
    <link rel="icon" type="image/x-icon" href="../src/favicon.ico">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            color: #333;
            margin: 0;
            padding: 20px;
        }
        .ordemdesaida {
            max-width: 600px;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
            margin: auto;
        }
        header {
            text-align: center;
            border-bottom: 2px solid #ddd;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        header h2 {
            margin: 0;
            color: #d35400;
        }
        fieldset {
            border: none;
            padding: 0;
        }
        legend {
            font-size: 1.2em;
            font-weight: bold;
            color: #d35400;
        }
        p, label {
            margin: 10px 0;
        }
        textarea, select, input {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .botoes {
            text-align: center;
            margin-top: 20px;
        }
        button {
            background-color: #d35400;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1em;
            margin: auto;
        }
        button:hover {
            background-color: #b03a00;
        }
    </style>
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
?>

<div class="ordemdesaida">
    <header> 
        <h2>Mega Xerox</h2> 
        <p>CNPJ: 12.689.177/0001-26</p>
        <p>WhatsApp: 2665-5910</p>
        <h4>2ª via</h4>
        <p>Ass.____________________________________</p>
    </header>

    <fieldset>
        <legend>Ordem de Saída Nº <?= $row['id'] ?></legend>

        <form id="saidaForm">
            <input type="hidden" name="entry_id" value="<?= $row['id'] ?>">

            <p><b>Nome:</b> <?= $row['nome'] ?></p>
            <p><b>Telefone:</b> <?= $row['telefone'] ?></p>
            <p><b>Data Entrada:</b> <?= $row['data_entrada'] ?></p>
            <p><b>Defeito Apresentado:</b> <?= $row['defeito'] ?></p>


            
            <label for="dataEntrega"><b>Data de Entrega:</b></label>
            <input type="date" name="data_saida" id="dataEntrega" required>

            <label for="servico_realizado"><b>Serviço Realizado:</b></label>
            <textarea name="servico_realizado" id="servico_realizado" required></textarea>

            <label for="metodoPagamento"><b>Método de Pagamento:</b></label>
            <select name="metodo_pagamento" id="metodoPagamento" required>
                <option value="Dinheiro">Dinheiro</option>
                <option value="Cartão de Crédito">Cartão de Crédito</option>
                <option value="Cartão de Débito">Cartão de Débito</option>
                <option value="Pix">Pix</option>
            </select>

            <label for="valorServico"><b>Valor do Serviço:</b> R$</label>
            <input type="number" name="valor" id="valorServico" step="0.01" required>

            <p><b>Garantia de Serviço Valida até:</b> <span id="garantia"></span></p>

            <div class="botoes">
                <button type="button" id="gerarOrdem">Gerar Ordem de Saída</button>
            </div>
        </form>
    </fieldset>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const dataEntrega = document.getElementById('dataEntrega');
    const garantiaSpan = document.getElementById('garantia');

    function calcularGarantia() {
        if (dataEntrega.value) {
            let data = new Date(dataEntrega.value);
            data.setMonth(data.getMonth() + 3);
            garantiaSpan.innerText = data.toLocaleDateString('pt-BR');
        } else {
            garantiaSpan.innerText = "Selecione uma data de entrega";
        }
    }

    dataEntrega.addEventListener('change', calcularGarantia);
    dataEntrega.value = new Date().toISOString().split('T')[0];
    calcularGarantia();

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
});
</script>

<?php
        } else {
            echo "<p style='text-align:center; color:red;'>Registro não encontrado.</p>";
        }
        mysqli_stmt_close($stmt);
    }
} else {
    echo "<p style='text-align:center; color:red;'>ID não fornecido.</p>";
}

mysqli_close($conn);
?>

</body>
</html>
