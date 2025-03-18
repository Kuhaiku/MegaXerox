<?php
include 'databaseconfig.php';

// Cadastro de Cliente
if (isset($_POST['tipo']) && $_POST['tipo'] == 'cliente') {
    $nome = $_POST['nome'];
    if (!empty($nome)) {
        $sql = "INSERT INTO clientes_fidelidade (nome) VALUES ('$nome')";
        $conn->query($sql);
    }
}

// Cadastro de Venda
if (isset($_POST['tipo']) && $_POST['tipo'] == 'venda') {
    $cliente_id = $_POST['cliente_id'];
    $unidade = $_POST['unidade'];
    $descricao_compra = $_POST['descricao_compra'];
    $valor = str_replace(',', '.', $_POST['valor']);
    $data_compra = $_POST['data_compra'];

    if (is_numeric($valor)) {
        $sql = "INSERT INTO vendas_fidelidade (cliente_id, unidade, descricao_compra, valor, data_compra)
                VALUES ('$cliente_id', '$unidade', '$descricao_compra', '$valor', '$data_compra')";
        $conn->query($sql);
    }
}

// Buscar clientes para o dropdown e listagem
$clientes = $conn->query("SELECT * FROM clientes_fidelidade")->fetch_all(MYSQLI_ASSOC);

// Buscar vendas para a listagem
$sql_vendas = "SELECT v.id, c.nome AS cliente, v.unidade, v.descricao_compra, v.valor, v.data_compra 
               FROM vendas_fidelidade v
               JOIN clientes_fidelidade c ON v.cliente_id = c.id";
$vendas = $conn->query($sql_vendas)->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Programa de Fidelidade</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; }
        nav a { margin-right: 15px; cursor: pointer; color: blue; text-decoration: underline; }
        .section { display: none; margin-top: 20px; }
        table { border-collapse: collapse; width: 100%; margin-top: 10px; }
        th, td { border: 1px solid #aaa; padding: 8px; text-align: left; }
        .active { display: block; }
    </style>
</head>
<body>

    <h1>Programa de Fidelidade</h1>
    <a href="../index.html">⬅ Voltar ao Início</a>

    <nav>
        <a onclick="showSection('cliente')">Cadastrar Cliente</a>
        <a onclick="showSection('venda')">Cadastrar Venda</a>
        <a onclick="showSection('listar_clientes')">Listar Clientes</a>
        <a onclick="showSection('listar_vendas')">Listar Vendas</a>
    </nav>

    <!-- Cadastrar Cliente -->
    <div id="cliente" class="section">
        <h2>Cadastrar Cliente</h2>
        <form method="POST">
            <input type="hidden" name="tipo" value="cliente">
            <label>Nome do Cliente:</label><br>
            <input type="text" name="nome" required><br><br>
            <button type="submit">Cadastrar</button>
        </form>
    </div>

    <!-- Cadastrar Venda -->
    <div id="venda" class="section">
        <h2>Cadastrar Venda</h2>
        <form method="POST">
            <input type="hidden" name="tipo" value="venda">

            <label>Cliente:</label><br>
            <select name="cliente_id" required>
                <?php foreach ($clientes as $cliente): ?>
                    <option value="<?= $cliente['id']; ?>"><?= $cliente['nome']; ?></option>
                <?php endforeach; ?>
            </select><br><br>

            <label>Unidade:</label><br>
            <input type="number" name="unidade" required><br><br>

            <label>Descrição da Compra:</label><br>
            <input type="text" name="descricao_compra" required><br><br>

            <label>Valor:</label><br>
            <input type="text" name="valor" required><br><br>

            <label>Data da Compra:</label><br>
            <input type="date" name="data_compra" required><br><br>

            <button type="submit">Cadastrar</button>
        </form>
    </div>

    <!-- Listar Clientes -->
    <div id="listar_clientes" class="section">
        <h2>Clientes Cadastrados</h2>
        <table>
            <tr>
                <th>ID</th>
                <th>Nome</th>
            </tr>
            <?php foreach ($clientes as $cliente): ?>
                <tr>
                    <td><?= $cliente['id']; ?></td>
                    <td><?= $cliente['nome']; ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>

    <!-- Listar Vendas -->
   <!-- Listar Vendas -->
<div id="listar_vendas" class="section">
    <h2>Vendas Cadastradas</h2>

    <form method="POST" id="filtroForm">
        <label>Selecionar Cliente:</label><br>
        <select name="cliente_filtro" onchange="document.getElementById('filtroForm').submit()">
            <option value="">-- Todos os Clientes --</option>
            <?php foreach ($clientes as $cliente): ?>
                <option value="<?= $cliente['id']; ?>" <?= (isset($_POST['cliente_filtro']) && $_POST['cliente_filtro'] == $cliente['id']) ? 'selected' : '' ?>>
                    <?= $cliente['nome']; ?>
                </option>
            <?php endforeach; ?>
        </select>
    </form>

    <br>

    <table>
        <tr>
            <th>ID</th>
            <th>Cliente</th>
            <th>Unidade</th>
            <th>Descrição</th>
            <th>Valor</th>
            <th>Data da Compra</th>
        </tr>
        <?php
        $filtro_sql = "";
        if (isset($_POST['cliente_filtro']) && $_POST['cliente_filtro'] != "") {
            $cliente_filtro = $_POST['cliente_filtro'];
            $filtro_sql = " WHERE v.cliente_id = '$cliente_filtro'";
        }

        $sql_vendas_filtradas = "SELECT v.id, c.nome AS cliente, v.unidade, v.descricao_compra, v.valor, v.data_compra 
                                 FROM vendas_fidelidade v
                                 JOIN clientes_fidelidade c ON v.cliente_id = c.id
                                 $filtro_sql";
        $vendas_filtradas = $conn->query($sql_vendas_filtradas)->fetch_all(MYSQLI_ASSOC);

        foreach ($vendas_filtradas as $venda): ?>
            <tr>
                <td><?= $venda['id']; ?></td>
                <td><?= $venda['cliente']; ?></td>
                <td><?= $venda['unidade']; ?></td>
                <td><?= $venda['descricao_compra']; ?></td>
                <td><?= $venda['valor']; ?></td>
                <td><?= $venda['data_compra']; ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
</div>


    <script>
        function showSection(id) {
            const sections = document.querySelectorAll('.section');
            sections.forEach(sec => sec.classList.remove('active'));
            document.getElementById(id).classList.add('active');
        }

        // Mostrar a seção de cadastrar cliente por padrão
        showSection('cliente');
    </script>
</body>
</html>
