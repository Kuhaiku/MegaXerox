<?php
include_once('databaseconfig.php'); // Certifique-se que o caminho está correto

// Lógica para salvar a ordem de saída
if (isset($_POST['salvar_ordem_saida'])) {
    // Coleta todos os dados do formulário, incluindo os campos readonly
    $nome = $_POST['nome'] ?? '';
    $telefone = $_POST['telefone'] ?? '';
    $tipo = $_POST['tipo'] ?? '';
    $marca = $_POST['marca'] ?? '';
    $modelo = $_POST['modelo'] ?? '';
    $perifericos = $_POST['perifericos'] ?? '';
    $defeito = $_POST['defeito'] ?? '';
    $preorc = $_POST['preorc'] ?? '';
    $servico_realizado = $_POST['servico_realizado'] ?? '';
    
    // Converte valor para o formato do banco de dados (ex: 1.250,50 -> 1250.50)
    $valor = $_POST['valor'] ?? '0';
    $valor = str_replace('.', '', $valor); // Remove separador de milhar
    $valor = str_replace(',', '.', $valor); // Troca vírgula por ponto
    $valor = floatval($valor);

    $data_saida = date('Y-m-d H:i:s');

    // Insere na tabela de saída
    $sql_insert = "INSERT INTO exitorder (nome, telefone, tipo, marca, modelo, perifericos, defeito, preorc, valor_conserto, servico_realizado, data_saida) 
                   VALUES ('$nome', '$telefone', '$tipo', '$marca', '$modelo', '$perifericos', '$defeito', '$preorc', '$valor', '$servico_realizado', '$data_saida')";

    if (mysqli_query($conn, $sql_insert)) {
        // Redireciona para evitar reenvio do formulário
        header("Location: exitorder.php?success=1");
        exit();
    } else {
        $error_message = mysqli_error($conn);
        header("Location: exitorder.php?error=" . urlencode($error_message));
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
    <title>Sistema Mega-Xerox - Ordem de Saída</title>
    <style>
        /* Estilos reaproveitados do layout de entrada */
        body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol"; font-size: 14px; background-color: #f4f4f4; }
        .container { max-width: 1000px; margin: 20px auto; background-color: #fff; padding: 20px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        
        header { display: flex; justify-content: space-between; align-items: flex-start; border-bottom: 2px solid #333; padding-bottom: 10px; margin-bottom: 15px; }
        .header-left p, .header-left h2 { margin: 2px 0; }
        .header-right { text-align: right; }
        .header-right h3, .header-right p { margin: 0; }
        .header-right p { margin-top: 30px; }

        fieldset { border: none; padding: 10px 0; }
        
        .form-row { display: flex; gap: 15px; width: 100%; }
        .inputBox { margin-bottom: 12px; width: 100%; }
        .inputUser { width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; font-size:14px; }
        .inputUser[readonly] { background-color: #e9ecef; cursor: not-allowed; }

        .data-fields { display: flex; justify-content: space-between; border: 1px solid #ccc; padding: 8px; border-radius: 4px; margin-bottom: 12px; }
        .data-fields b { margin-right: 5px; }

        .dispositivo { border: 1px solid #e0e0e0; padding: 15px; margin-bottom: 10px; border-radius: 5px; }
        
        .tipodispositivo { margin-bottom: 15px; }

        textarea.inputUser { height: 60px; }

        .botoes { text-align: right; margin-top: 20px; }
        button, .navegar { padding: 10px 15px; border-radius: 5px; text-decoration: none; border: none; cursor: pointer; font-size: 14px; }
        .navegar { background-color: #6c757d; color: white; display: inline-block; }
        button[type="submit"] { background-color: #28a745; color: white; margin-left: 10px; }

        /* Estilos para a tabela de busca */
        #busca { width: 100%; padding: 10px; margin-bottom: 20px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
        #tabelaProdutos { width: 100%; border-collapse: collapse; }
        #tabelaProdutos th, #tabelaProdutos td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        #tabelaProdutos th { background-color: #f2f2f2; }
        #tabelaProdutos tr:nth-child(even) { background-color: #f9f9f9; }
        #tabelaProdutos tr:hover { background-color: #ddd; }
        #tabelaProdutos a { color: #007bff; text-decoration: none; font-weight: bold; }
        #tabelaProdutos a:hover { text-decoration: underline; }

    </style>
</head>
<body>
    <div class="container">
        <?php
        // Se um ID foi passado pela URL, mostra o formulário de saída
        if (isset($_GET['id'])) {
            $id = intval($_GET['id']);
            $sql_select = "SELECT * FROM entryorder WHERE id = $id";
            $result = mysqli_query($conn, $sql_select);

            if ($result && mysqli_num_rows($result) > 0) {
                $row = mysqli_fetch_assoc($result);
        ?>
                <header>
                    <div class="header-left">
                        <h2>Mega Xerox</h2>
                        <p>Cnpj: 12.689.177/0001-26</p>
                        <p>Whatsapp: 2665-5910</p>
                    </div>
                    <div class="header-right">
                        <h3>Ordem de Saída (Ref. Entrada Nº <?php echo $row['id']; ?>)</h3>
                        <p>Ass.____________________________________</p>
                    </div>
                </header>

                <form method="post" action="">
                    <input type="hidden" name="tipo" value="<?php echo htmlspecialchars($row['tipo']); ?>">
                    <input type="hidden" name="marca" value="<?php echo htmlspecialchars($row['marca']); ?>">
                    <input type="hidden" name="modelo" value="<?php echo htmlspecialchars($row['modelo']); ?>">
                    <input type="hidden" name="perifericos" value="<?php echo htmlspecialchars($row['perifericos']); ?>">
                    <input type="hidden" name="defeito" value="<?php echo htmlspecialchars($row['defeito']); ?>">
                    <input type="hidden" name="preorc" value="<?php echo htmlspecialchars($row['preorc']); ?>">
                    
                    <fieldset>
                        <div class="inputBox">
                            <input class="inputUser" type="text" name="nome" value="<?php echo htmlspecialchars($row['nome']); ?>" readonly placeholder="Nome do Cliente:"/>
                        </div>
                        <div class="inputBox">
                            <input class="inputUser" type="text" name="telefone" value="<?php echo htmlspecialchars($row['telefone']); ?>" readonly placeholder="Telefone / Whatsapp:"/>
                        </div>

                        <div class="data-fields">
                            <div><b>Data de Entrada:</b> <?php echo date('d/m/Y H:i', strtotime($row['data_entrada'])); ?></div>
                            <div><b>Data de Saída:</b> <?php echo date('d/m/Y H:i'); ?></div>
                        </div>
                        
                        <div class="dispositivo">
                            <div class="tipodispositivo">
                                <b>Tipo de Dispositivo:</b> <?php echo htmlspecialchars($row['tipo']); ?>
                            </div>
                            <div class="form-row">
                                <div class="inputBox">
                                    <input class="inputUser" type="text" value="Marca: <?php echo htmlspecialchars($row['marca']); ?>" readonly />
                                </div>
                                <div class="inputBox">
                                    <input class="inputUser" type="text" value="Modelo: <?php echo htmlspecialchars($row['modelo']); ?>" readonly />
                                </div>
                            </div>
                            <div class="inputBox">
                                <input class="inputUser" type="text" value="Periféricos/Acessórios: <?php echo htmlspecialchars($row['perifericos']); ?>" readonly/>
                            </div>
                            <div class="inputBox">
                                <textarea class="inputUser" readonly>Defeito Apresentado: <?php echo htmlspecialchars($row['defeito']); ?></textarea>
                            </div>
                            <div class="inputBox">
                                 <input class="inputUser" type="text" value="Orçamento Prévio: <?php echo htmlspecialchars($row['preorc']); ?>" readonly />
                            </div>
                             <hr>
                            <div class="inputBox">
                                <textarea class="inputUser" name="servico_realizado" required placeholder="Serviço Realizado:"></textarea>
                            </div>
                            <div class="inputBox">
                                <input class="inputUser" type="text" name="valor" required placeholder="Valor Total do Conserto (R$):" />
                            </div>
                        </div>
                        
                        <div class="botoes">
                            <a class="navegar" href="exitorder.php">Voltar para Busca</a>
                            <button type="submit" name="salvar_ordem_saida">
                                <i class="fas fa-save"></i> Salvar e Gerar Saída
                            </button>
                        </div>
                    </fieldset>
                </form>
        <?php
            } else {
                echo "<p>Registro de entrada não encontrado.</p>";
            }
        // Se nenhum ID foi passado, mostra a tabela de busca
        } else {
        ?>
            <h2>Selecione uma Ordem de Entrada para Gerar a Saída</h2>
            <input type="text" id="busca" onkeyup="filtrarTabela()" placeholder="Digite o nome ou ID do cliente para buscar...">
            
            <table id="tabelaProdutos">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>Telefone</th>
                        <th>Tipo</th>
                        <th>Modelo</th>
                        <th>Data de Entrada</th>
                        <th>Ação</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                    $sql_all = "SELECT id, nome, telefone, tipo, modelo, data_entrada FROM entryorder ORDER BY id DESC";
                    $result_all = mysqli_query($conn, $sql_all);
                    if ($result_all && mysqli_num_rows($result_all) > 0) {
                        while ($row = mysqli_fetch_assoc($result_all)) {
                            echo "<tr>
                                    <td class='search-id'>{$row['id']}</td>
                                    <td class='search-nome'>{$row['nome']}</td>
                                    <td>{$row['telefone']}</td>
                                    <td>{$row['tipo']}</td>
                                    <td>{$row['modelo']}</td>
                                    <td>" . date('d/m/Y H:i', strtotime($row['data_entrada'])) . "</td>
                                    <td><a href='?id={$row['id']}'>Gerar Saída</a></td>
                                  </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='7'>Nenhuma ordem de entrada encontrada.</td></tr>";
                    }
                ?>
                </tbody>
            </table>
        <?php
        }
        mysqli_close($conn);
        ?>
    </div>

    <script>
        function filtrarTabela() {
            const input = document.getElementById("busca");
            const filter = input.value.toLowerCase();
            const table = document.getElementById("tabelaProdutos");
            const tr = table.getElementsByTagName("tr");

            for (let i = 1; i < tr.length; i++) { // Começa em 1 para pular o cabeçalho
                let tdId = tr[i].getElementsByClassName("search-id")[0];
                let tdNome = tr[i].getElementsByClassName("search-nome")[0];
                if (tdId || tdNome) {
                    let txtValueId = tdId.textContent || tdId.innerText;
                    let txtValueNome = tdNome.textContent || tdNome.innerText;
                    if (txtValueId.toLowerCase().indexOf(filter) > -1 || txtValueNome.toLowerCase().indexOf(filter) > -1) {
                        tr[i].style.display = "";
                    } else {
                        tr[i].style.display = "none";
                    }
                }
            }
        }
    </script>
</body>
</html>
