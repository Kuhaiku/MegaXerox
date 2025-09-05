<?php
include_once('databaseconfig.php');
$sql = "SELECT MAX(id) as max_id FROM entryorder";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $next_id = $row["max_id"] + 1;
} else {
    $next_id = 1;
}

if (isset($_POST['submit'])) {
    // A lógica PHP para salvar no banco de dados permanece a mesma
    $nome = isset($_POST['nome']) ? $_POST['nome'] : '';
    $telefone = isset($_POST['telefone']) ? $_POST['telefone'] : '';
    $preorcamento = isset($_POST['preorc']) ? $_POST['preorc'] : '';

    if (!empty($_POST['tipo'])) {
        foreach ($_POST['tipo'] as $key => $tipo) {
            $marca = $_POST['marca'][$key] ?? '';
            $modelo = $_POST['modelo'][$key] ?? '';
            $perifericos = $_POST['perifericos'][$key] ?? 'NÃO';
            $defeito = $_POST['defeito'][$key] ?? '';
            $result = mysqli_query($conn, "INSERT INTO entryorder(`nome`, `telefone`, `tipo`, `marca`, `modelo`, `perifericos`, `defeito`, `preorc`, `data_entrada`) VALUES ('$nome','$telefone','$tipo','$marca','$modelo','$perifericos','$defeito','$preorcamento', NOW())");
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    <title>Sistema Mega-Xerox - Ordem de Entrada</title>
    <style>
        /* Estilos para o novo layout compacto */
        body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol"; font-size: 14px; background-color: #f4f4f4; }
        .ordemdeentrada { max-width: 800px; margin: 20px auto; background-color: #fff; padding: 20px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        
        header { display: flex; justify-content: space-between; align-items: flex-start; border-bottom: 2px solid #333; padding-bottom: 10px; }
        .header-left p, .header-left h2 { margin: 2px 0; }
        .header-right { text-align: right; }
        .header-right h3, .header-right p { margin: 0; }
        .header-right p { margin-top: 30px; }

        fieldset { border: none; padding: 10px 0; }
        
        .form-row { display: flex; gap: 15px; width: 100%; }
        .inputBox { margin-bottom: 12px; width: 100%; }
        .inputUser { width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; font-size:14px; }
        
        .data-fields { display: flex; justify-content: space-between; border: 1px solid #ccc; padding: 8px; border-radius: 4px; margin-bottom: 12px; }
        .data-fields b { margin-right: 5px; }

        .dispositivo { border: 1px solid #e0e0e0; padding: 15px; margin-bottom: 10px; border-radius: 5px; }
        
        /* Alteração para alinhar botão */
        .tipodispositivo { display: flex; align-items: center; flex-wrap: wrap; gap: 10px; margin-bottom: 15px; }
        .tipodispositivo b { margin-right: 10px; }
        .tipodispositivo button { margin-left: auto; /* Joga o botão para a direita */ }

        textarea.inputUser { height: 45px; } 

        .termos-servico { margin: 15px 0; padding: 15px; border: 2px solid #000; border-radius: 5px; background-color: #f5f5f5; }
        .termos-servico p { margin: 0 0 10px 0; font-weight: bold; text-align: center; font-size: 16px; }
        .termos-servico ol { margin: 0; padding-left: 20px; font-size: 12px; }
        .termos-servico li { margin-bottom: 8px; line-height: 1.4; }

        .botoes { text-align: right; margin-top: 20px; }
        button, .navegar { padding: 10px 15px; border-radius: 5px; text-decoration: none; border: none; cursor: pointer; font-size: 14px; }
        .navegar { background-color: #6c757d; color: white; }
        button[type="submit"] { background-color: #007bff; color: white; margin-left: 10px; }
    </style>
</head>
<body>
    <div class="ordemdeentrada">
        <header>
            <div class="header-left">
                <h2>Mega Xerox</h2>
                <p>Cnpj: 12.689.177/0001-26</p>
                <p>Whatsapp: 2665-5910</p>
            </div>
            <div class="header-right">
                <h3>Ordem de Entrada Nº <?php echo $next_id; ?></h3>
                <p>Ass.____________________________________</p>
            </div>
        </header>

        <div class="termos-servico">
            <p>TERMOS DE GARANTIA E CONDIÇÕES DE DEPÓSITO</p>
            <ol>
                <b><li>Após 7 (sete) dias da comunicação do orçamento (aprovado ou não), o equipamento deve ser retirado. Não ocorrendo a retirada no prazo, será cobrada uma taxa diária de R$ 10,00 (dez reais) a título de armazenamento.</li></b>
                <b><li>Conforme o Código Civil (arts. 643, 644 e 1.275) e o Código de Defesa do Consumidor, o cliente declara estar ciente e de acordo que, caso não retire o aparelho em até 30 (trinta) dias, o equipamento será considerado abandonado, podendo o prestador adotar medidas cabíveis para ressarcir os custos de serviço e armazenagem, inclusive mediante descarte ou venda do bem.</li></b>
                <b><li>A garantia é limitada exclusivamente ao defeito reparado. O prazo de garantia segue o artigo 26, inciso II, do Código de Defesa do Consumidor, sendo de 90 (noventa) dias para serviços prestados em produtos duráveis. A garantia não cobre mau uso, quedas, oxidação, violação do lacre ou defeitos diferentes do reparado.</li></b>
            </ol>
        </div>

        <form method="post" action="" id="formulario">
            <fieldset>
                <div class="inputBox">
                    <input class="inputUser" type="text" name="nome" id="nome" required oninput="this.value = this.value.toUpperCase()" placeholder="Nome do Cliente:"/>
                </div>
                <div class="inputBox">
                    <input class="inputUser" type="text" name="telefone" id="telefone" required placeholder="Telefone / Whatsapp:"/>
                </div>

                <div class="data-fields">
                    <div><b>Data de Entrada:</b> <span id="dataAtual"></span></div>
                    <div><b>Previsão do Orçamento:</b> <span id="dataPrevisao"></span></div>
                </div>
                
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
                            <button type="button" onclick="adicionarDispositivo()">Adicionar Dispositivo</button>
                        </div>
                        <div class="form-row">
                            <div class="inputBox">
                                <input class="inputUser" type="text" name="marca[0]" required oninput="this.value = this.value.toUpperCase()" placeholder="Marca:" />
                            </div>
                            <div class="inputBox">
                                <input class="inputUser" type="text" name="modelo[0]" required oninput="this.value = this.value.toUpperCase()" placeholder="Modelo:" />
                            </div>
                        </div>
                        <div class="inputBox">
                            <input class="inputUser" type="text" name="perifericos[0]" value="SEM PERIFÉRICOS" oninput="this.value = this.value.toUpperCase()" placeholder="Periféricos/Acessórios:"/>
                        </div>
                        <div class="inputBox">
                            <textarea class="inputUser" name="defeito[0]" oninput="this.value = this.value.toUpperCase()" placeholder="Defeito Apresentado:"></textarea>
                        </div>
                         <div class="inputBox">
                            <input class="inputUser" type="text" name="preorc" value="SEM ORÇAMENTO PRÉVIO" required oninput="this.value = this.value.toUpperCase()" placeholder="Orçamento Prévio:"/>
                        </div>
                    </div>
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
    let dispositivoCount = 1;
    function adicionarDispositivo() {
        const dispositivosDiv = document.getElementById('dispositivos');
        const novoDispositivo = document.createElement('div');
        novoDispositivo.classList.add('dispositivo');
        // O novo dispositivo não terá o botão "Adicionar"
        novoDispositivo.innerHTML = `
            <div class="tipodispositivo" style="justify-content: flex-start;">
                <b>Tipo de Dispositivo:</b>
                <input type="radio" name="tipo[${dispositivoCount}]" value="IMPRESSORA" required /> Impressora
                <input type="radio" name="tipo[${dispositivoCount}]" value="NOTEBOOK" /> Notebook
                <input type="radio" name="tipo[${dispositivoCount}]" value="DESKTOP" /> Desktop
                <input type="radio" name="tipo[${dispositivoCount}]" value="CONTROLE" /> Controle
                <input type="radio" name="tipo[${dispositivoCount}]" value="CONSOLE" /> Console
                <input type="radio" name="tipo[${dispositivoCount}]" value="CELULAR" /> Celular
                <input type="radio" name="tipo[${dispositivoCount}]" value="OUTRO" /> Outro
            </div>
            <div class="form-row">
                <div class="inputBox">
                    <input class="inputUser" type="text" name="marca[${dispositivoCount}]" required oninput="this.value = this.value.toUpperCase()" placeholder="Marca:" />
                </div>
                <div class="inputBox">
                    <input class="inputUser" type="text" name="modelo[${dispositivoCount}]" required oninput="this.value = this.value.toUpperCase()" placeholder="Modelo:" />
                </div>
            </div>
            <div class="inputBox">
                <input class="inputUser" type="text" name="perifericos[${dispositivoCount}]" value="SEM PERIFÉRICOS" oninput="this.value = this.value.toUpperCase()" placeholder="Periféricos/Acessórios:" />
            </div>
            <div class="inputBox">
                <textarea class="inputUser" name="defeito[${dispositivoCount}]" oninput="this.value = this.value.toUpperCase()" placeholder="Defeito Apresentado:"></textarea>
            </div>
            <div class="inputBox">
                <input class="inputUser" type="text" name="preorc" value="SEM ORÇAMENTO PRÉVIO" required oninput="this.value = this.value.toUpperCase()" placeholder="Orçamento Prévio:"/>
            </div>
            <button type="button" class="removerDispositivo" onclick="removerDispositivo(this)">Remover Dispositivo</button>
        `;
        dispositivosDiv.appendChild(novoDispositivo);
        dispositivoCount++;
    }

    function removerDispositivo(button) {
        button.parentElement.remove();
    }

    function formatarDataBr(data, incluirHora = true) {
        const options = { day: '2-digit', month: '2-digit', year: 'numeric' };
        if (incluirHora) {
            options.hour = '2-digit';
            options.minute = '2-digit';
        }
        return new Intl.DateTimeFormat('pt-BR', options).format(data);
    }

    function calcularDataPrevisao() {
        let diasUteisAdicionados = 0;
        let dataProvisoria = new Date(); 
        while (diasUteisAdicionados < 3) {
            dataProvisoria.setDate(dataProvisoria.getDate() + 1);
            let diaDaSemana = dataProvisoria.getDay();
            if (diaDaSemana >= 1 && diaDaSemana <= 5) { // de Segunda (1) a Sexta (5)
                diasUteisAdicionados++;
            }
        }
        document.getElementById('dataAtual').innerText = formatarDataBr(new Date());
        document.getElementById('dataPrevisao').innerText = formatarDataBr(dataProvisoria, false);
    }
    calcularDataPrevisao();
    </script>
</body>
</html>

