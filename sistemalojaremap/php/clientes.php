<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Controle de Vendas</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #005f73;
            margin: 0;
            padding: 0;
            color: #333;
        }
        .menu {
            background-color: #005f78;
            padding: 15px;
            text-align: center;
        }
        .menu a {
            color: white;
            margin: 0 20px;
            text-decoration: none;
            font-weight: bold;
            font-size: 16px;
            transition: color 0.3s;
        }
        .menu a:hover {
            color: black;
            cursor: pointer;
        }
        .content {
            padding: 20px;
            max-width: 900px;
            margin: 0 auto;
            background-color: white;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 5px;
        }
        .content-section {
            margin-top: 20px;
        }
        .hidden {
            display: none;
        }
        h1, h2 {
            color: #007bff;
        }
        label {
            display: block;
            margin-top: 10px;
            font-weight: bold;
        }
        input, select {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }
        input[type="submit"] {
            margin-top: 20px;
            background-color: #005f73;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        input[type="submit"]:hover {
            background-color: #004e5f;
        }
        .result-section {
            margin-top: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #005f73;
            color: white;
        }
        button {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 6px 12px;
            margin-right: 5px;
            border-radius: 4px;
            cursor: pointer;
        }
        button:hover {
            background-color: #0056b3;
        }
        .success-message {
            color: green;
            font-weight: bold;
            margin-top: 10px;
        }
        .error-message {
            color: red;
            font-weight: bold;
            margin-top: 10px;
        }
    </style>
    <script>
        function showSection(sectionId) {
            document.querySelectorAll('.content-section').forEach(section => {
                section.classList.add('hidden');
            });
            document.getElementById(sectionId).classList.remove('hidden');
        }

        function fetchSalesById(clienteId) {
            if (clienteId === "") {
                document.getElementById('resultSection').innerHTML = "";
                return;
            }
            var xhr = new XMLHttpRequest();
            xhr.open('GET', 'consultar_vendas.php?id_cliente=' + clienteId, true);
            xhr.onreadystatechange = function () {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    document.getElementById('resultSection').innerHTML = xhr.responseText;
                    somarValores();
                }
            };
            xhr.send();
        }

        function somarValores() {
            var total = 0;
            var valores = document.querySelectorAll('.valor-total');
            valores.forEach(function(td) {
                var valorTexto = td.textContent.replace('R$', '').replace(',', '.').trim();
                var valor = parseFloat(valorTexto);
                if (!isNaN(valor)) {
                    total += valor;
                }
            });
            var totalFormatado = total.toLocaleString('pt-BR', {style: 'currency', currency: 'BRL'});
            document.getElementById('totalGeral').textContent = 'Total Geral: ' + totalFormatado;
        }
    </script>
</head>
<body>

    <div class="menu">
        <a href="../index.html">Início</a>
        <a onclick="showSection('cadastrarCliente')">Cadastrar Cliente</a>
        <a onclick="showSection('cadastrarVenda')">Cadastrar Venda</a>
        <a onclick="showSection('gerenciarVendas')">Gerenciar Vendas</a>
    </div>

    <div class="content">
        <div id="cadastrarCliente" class="content-section hidden">
            <h2>Cadastrar Cliente</h2>
            <?php include 'cadastrar_cliente.php'; ?>
        </div>

        <div id="cadastrarVenda" class="content-section hidden">
            <h2>Cadastrar Venda</h2>
            <?php include 'cadastrar_venda.php'; ?>
        </div>

        <div id="gerenciarVendas" class="content-section hidden">
            <h2>Gerenciar Vendas</h2>

            <table>
                <thead>
                    <tr>
                        <th>Nome do Cliente</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    require 'databaseconfig.php';
                    $result = $conn->query("SELECT id_cliente, nome FROM clientes");
                    while ($row = $result->fetch_assoc()) {
                        $id = $row['id_cliente'];
                        $nome = htmlspecialchars($row['nome'], ENT_QUOTES, 'UTF-8');
                        $urlNome = urlencode($row['nome']);
                        echo "<tr>";
                        echo "<td>$nome</td>";
                        echo "<td>
                                <button onclick=\"fetchSalesById('$id')\">Ver Vendas</button>
                                <a href='https://loja-megaxerox.qtgmyu.easypanel.host/php/recibo_cliente.php?cliente=$urlNome' target='_blank'>
                                    <button>Recibo</button>
                                </a>
                              </td>";
                        echo "</tr>";
                    }
                    ?>
                </tbody>
            </table>

            <div id="resultSection" class="result-section">
                <!-- Resultados da consulta serão inseridos aqui -->
                <p id="totalGeral"></p>
            </div>
        </div>
    </div>

    <script>
        showSection('cadastrarCliente');
    </script>

</body>
</html>
