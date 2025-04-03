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
    $sql = "SELECT * FROM sistemaloja.entryorder WHERE id = $id";
    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        
        $row = mysqli_fetch_assoc($result);
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
                    <b>
                        Ordem de Saída
                        <span id='numberField'> Nº {$row['id']}</span>
                    </b>
        </legend>
        <div class='inputBox'>
                    <p><b>Nome:</b> {$row['nome']}</p>
                </div>
                <div class='inputBox'>
                    <p><b>Telefone:</b> {$row['telefone']}</p>
                </div>

                <div class='data'>
                    <p><b>Data de Entrada:</b> <span id='dataFormatada'></span></p>
                    <p><b>Data de Entrega:</b> <input type='date' id='dataEntrega' required></p>
                </div>           
                        <p><b>Tipo de Dispositivo:</b> {$row['tipo']}</p>
                        <p><b>Marca do Dispositivo:</b> {$row['marca']}</p>
                        <p><b>Modelo do Dispositivo:</b> {$row['modelo']}</p>
                        <p><b>Periféricos:</b> {$row['perifericos']}</p>
                        
                        <div class='inputBox'>
                            <label for='defeito'><b>Defeito Apresentado:</b></label>
                            <p class='defeito'>{$row['defeito']}</p>
                        </div>

                        <div class='inputBox'>
                            <label for='servico_realizado'><b>Serviço Realizado:</b></label>
                            <textarea id='servico_realizado' required></textarea>
                        </div>

                        <p><b>Orçamento Prévio:</b> {$row['preorc']}</p> 
                        <p><b>Valor do Serviço:</b> R$ <input type='number' id='valorServico' required></p>
                        <p><b>Método de Pagamento:</b> 
                            <select id='metodoPagamento' required>
                                <option value='Dinheiro'>Dinheiro</option>
                                <option value='Cartão de Crédito'>Cartão de Crédito</option>
                                <option value='Cartão de Débito'>Cartão de Débito</option>
                                <option value='Pix'>Pix</option>
                            </select>
                        </p>
                        <p><b>Tempo de Garantia:</b> <span id='garantia'></span></p>

                        <div class='termos'>
                            <b>Termos de Saída:</b>
                            <ol>
                                <li><b>Garantia:</b> A garantia cobre apenas os serviços realizados e tem duração de 3 meses.</li>
                                <li><b>Responsabilidade do Cliente:</b> Após a retirada, a loja não se responsabiliza por problemas não relacionados ao serviço realizado.</li>
                                <li><b>Pagamentos:</b> O pagamento deve ser realizado no ato da retirada do dispositivo.</li>
                            </ol>
                        </div>                       

                        <div class='botoes'>
                            <a href='../index.html'>Voltar</a>
                            <button type='button' onclick='gerarOrdemSaida()'>Gerar Ordem de Saída</button>
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
function formatarDataBR(data) {
    const partesData = data.split(' ')[0].split('-');
    return `${partesData[2]}/${partesData[1]}/${partesData[0]}`;
}

// Formatar a data de entrada corretamente
const dataAtual = document.getElementById('dataAtual')?.innerText;
if (dataAtual) {
    document.getElementById('dataFormatada').innerText = formatarDataBR(dataAtual);
}

// Atualizar automaticamente o tempo de garantia
document.getElementById('dataEntrega').addEventListener('change', function() {
    const dataEntrega = new Date(this.value);
    if (!isNaN(dataEntrega.getTime())) {
        dataEntrega.setMonth(dataEntrega.getMonth() + 3);
        const dataGarantia = dataEntrega.toISOString().split('T')[0].split('-').reverse().join('/');
        document.getElementById('garantia').innerText = dataGarantia;
    }
});

function gerarOrdemSaida() {
    const servicoRealizado = document.getElementById('servico_realizado').value;
    const dataEntrega = document.getElementById('dataEntrega').value;
    const metodoPagamento = document.getElementById('metodoPagamento').value;
    const valorServico = document.getElementById('valorServico').value;
    const garantia = document.getElementById('garantia').innerText;

    if (!servicoRealizado || !dataEntrega || !metodoPagamento || !valorServico) {
        alert("Preencha todos os campos antes de gerar a ordem de saída.");
        return;
    }

    alert("Ordem de saída gerada com sucesso!");
}
</script>
</body>
</html>
