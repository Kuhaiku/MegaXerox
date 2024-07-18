<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/printorder.css" />
<link rel="icon" type="image/x-icon" href="../src/favicon.ico" />
    <link rel="stylesheet" href="../css/exitorder.css" />
    <title>Sistema Mega-Xerox - Ordem de Saída</title>
    <script>
        function filtrarEntradas() {
            var input, filter, table, tr, td, i, txtValue;
            input = document.getElementById("busca");
            filter = input.value.toLowerCase();
            table = document.getElementById("tabelaProdutos");
            tr = table.getElementsByTagName("tr");

            for (i = 1; i < tr.length; i++) {
                td = tr[i].getElementsByClassName("id");
                if (td.length > 0) {
                    txtValue = td[0].textContent || td[0].innerText;
                    if (txtValue.toLowerCase().indexOf(filter) > -1) {
                        tr[i].style.display = "";
                    } else {
                        tr[i].style.display = "none";
                    }
                }
            }
        }

        function converterValor() {
            var valorInputs = document.getElementsByName("valor");
            for (var i = 0; i < valorInputs.length; i++) {
                var valor = valorInputs[i].value;
                
                // Substitui a vírgula por ponto
                valor = valor.replace(',', '.');
                
                // Atualiza o valor do input com o novo formato
                valorInputs[i].value = valor;
            }
        }
    </script>
</head>
<body>
    <input type="text" id="busca" placeholder="Buscar Cliente" onkeyup="filtrarEntradas()">
    <?php
    include '../php/databaseconfig.php';

    if (isset($_POST['salvar_ordem_saida'])) {
        $nome = $_POST['nome'];
        $telefone = $_POST['telefone'];
        $tipo = $_POST['tipo'];
        $marca = $_POST['marca'];
        $modelo = $_POST['modelo'];
        $perifericos = $_POST['perifericos'];
        $defeito = $_POST['defeito'];
        $preorc = $_POST['preorc'];
        $servico_realizado = $_POST['servico_realizado'];
        $valor = floatval(str_replace(',', '.', $_POST['valor']));
        $data_saida = date('Y-m-d H:i:s');

        $sql = "INSERT INTO sistemaloja.exitorder (nome, telefone, tipo, marca, modelo, perifericos, defeito, preorc, valor_conserto, servico_realizado, data_saida) 
                VALUES ('$nome', '$telefone', '$tipo', '$marca', '$modelo', '$perifericos', '$defeito', '$preorc', $valor, '$servico_realizado', '$data_saida')";

        if (mysqli_query($conn, $sql)) {
            echo "Ordem de saída registrada com sucesso.";
        } else {
            echo "Erro ao registrar a ordem de saída: " . mysqli_error($conn);
        }
    }

    if (isset($_GET['id'])) {
        $id = intval($_GET['id']);
        $sql = "SELECT * FROM sistemaloja.entryorder WHERE id = $id";
        $result = mysqli_query($conn, $sql);

        if ($result && mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            echo "<div class='ordemdeentrada'>
                    <fieldset>
                        <legend><b>Ordem de Entrada <span id='numberField'> Nº {$row['id']}</span></b></legend>
                        <form method='POST' action=''>
                            <div class='inputBox'>
                                <p>Nome: <input type='text' name='nome' value='{$row['nome']}' readonly></p>
                                <p>Telefone: <input type='text' name='telefone' value='{$row['telefone']}' readonly></p>
                                <p>Data de entrada: <input type='text' name='data_entrada' value='{$row['data_entrada']}' readonly></p>
                                <p>Tipo de Dispositivo: <input type='text' name='tipo' value='{$row['tipo']}' readonly></p>
                                <p>Marca do Dispositivo: <input type='text' name='marca' value='{$row['marca']}' readonly></p>
                                <p>Modelo do Dispositivo: <input type='text' name='modelo' value='{$row['modelo']}' readonly></p>
                                <p>Periféricos: <input type='text' name='perifericos' value='{$row['perifericos']}' readonly></p>
                                <p>Defeito Apresentado: <input type='text' name='defeito' value='{$row['defeito']}' readonly></p>
                                <p>Orçamento Prévio: <input type='text' name='preorc' value='{$row['preorc']}' readonly></p>
                                <p>Serviço Realizado: <input type='text' name='servico_realizado'></p>
                                <p>Valor: <input type='text' name='valor' onblur='converterValor()'></p>
                            </div>
                            
                            <div class='botoes'>
                                <a href='../index.html'>Voltar</a>
                                <button type='submit' name='salvar_ordem_saida'>Salvar</button>
                            </div>
                        </form>
                    </fieldset>
                  </div>";
        } else {
            echo "Registro não encontrado.";
        }

        mysqli_free_result($result);
    } else {
        $sql = "SELECT * FROM sistemaloja.entryorder";
        $result = mysqli_query($conn, $sql);

        if ($result && mysqli_num_rows($result) > 0) {
            echo "<table id='tabelaProdutos'>
                    <tr>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>Telefone</th>
                        <th>Data de Entrada</th>
                        <th>Tipo</th>
                        <th>Marca</th>
                        <th>Modelo</th>
                        <th>Periféricos</th>
                        <th>Defeito</th>
                        <th>Orçamento Prévio</th>
                        <th>Ação</th>
                    </tr>";
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>
                        <td class='id'>{$row['id']}</td>
                        <td>{$row['nome']}</td>
                        <td>{$row['telefone']}</td>
                        <td>{$row['data_entrada']}</td>
                        <td>{$row['tipo']}</td>
                        <td>{$row['marca']}</td>
                        <td>{$row['modelo']}</td>
                        <td>{$row['perifericos']}</td>
                        <td>{$row['defeito']}</td>
                        <td>{$row['preorc']}</td>
                        <td><a href='?id={$row['id']}'>Selecionar</a></td>
                    </tr>";
            }
            echo "</table>";
        } else {
            echo "Nenhum registro encontrado.";
        }

        mysqli_close($conn);
    }
    ?>
</body>
</html>
