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
        h1, h2 {
            color: #0a9396;
            text-align: center;
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-top: 10px;
            font-weight: 600;
        }
        input[type="text"],
        input[type="date"],
        input[type="number"],
        select {
            width: 100%;
            padding: 12px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 8px;
            box-sizing: border-box;
            outline: none;
            transition: border 0.3s;
        }
        input[type="text"]:focus, 
        input[type="date"]:focus, 
        input[type="number"]:focus, 
        select:focus {
            border: 1px solid #0a9396;
        }
        input[type="submit"] {
            margin-top: 20px;
            background-color: #0a9396;
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.3s, transform 0.3s;
        }
        input[type="submit"]:hover {
            background-color: #94d2bd;
            transform: scale(1.05);
        }
        ul {
            list-style: none;
            padding: 0;
        }
        li {
            background-color: #e9ecef;
            margin: 10px 0;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            display: grid;
            grid-template-columns: 2fr 1fr 1fr 1fr;
            gap: 15px;
            align-items: center;
            transition: transform 0.3s;
            overflow: hidden;
        }
        li:hover {
            transform: scale(1.02);
        }
        li span {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 100%;
            display: inline-block;
        }
        li .ver-mais {
            display: none;
        }
        li a {
            color: #0a9396;
            text-decoration: none;
            font-weight: 600;
            text-align: right;
            cursor: pointer;
        }
        li a:hover {
            text-decoration: underline;
        }
        .success-message {
            color: #2a9d8f;
            font-weight: bold;
            margin-top: 10px;
            text-align: center;
        }
        .error-message {
            color: #d62828;
            font-weight: bold;
            margin-top: 10px;
            text-align: center;
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
        function toggleVerMais(button) {
            var span = button.previousElementSibling;
            span.classList.toggle('ver-mais');
            button.innerText = span.classList.contains('ver-mais') ? 'Ver Menos' : 'Ver Mais';
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
        <div id="resultSection" class="result-section"></div>
    </div>
</div>
<script>
    showSection('cadastrarCliente');
</script>
</body>
</html>
