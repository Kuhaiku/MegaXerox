<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="icon" type="image/x-icon" href="../src/favicon.ico" />
<title>Sistema Mega-Xerox - Ordem de Saída</title>
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
                        <br>
                        <br>
                        <p><b>Data de Entrega:</b> <input type='date' name='data_saida' id='dataEntrega' required></p>
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

                    <p><b>Garantia de Serviço Valida até:</b> 
                        <input type='text' name='garantia' id='garantia' readonly>
                    </p>

                    <div class='botoes'>
                        <button type='button' id='gerarOrdem'>Gerar Ordem de Saída</button>
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
document.getElementById('dataEntrega').value = new Date().toISOString().split('T')[0];

function calcularGarantia() {
    const dataEntregaInput = document.getElementById('dataEntrega');
    const garantiaInput = document.getElementById('garantia');

    if (dataEntregaInput.value) {
        const dataEntrega = new Date(dataEntregaInput.value);
        if (!isNaN(dataEntrega.getTime())) {
            dataEntrega.setMonth(dataEntrega.getMonth() + 3);
            const dataGarantia = dataEntrega.toISOString().split('T')[0].split('-').reverse().join('/');
            garantiaInput.value = dataGarantia;
        } else {
            garantiaInput.value = "Data inválida";
        }
    } else {
        garantiaInput.value = "Selecione uma data de entrega";
    }
}

// Calcula a garantia ao carregar a página
document.addEventListener('DOMContentLoaded', calcularGarantia);

// Atualiza a garantia quando a data de entrega mudar
document.getElementById('dataEntrega').addEventListener('change', calcularGarantia);

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
