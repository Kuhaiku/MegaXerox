<?php
include 'databaseconfig.php';

// Cadastro de Cliente
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['nome_cliente'])) {
    $nome = $_POST['nome_cliente'];
    if ($nome != '') {
        $conn->query("INSERT INTO clientes_fidelidade (nome) VALUES ('$nome')");
    }
    header('Location: fidelidade.php');
    exit;
}

// Cadastro de Venda
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cliente_id'], $_POST['unidade'], $_POST['descricao'], $_POST['valor'], $_POST['data'])) {
    $cliente_id = $_POST['cliente_id'];
    $unidade = $_POST['unidade'];
    $descricao = $_POST['descricao'];
    $valor = str_replace(",", ".", $_POST['valor']);
    $data = $_POST['data'];

    if ($cliente_id != '' && $unidade != '' && $descricao != '' && $valor != '' && $data != '') {
        $conn->query("INSERT INTO vendas_fidelidade (cliente_id, unidade, descricao_compra, valor, data_compra) 
                     VALUES ('$cliente_id', '$unidade', '$descricao', '$valor', '$data')");
    }
    header('Location: fidelidade.php');
    exit;
}

// AJAX: Listar Vendas Filtradas
if (isset($_POST['cliente_filtro'])) {
    $filtro_sql = "";
    if ($_POST['cliente_filtro'] != "") {
        $cliente_filtro = $_POST['cliente_filtro'];
        $filtro_sql = " WHERE v.cliente_id = '$cliente_filtro'";
    }

    $sql_vendas = "SELECT v.id, c.nome AS cliente, v.unidade, v.descricao_compra, v.valor, v.data_compra 
                   FROM vendas_fidelidade v
                   JOIN clientes_fidelidade c ON v.cliente_id = c.id
                   $filtro_sql";
    $vendas = $conn->query($sql_vendas)->fetch_all(MYSQLI_ASSOC);

    echo '<table>
            <tr>
                <th>ID</th>
                <th>Cliente</th>
                <th>Unidade</th>
                <th>Descrição</th>
                <th>Valor</th>
                <th>Data da Compra</th>
            </tr>';

    foreach ($vendas as $venda) {
        echo "<tr>
                <td>{$venda['id']}</td>
                <td>{$venda['cliente']}</td>
                <td>{$venda['unidade']}</td>
                <td>{$venda['descricao_compra']}</td>
                <td>{$venda['valor']}</td>
                <td>{$venda['data_compra']}</td>
              </tr>";
    }
    echo '</table>';
    exit;
}

// Lista de Clientes para os Formulários
$clientes = $conn->query("SELECT * FROM clientes_fidelidade")->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Programa de Fidelidade</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        nav a { margin-right: 15px; cursor: pointer; text-decoration: underline; color: blue; }
        .section { display: none; margin-top: 20px; }
        table { border-collapse: collapse; width: 100%; margin-top: 10px; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <nav>
        <a onclick="mostrar('cadastrar_cliente')">Cadastrar Cliente</a>
        <a onclick="mostrar('cadastrar_venda')">Cadastrar Venda</a>
        <a onclick="mostrar('listar_vendas')">Listar Vendas</a>
        <a href="../index.html">Início</a>
    </nav>

    <!-- Cadastrar Cliente -->
    <div id="cadastrar_cliente" class="section">
        <h2>Cadastrar Cliente</h2>
        <form method="POST">
            <label>Nome:</label><br>
            <input type="text" name="nome_cliente" required><br><br>
            <button type="submit">Cadastrar Cliente</button>
        </form>
    </div>

    <!-- Cadastrar Venda -->
    <div id="cadastrar_venda" class="section">
        <h2>Cadastrar Nova Compra</h2>
        <form method="POST">
            <label>Cliente:</label><br>
            <select name="cliente_id" required>
                <option value="">Selecione o cliente</option>
                <?php foreach ($clientes as $cliente): ?>
                    <option value="<?= $cliente['id']; ?>"><?= $cliente['nome']; ?></option>
                <?php endforeach; ?>
            </select><br><br>
            <label>Unidade:</label><br>
            <input type="text" name="unidade" required><br><br>
            <label>Descrição da Compra:</label><br>
            <input type="text" name="descricao" required><br><br>
            <label>Valor da Compra:</label><br>
            <input type="text" name="valor" required><br><br>
            <label>Data da Compra:</label><br>
            <input type="date" name="data" required><br><br>
            <button type="submit">Cadastrar Compra</button>
        </form>
    </div>

    <!-- Listar Vendas -->
    <div id="listar_vendas" class="section">
        <h2>Vendas Cadastradas</h2>

        <label>Selecionar Cliente:</label><br>
        <select id="cliente_filtro" onchange="carregarVendas()">
            <option value="">-- Todos os Clientes --</option>
            <?php foreach ($clientes as $cliente): ?>
                <option value="<?= $cliente['id']; ?>">
                    <?= $cliente['nome']; ?>
                </option>
            <?php endforeach; ?>
        </select>

        <br><br>

        <div id="vendas_table">
            <!-- A tabela de vendas será carregada aqui via AJAX -->
        </div>
    </div>

<script>
function mostrar(secao) {
    var secoes = document.getElementsByClassName('section');
    for (var i = 0; i < secoes.length; i++) {
        secoes[i].style.display = 'none';
    }
    document.getElementById(secao).style.display = 'block';
}

function carregarVendas() {
    const clienteId = document.getElementById('cliente_filtro').value;

    const xhr = new XMLHttpRequest();
    xhr.open('POST', 'fidelidade.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
            document.getElementById('vendas_table').innerHTML = xhr.responseText;
        }
    };

    xhr.send('cliente_filtro=' + clienteId);
}
</script>
</body>
</html>
