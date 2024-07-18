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
                    <fieldset>
                        <legend><b>Ordem de Entrada <span id='numberField'> Nº {$row['id']}</span></b></legend>
                    
                        <div class='inputBox'>
                        <p>Nome: {$row['nome']}</p>
                        <p>Telefone: {$row['telefone']}</p>
                        <p>Data de entrada: {$row['data_entrada']}</p>
                        <p>Tipo de Dispositivo: {$row['tipo']}</p>
                        <p>Marca do Dispositivo: {$row['marca']}</p>
                        <p>Modelo do Dispositivo: {$row['modelo']}</p>
                        <p>Periféricos: {$row['perifericos']}</p>
                        <p>Defeito Apresentado: {$row['defeito']}</p>
                        <p>Orçamento Prévio:{$row['preorc']}</p> 
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

<!-- 
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
                <legend>
                    <b>
                        Ordem de Entrada
                        <span id="numberField"> Nº <?php echo $next_id; ?></span>
                    </b>
                </legend>
                <div class="inputBox">
                    <input class="inputUser" type="text" name="nome" id="nome" required />
                    <label class="labelInupt" for="nome">Nome:</label>
                </div>
                <div class="inputBox">
                    <input class="inputUser" type="text" name="telefone" id="telefone" required />
                    <label class="labelInupt" for="telefone">Telefone:</label>
                    
                </div>
                <div class="data">
                    <!-- <input type="hidden" id="dataAtual" name="dataAtual" value="" /> -->
                    <b>Data de Entrada:</b> <span id="dataAtual"> </span><br />
                    <b>Data de previsão para o orçamento:</b>
                    <span id="dataPrevisao"></span>
                </div>
                <div class="tipodispositivo">
                    <b>Tipo de Dispositivo:</b>
                    <input type="radio" name="tipo" id="impressora" value="impressora" />
                    Impressora
                    <input type="radio" name="tipo" id="notebook" value="notebook" />
                    Notebook
                    <input type="radio" name="tipo" id="desktop" value="desktop" />
                    Desktop
                    <input type="radio" name="tipo" id="celular" value="celular" />
                    Celular
                    <input type="radio" name="tipo" id="outro" value="outro" />
                    Outro
                </div>
                <div class="inputBox">
                    <input class="inputUser" type="text" name="marca" id="marca" required />
                    <label class="labelInupt" for="marca">Marca:</label>
                </div>
                <div class="inputBox">
                    <input class="inputUser" type="text" name="modelo" id="modelo" required />
                    <label class="labelInupt" for="modelo">Modelo:</label>
                </div>
                <div class="inputBox">
                    <input class="inputUser" type="text" name="perifericos" value="Não" id="perifericos" required />
                    <label class="labelInupt" for="perifericos">Periféricos</label>
                </div>
                <div class="inputBox">
                    <label for="defeito">Defeito Apresentado</label>
                    <textarea oninput="ajustarAltura(this)" class="inputUser" name="defeito" id="defeito" cols="50" rows="3"></textarea>
                </div>
                <div class="inputBox">
                    <input class="inputUser" type="text" name="preorc" value="Não" id="preorc" required />
                    <label class="labelInupt" for="preorc">Orçamento Prévio:</label>
                </div>
                <div class="termos">
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
                
                <div class="botoes">
                    <a class="navegar" href="../index.html">Voltar</a>
                    <button type="submit" id="submit" name="submit" value="Salvar e Imprimir" onclick="print()">
                        <i class="fas fa-print"></i> Salvar e Imprimir 
                    </button>
                </div>
            </fieldset>
        </form>
    </div> -->