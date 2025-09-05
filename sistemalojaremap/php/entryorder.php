<?php
include_once('databaseconfig.php');
// ... (resto do seu código PHP continua igual) ...
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $next_id = $row["max_id"] + 1;
} else {
    $next_id = 1;
}
// ... (resto do seu código PHP continua igual) ...
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="icon" type="image/x-icon" href="../src/favicon.ico" />
    <link rel="stylesheet" href="../css/entryorder.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    <title>Sistema Mega-Xerox - Ordem de Entrada</title>
    <style>
        /* Adicione este estilo para formatar os termos */
        .termos-servico {
            margin-top: 20px;
            padding: 10px;
            border: 1px solid #aaa;
            border-radius: 5px;
            background-color: #f5f5f5;
            font-size: 12px; /* Tamanho geral do texto dos termos */
        }
        .termos-servico p {
            margin: 0 0 5px 0;
            font-weight: bold;
        }
        .termos-servico ol {
            margin: 0;
            padding-left: 20px; /* Alinha a lista numerada */
        }
        .termos-servico li {
            margin-bottom: 5px; /* Espaço entre os itens da lista */
        }
    </style>
</head>
<body>
    <div class="ordemdeentrada">
        <header>
            <h2>Mega Xerox</h2>
            <p>Cnpj: 12.689.177/0001-26</p>
            <p>Whatsapp: 2665-5910</p>
            <br><br>
            <p>Ass.____________________________________</p>
        </header>

        <form method="post" action="" id="formulario">
            <fieldset>
                <legend><b>Ordem de Entrada <span id="numberField"> Nº <?php echo $next_id; ?></span></b></legend>
                <div class="inputBox">
                    <input class="inputUser" type="text" name="nome" id="nome" required oninput="this.value = this.value.toUpperCase()"/>
                    <label class="labelInupt" for="nome">Nome:</label>
                </div>
                <div class="inputBox">
                    <input class="inputUser" type="text" name="telefone" id="telefone" required />
                    <label class="labelInupt" for="telefone">Telefone:</label>
                </div>
                <div class="data">
                    <b>Data de Entrada:</b> <span id="dataAtual"></span><br />
                    <b>Data de previsão para o orçamento:</b> <span id="dataPrevisao"></span>
                </div>
                
                <div id="dispositivos">
                    <div class="dispositivo">
                        </div>
                </div>
                <button type="button" onclick="adicionarDispositivo()">Adicionar Dispositivo</button>
                
                <div class="termos-servico">
                    <p>TERMOS DE SERVIÇO E CONDIÇÕES DE DEPÓSITO</p>
                    <ol>
                        <li>Após <b>7 (sete) dias</b> da comunicação do orçamento (aprovado ou não), o equipamento deve ser retirado.</li>
                        <li>Não ocorrendo a retirada no prazo, será cobrada uma taxa diária de <b>R$ 10,00 (dez reais)</b> a título de armazenamento.</li>
                        <li>Após <b>30 (trinta) dias</b> de armazenamento sem a retirada, o silêncio do cliente será interpretado como <b>abandono do equipamento</b>. Neste caso, o bem poderá ser vendido ou descartado para ressarcir os custos de serviço e guarda.</li>
                        <li>Estes termos estão amparados pelo Código Civil (Arts. 643, 644 e 1.275) e pelo Código de Defesa do Consumidor. A assinatura desta ordem de serviço implica no aceite integral destas condições.</li>
                    </ol>
                </div>
                <div class="botoes">
                    <a class="navegar" href="../index.html">Voltar</a>
                    <button type="submit" onclick="print()" name="submit" value="Salvar e Imprimir">
                        <i class="fas fa-print"></i> Salvar e Imprimir
                    </button>
                </div>
            </fieldset>
        </form>
    </div>

    <script>
        // ... (seu script JS) ...
    </script>

</body>
</html>
