<?php
include_once('databaseconfig.php');
$sql = "SELECT MAX(id) as max_id FROM entryorder";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    // Saída dos dados da linha
    $row = $result->fetch_assoc();
    $next_id = $row["max_id"] + 1;
} else {
    $next_id = 1; // Se não houver registros, começa com 1
}

if (isset($_POST['submit'])) {
    // Campos comuns
    $nome = isset($_POST['nome']) ? $_POST['nome'] : '';
    $telefone = isset($_POST['telefone']) ? $_POST['telefone'] : '';
    $preorcamento = isset($_POST['preorc']) ? $_POST['preorc'] : '';

    // Processando cada dispositivo
    if (!empty($_POST['tipo'])) {
        foreach ($_POST['tipo'] as $key => $tipo) {
            $marca = $_POST['marca'][$key] ?? '';
            $modelo = $_POST['modelo'][$key] ?? '';
            $perifericos = $_POST['perifericos'][$key] ?? 'Não';
            $defeito = $_POST['defeito'][$key] ?? '';

            $result = mysqli_query($conn, "INSERT INTO entryorder(`nome`, `telefone`, `tipo`, `marca`, `modelo`, `perifericos`, `defeito`, `preorc`) 
            VALUES ('$nome','$telefone','$tipo','$marca','$modelo','$perifericos','$defeito','$preorcamento')");

            if (!$result) {
                $error_message = mysqli_error($conn);
                header("Location: entryorder.php?error=" . urlencode($error_message));
                exit();
            }
        }
        header("Location: entryorderdtbase.php?success=1");
        exit();
    }
}
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
                
                <!-- Dispositivos -->
                <div id="dispositivos">
                    <div class="dispositivo">
                        <div class="tipodispositivo">
                            <b>Tipo de Dispositivo:</b>
                            <input type="radio" name="tipo[0]" value="IMPRESSORA" required /> Impressora
                            <input type="radio" name="tipo[0]" value="NOTEBOOK" /> Notebook
                            <input type="radio" name="tipo[0]" value="DESKTOP" /> Desktop
                            <input type="radio" name="tipo[0]" value="CONTROLE" /> Controle
                            <input type="radio" name="tipo[0]" value="CONSOLE" /> Console
                            <input type="radio" name="tipo[0]" value="CELULAR" /> Celular
                            <input type="radio" name="tipo[0]" value="OUTRO" /> Outro
                        </div>
                        <div class="inputBox">
                            <input class="inputUser" type="text" name="marca[0]" required oninput="this.value = this.value.toUpperCase()" />
                            <label class="labelInupt" for="marca">Marca:</label>
                        </div>
                        <div class="inputBox">
                            <input class="inputUser" type="text" name="modelo[0]" required oninput="this.value = this.value.toUpperCase()" />
                            <label class="labelInupt" for="modelo">Modelo:</label>
                        </div>
                        <div class="inputBox">
                            <input class="inputUser" type="text" name="perifericos[0]" value="Não" />
                            <label class="labelInupt" for="perifericos">Periféricos</label>
                        </div>
                        <div class="inputBox">
                            <label for="defeito">Defeito Apresentado:</label>
                            <textarea class="inputUser" name="defeito[0]" cols="50" rows="3"></textarea>
                        </div>
                    </div>
                </div>

                <!-- Botão para adicionar mais dispositivos -->
                <button type="button" onclick="adicionarDispositivo()">Adicionar Dispositivo</button>

                <div class="inputBox">
                    <input class="inputUser" type="text" name="preorc" value="Não" required />
                    <label class="labelInupt" for="preorc">Orçamento Prévio:</label>
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
    // Função para adicionar mais dispositivos
    let dispositivoCount = 1; // Começa com 1 dispositivo

    function adicionarDispositivo() {
        const dispositivosDiv = document.getElementById('dispositivos');
        const novoDispositivo = document.createElement('div');
        novoDispositivo.classList.add('dispositivo');
        
        novoDispositivo.innerHTML = `
            <div class="tipodispositivo">
                <b>Tipo de Dispositivo:</b>
                <input type="radio" name="tipo[${dispositivoCount}]" value="IMPRESSORA" required /> Impressora
                <input type="radio" name="tipo[${dispositivoCount}]" value="NOTEBOOK" /> Notebook
                <input type="radio" name="tipo[${dispositivoCount}]" value="DESKTOP" /> Desktop
                <input type="radio" name="tipo[${dispositivoCount}]" value="CONTROLE" /> Controle
                <input type="radio" name="tipo[${dispositivoCount}]" value="CONSOLE" /> Console
                <input type="radio" name="tipo[${dispositivoCount}]" value="CELULAR" /> Celular
                <input type="radio" name="tipo[${dispositivoCount}]" value="OUTRO" /> Outro
            </div>
            <div class="inputBox">
                <input class="inputUser" type="text" name="marca[${dispositivoCount}]" required oninput="this.value = this.value.toUpperCase()" />
                <label class="labelInupt" for="marca">Marca:</label>
            </div>
            <div class="inputBox">
                <input class="inputUser" type="text" name="modelo[${dispositivoCount}]" required oninput="this.value = this.value.toUpperCase()" />
                <label class="labelInupt" for="modelo">Modelo:</label>
            </div>
            <div class="inputBox">
                <input class="inputUser" type="text" name="perifericos[${dispositivoCount}]" value="Não" />
                <label class="labelInupt" for="perifericos">Periféricos</label>
            </div>
            <div class="inputBox">
                <label for="defeito">Defeito Apresentado:</label>
                <textarea class="inputUser" name="defeito[${dispositivoCount}]" cols="50" rows="3"></textarea>
            </div>
            <button type="button" class="removerDispositivo" onclick="removerDispositivo(this)">Remover</button>
        `;
        
        dispositivosDiv.appendChild(novoDispositivo);
        dispositivoCount++;
    }

    // Função para remover dispositivo
    function removerDispositivo(button) {
        const dispositivo = button.parentElement;
        dispositivo.remove();
    }

    const dataAtual = new Date();

    function formatarDataBr(data, incluirHora = true) {
        const options = { day: '2-digit', month: '2-digit', year: 'numeric' };

        if (incluirHora) {
            options.hour = '2-digit';
            options.minute = '2-digit';
        }

        return new Intl.DateTimeFormat('pt-BR', options).format(data);
    }

    function calcularDataPrevisao() {
        for (let i = 0; i < 3;) {
            dataAtual.setDate(dataAtual.getDate() + 1);
            if (dataAtual.getDay() >= 1 && dataAtual.getDay() <= 5) {
                i++;
            }
        }

        document.getElementById('dataAtual').innerText = formatarDataBr(new Date());
        document.getElementById('dataPrevisao').innerText = formatarDataBr(dataAtual, false);
    }

    calcularDataPrevisao();
</script>

</body>
</html>
