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
    $sql = "SELECT * FROM sistemaloja.entryorder WHERE id = $id";
    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        
        $row = mysqli_fetch_assoc($result);
        echo "<div class='ordemdeentrada'>
        <header> 
            <h2>Mega Xerox</h2> 
            <p>Cnpj: 12.689.177/0001-26</p>
            <p>Whatsapp: 2665-5910</p>
            <h4> 2ª via</h4>
            <br><br>
            <p>Ass.____________________________________</p>
        </header>
        <fieldset>
        <legend>
                    <b>
                        Ordem de Entrada
                        <span id='numberField'> Nº {$row['id']}</span>
                    </b>
        </legend>
        <div class='inputBox'>
                    <p>Nome: {$row['nome']}</p>
                </div>
                <div class='inputBox'>
                    <p> Telefone: {$row['telefone']}</p>
                </div>

                <div class='data'>
                    <b>Data de Entrada:</b> <span id='dataAtual' style='display:none;'> {$row['data_entrada']} </span><br />    
                    <b>Data de Entrada:</b> <span id='dataFormatada'></span><br />
                </div>           
                        <p>Tipo de Dispositivo: {$row['tipo']}</p>
                        <p>Marca do Dispositivo: {$row['marca']}</p>
                        <p>Modelo do Dispositivo: {$row['modelo']}</p>
                        <p>Periféricos: {$row['perifericos']}</p>
                        
                        <div class='inputBox'>
                            <label for='defeito'>Defeito Apresentado</label>
                            <p class='defeito' >{$row['defeito']}</p>
                        </div>
                        <p>Orçamento Prévio:{$row['preorc']}</p> 
                </div>
<div class='termos'>
                    <b>Termos de entrada:</b>
                    <ol>
                        <li>
                            <b>Responsabilidade do Cliente:</b> O cliente é responsável por
                            fornecer informações precisas sobre o dispositivo e suas
                            condições atuais.
                        </li>
                        <li>
                            <b>Condições do Dispositivo:</b> O dispositivo deve estar em
                            condições adequadas para o serviço solicitado. Qualquer dano
                            pré-existente deve ser informado à loja antes do serviço.
                        </li>
                        <li>
                            <b>Autorização para Orçamento:</b> O cliente autoriza a loja a
                            realizar um orçamento para avaliação do serviço necessário. O
                            cliente será informado sobre quaisquer custos adicionais antes
                            do início do serviço, caso seja necessário.
                        </li>
                        <li>
                            <b>Após a conclusão do serviço ou, em caso de não autorização,
                                notificaremos imediatamente.</b
                            >
                            Pedimos que retire seu dispositivo em até 10 dias úteis após
                            essa notificação. Caso não seja retirado dentro desse prazo,
                            será aplicada uma multa correspondente ao valor de armazenamento
                            do dispositivo.
                        </li>
                        <li>
                            <b>Autorização para serviço em caso de orçamento prévio:</b> O cliente autoriza a loja a
                            realizar os serviços necessários no dispositivo conforme
                            descrito no formulário de entrada.
                        </li>
                        <li>
                            <b>Comunicação:</b> A loja se compromete a manter o cliente
                            informado sobre o status do serviço e quaisquer problemas que
                            possam surgir durante o processo.
                        </li>
                    </ol>
                </div>                       
                        <div class='botoes'>
                        <a href='../index.html'>Voltar</a>
                            <button type='button' onclick='print()'>Imprimir</button>
                        </div>
                    </fieldset>
              </div>
              
              
                "
              ;
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
// data
     function formatarDataBR(data) {
        const partesData = data.split(' ')[0].split('-');
        return `${partesData[2]}/${partesData[1]}/${partesData[0]}`;
    }

    // Obtém a data do elemento original
    const dataAtual = document.getElementById('dataAtual').innerText;
    
    // Formata a data no padrão brasileiro
    const dataFormatada = formatarDataBR(dataAtual);
    
    // Exibe a data formatada no local desejado
    document.getElementById('dataFormatada').innerText = dataFormatada;
</script>
</body>
</html>
