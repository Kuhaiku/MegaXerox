<?php
include './databaseconfig.php';

// Cadastro de Cliente
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['nome_cliente'])) {
    $nome = $_POST['nome_cliente'];
    if ($nome != '') {
        $conn->query("INSERT INTO clientes_fidelidade (nome) VALUES ('$nome')");
    }
    header('Location: fidelidade.php');
    exit;
}

// Cadastro de Venda e Lógica de Brindes por Unidade
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cliente_id'], $_POST['unidade'], $_POST['descricao'], $_POST['valor'], $_POST['data'])) {
    $cliente_id = $_POST['cliente_id'];
    $unidade = intval($_POST['unidade']);
    $descricao = $_POST['descricao'];
    $valor = str_replace(",", ".", $_POST['valor']);
    $data = $_POST['data'];

    if ($cliente_id != '' && $unidade > 0 && $descricao != '' && $valor != '' && $data != '') {
        $conn->query("INSERT INTO vendas_fidelidade (cliente_id, unidade, descricao_compra, valor, data_compra) 
                     VALUES ('$cliente_id', '$unidade', '$descricao', '$valor', '$data')");

        // Soma total de unidades compradas pelo cliente
        $result_unidades = $conn->query("SELECT SUM(unidade) AS total_unidades FROM vendas_fidelidade WHERE cliente_id = '$cliente_id'");
        $row_unidades = $result_unidades->fetch_assoc();
        $total_unidades = intval($row_unidades['total_unidades']);

        // Quantos brindes já foram dados
        $result_brindes = $conn->query("SELECT COUNT(*) AS total_brindes FROM brindes_fidelidade WHERE cliente_id = '$cliente_id'");
        $row_brindes = $result_brindes->fetch_assoc();
        $total_brindes = intval($row_brindes['total_brindes']);

        // Calcular quantos brindes deveriam ter sido dados
        $brindes_devidos = intdiv($total_unidades, 5);
        $brindes_a_cadastrar = $brindes_devidos - $total_brindes;

        for ($i = 0; $i < $brindes_a_cadastrar; $i++) {
            $descricao_brinde = "Brinde por atingir 5 unidades";
            $data_brinde = date('Y-m-d');
            $conn->query("INSERT INTO brindes_fidelidade (cliente_id, descricao_brinde, data_brinde) 
                         VALUES ('$cliente_id', '$descricao_brinde', '$data_brinde')");
        }
    }
    header('Location: fidelidade.php');
    exit;
}

// AJAX: Listar Vendas
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
                
                <th>Cliente</th>
                <th>Unidade</th>
                <th>Descrição</th>
                <th>Valor</th>
                <th>Data da Compra</th>
            </tr>';
    foreach ($vendas as $venda) {
        echo "<tr>
                
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

// AJAX: Listar Brindes
if (isset($_POST['cliente_filtro_brindes'])) {
    $filtro_sql = "";
    if ($_POST['cliente_filtro_brindes'] != "") {
        $cliente_filtro = $_POST['cliente_filtro_brindes'];
        $filtro_sql = " WHERE b.cliente_id = '$cliente_filtro'";
    }

    $sql_brindes = "SELECT b.id, c.nome AS cliente, b.descricao_brinde, b.data_brinde 
                    FROM brindes_fidelidade b
                    JOIN clientes_fidelidade c ON b.cliente_id = c.id
                    $filtro_sql";
    $brindes = $conn->query($sql_brindes)->fetch_all(MYSQLI_ASSOC);

    echo '<table>
            <tr>
                <th>Cliente</th>
                <th>Descrição do Brinde</th>
                <th>Data do Brinde</th>
            </tr>';
    foreach ($brindes as $brinde) {
        echo "<tr>
                <td>{$brinde['cliente']}</td>
                <td>{$brinde['descricao_brinde']}</td>
                <td>{$brinde['data_brinde']}</td>
              </tr>";
    }
    echo '</table>';
    exit;
}

// Lista de Clientes
$clientes = $conn->query("SELECT * FROM clientes_fidelidade")->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Programa de Fidelidade</title>
    <style>
/* Reset básico */
* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
  font-family: Arial, sans-serif;
}

/* Corpo da página */
body {
  background-color: #f5f5f5;
  padding: 20px;
  color: #333;
}

/* Cabeçalho com link */
h1 {
  margin-bottom: 20px;
  color: #444;
  text-align: center;
}

a {
  text-decoration: none;
  color: #ffffff;
  background-color: #4CAF50;
  padding: 8px 14px;
  border-radius: 5px;
  transition: background-color 0.3s;
}

a:hover {
  background-color: #45a049;
}

/* Menu no topo */
nav {
  margin-bottom: 20px;
  text-align: center;
  padding: 10px 0;
  background-color: #007BFF;
  border-radius: 8px;
}

nav a {
  margin: 0 10px;
  padding: 10px 20px;
  color: #fff;
  background: none;
  border-radius: 5px;
  transition: background-color 0.3s;
}

nav a:hover {
  background-color: #0056b3;
}

/* Seções */
section {
  background-color: white;
  padding: 20px;
  margin-bottom: 20px;
  border-radius: 8px;
  box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

h2 {
  margin-bottom: 15px;
  color: #333;
}

/* Inputs e selects */
input[type="text"],
input[type="number"],
input[type="date"],
select {
  width: 70%;
  padding: 8px;
  margin: 6px 0 15px 0;
  border: 1px solid #ccc;
  border-radius: 4px;
}

/* Botão de envio */
input[type="submit"], button {
  background-color: #28a745;
  color: white;
  border: none;
  padding: 10px 20px;
  border-radius: 5px;
  cursor: pointer;
  transition: background-color 0.3s;
}

input[type="submit"]:hover, button:hover {
  background-color: #218838;
}

/* Tabela */
table {
  width: 70%;
  border-collapse: collapse;
  margin-top: 10px;
}

th, td {
  border: 1px solid #ddd;
  padding: 10px;
  text-align: left;
}

th {
  background-color: #007BFF;
  color: white;
}

tr:nth-child(even) {
  background-color: #f2f2f2;
}

.container {
  display: flex;
}

.container-content {
  margin: auto;
}

/* Responsividade básica */
@media (max-width: 600px) {
  nav a {
    display: block;
    margin-bottom: 10px;
  }
  * {
    margin: auto;
  }
}


</style>
</head>
<body>
    <nav>
        <a onclick="mostrar('cadastrar_cliente')">Cadastrar Cliente</a>
        <a onclick="mostrar('cadastrar_venda')">Cadastrar Venda</a>
        <a onclick="mostrar('listar_vendas')">Listar Vendas</a>
        <a onclick="mostrar('listar_brindes')">Listar Brindes</a>
        <a href="../index.html">Início</a>
    </nav>
<div class="container">
    <div class="container-content">
    <div id="cadastrar_cliente" class="section">
        <h2>Cadastrar Cliente</h2>
        <form method="POST">
            <label>Nome:</label><br>
            <input type="text" name="nome_cliente" required><br><br>
            <button type="submit">Cadastrar Cliente</button>
        </form>
    </div>

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

    <div id="listar_vendas" class="section">
        <h2>Vendas Cadastradas</h2>
        <label>Selecionar Cliente:</label><br>
        <select id="cliente_filtro" onchange="carregarVendas()">
            <option value="">-- Todos os Clientes --</option>
            <?php foreach ($clientes as $cliente): ?>
                <option value="<?= $cliente['id']; ?>"><?= $cliente['nome']; ?></option>
            <?php endforeach; ?>
        </select><br><br>
        <div id="vendas_table"></div>
    </div>

    <div id="listar_brindes" class="section">
        <h2>Brindes Concedidos</h2>
        <label>Selecionar Cliente:</label><br>
        <select id="cliente_filtro_brindes" onchange="carregarBrindes()">
            <option value="">-- Todos os Clientes --</option>
            <?php foreach ($clientes as $cliente): ?>
                <option value="<?= $cliente['id']; ?>"><?= $cliente['nome']; ?></option>
            <?php endforeach; ?>
        </select><br><br>
        <div id="brindes_table"></div>
    </div>
</div>
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

function carregarBrindes() {
    const clienteId = document.getElementById('cliente_filtro_brindes').value;
    const xhr = new XMLHttpRequest();
    xhr.open('POST', 'fidelidade.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
            document.getElementById('brindes_table').innerHTML = xhr.responseText;
        }
    };
    xhr.send('cliente_filtro_brindes=' + clienteId);
}
</script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    mostrar('cadastrar_venda');
});
</script>

</body>
</html>
