<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Controle de Vendas</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #0a9396;
            margin: 0;
            padding: 0;
            color: #333;
        }
        .menu {
            background-color: #005f73;
            padding: 15px;
            text-align: center;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
        }
        .menu a {
            color: white;
            margin: 0 20px;
            text-decoration: none;
            font-weight: 600;
            font-size: 16px;
            transition: color 0.3s, transform 0.3s;
            display: inline-block;
        }
        .menu a:hover {
            color: #94d2bd;
            transform: scale(1.1);
        }
        .content {
            padding: 30px;
            max-width: 1000px;
            margin: 40px auto;
            background-color: white;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.2);
            border-radius: 10px;
            animation: fadeIn 0.8s ease-in-out;
        }
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        .content-section {
            margin-top: 20px;
        }
        .hidden {
            display: none;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ccc;
            padding: 12px;
            text-align: left;
        }
        th {
            background-color: #0a9396;
            color: white;
        }
    </style>
    <script>
        function showSection(sectionId) {
            document.querySelectorAll('.content-section').forEach(section => {
                section.classList.add('hidden');
            });
            document.getElementById(sectionId).classList.remove('hidden');
        }
        function fetchSales() {
            var clienteId = document.getElementById('clienteConsulta').value;
            var xhr = new XMLHttpRequest();
            xhr.open('GET', 'consultar_vendas.php?id_cliente=' + clienteId, true);
            xhr.onreadystatechange = function () {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    document.getElementById('resultSection').innerHTML = xhr.responseText;
                }
            };
            xhr.send();
        }
    </script>
</head>
<body>
<div class="menu">
    <a href="../index.html">Inicio</a>
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
        <label for="clienteConsulta">Selecione o Cliente:</label>
        <select id="clienteConsulta" name="clienteConsulta" onchange="fetchSales()">
            <option value="">Selecione um Cliente</option>
            <?php
            require 'databaseconfig.php';
            $result = $conn->query("SELECT id_cliente, nome FROM clientes");
            while ($row = $result->fetch_assoc()) {
                echo '<option value="'.$row['id_cliente'].'">'.$row['nome'].'</option>';
            }
            ?>
        </select>
        <div id="resultSection" class="result-section">
            <table>
                <thead>
                    <tr>
                        <th>Descrição</th>
                        <th>Data</th>
                        <th>Valor Total</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Resultados da consulta serão inseridos aqui -->
                </tbody>
            </table>
        </div>
    </div>
</div>
<script>
    showSection('cadastrarCliente');
</script>
</body>
</html>
