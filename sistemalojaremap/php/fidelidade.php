<?php
include('databaseconfig.php');

// Inserção de cliente
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['nome'])) {
    $nome = trim($_POST['nome']);
    if (!empty($nome)) {
        $conn->query("INSERT INTO cliente_fidelidade (nome) VALUES ('$nome')");
    }
}

// Inserção de venda + controle de brinde
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cliente_id'])) {
    $cliente_id = $_POST['cliente_id'];
    $unidade = (int)$_POST['unidade'];
    $descricao = $conn->real_escape_string($_POST['descricao']);
    $valor = floatval(str_replace(',', '.', $_POST['valor']));
    $data = $_POST['data'];

    // Inserir venda normal
    $conn->query("INSERT INTO vendas_fidelidade (cliente_id, unidade, descricao, valor_compra, data_compra)
                 VALUES ($cliente_id, $unidade, '$descricao', $valor, '$data')");

    // Total de unidades do cliente
    $result = $conn->query("SELECT SUM(unidade) as total_unidades FROM vendas_fidelidade 
                            WHERE cliente_id = $cliente_id AND descricao != 'Brinde'");
    $data_unidade = $result->fetch_assoc();
    $total_unidades = (int)$data_unidade['total_unidades'];

    // Verificar se atingiu 5 ou mais unidades
    while ($total_unidades >= 5) {
        $conn->query("INSERT INTO vendas_fidelidade (cliente_id, unidade, descricao, valor_compra, data_compra)
                     VALUES ($cliente_id, 0, 'Brinde', 0, CURDATE())");
        $total_unidades -= 5;
        // Subtrai 5 unidades do total (já entregou o brinde)
    }

    // Atualizar unidades restantes (opcional, só usado para exibição abaixo)
    $unidades_faltando = 5 - $total_unidades;
}

// Carrega clientes
$clientes = $conn->query("SELECT * FROM cliente_fidelidade");

// Lista vendas de um cliente específico
$vendas = [];
$unidades_cliente = 0;
$unidades_faltando = 0;

if (isset($_GET['cliente_id'])) {
    $cliente_id = $_GET['cliente_id'];
    $vendas_result = $conn->query("SELECT * FROM vendas_fidelidade WHERE cliente_id = $cliente_id ORDER BY data_compra DESC");
    while ($row = $vendas_result->fetch_assoc()) {
        $vendas[] = $row;
        if ($row['descricao'] != 'Brinde') {
            $unidades_cliente += $row['unidade'];
        }
    }
    $brindes_recebidos = floor($unidades_cliente / 5);
    $unidades_faltando = 5 - ($unidades_cliente % 5);
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Programa de Fidelidade</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <a href="../index.html">Início</a>
    <h1>Programa de Fidelidade</h1>

    <nav>
        <button onclick="mostrarSecao('cadastroVenda')">Cadastrar Venda</button>
        <button onclick="mostrarSecao('cadastroCliente')">Cadastrar Cliente</button>
        <button onclick="mostrarSecao('listarVendas')">Listar Vendas</button>
    </nav>

    <!-- Cadastrar Venda -->
    <section id="cadastroVenda">
        <h2>Cadastrar Nova Venda</h2>
        <form method="POST" action="">
            <label>Cliente:</label>
            <select name="cliente_id" required>
                <option value="">Selecione</option>
                <?php while ($c = $clientes->fetch_assoc()): ?>
                    <option value="<?= $c['id'] ?>"><?= htmlspecialchars($c['nome']) ?></option>
                <?php endwhile; ?>
            </select>

            <label>Unidade:</label>
            <input type="number" name="unidade" min="1" required>

            <label>Descrição:</label>
            <input type="text" name="descricao" required>

            <label>Valor:</label>
            <input type="text" name="valor" placeholder="Ex: 50,00" required>

            <label>Data:</label>
            <input type="date" name="data" required>

            <input type="submit" value="Cadastrar Venda">
        </form>
    </section>

    <!-- Cadastrar Cliente -->
    <section id="cadastroCliente" style="display: none;">
        <h2>Cadastrar Novo Cliente</h2>
        <form method="POST" action="">
            <label>Nome do Cliente:</label>
            <input type="text" name="nome" required>
            <input type="submit" value="Cadastrar Cliente">
        </form>
    </section>

    <!-- Listar Vendas -->
    <section id="listarVendas" style="display: none;">
        <h2>Listar Vendas por Cliente</h2>
        <form method="GET" action="">
            <label>Selecionar Cliente:</label>
            <select name="cliente_id" onchange="this.form.submit()">
                <option value="">Selecione</option>
                <?php
                $clientes->data_seek(0);
                while ($c = $clientes->fetch_assoc()):
                ?>
                    <option value="<?= $c['id'] ?>" <?= (isset($_GET['cliente_id']) && $_GET['cliente_id'] == $c['id']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($c['nome']) ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </form>

        <?php if (!empty($vendas)): ?>
            <h3>Compras do Cliente</h3>
            <table>
                <thead>
                    <tr>
                        <th>Unidade</th>
                        <th>Descrição</th>
                        <th>Valor</th>
                        <th>Data</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($vendas as $v): ?>
                        <tr>
                            <td><?= $v['unidade'] ?></td>
                            <td><?= htmlspecialchars($v['descricao']) ?></td>
                            <td>R$ <?= number_format($v['valor_compra'], 2, ',', '.') ?></td>
                            <td><?= date('d/m/Y', strtotime($v['data_compra'])) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php if ($unidades_faltando > 0): ?>
                <p><strong>Faltam <?= $unidades_faltando ?> unidade(s) para o próximo brinde.</strong></p>
            <?php else: ?>
                <p><strong>Brinde entregue! O contador foi reiniciado.</strong></p>
            <?php endif; ?>
        <?php elseif (isset($_GET['cliente_id'])): ?>
            <p>Nenhuma venda registrada para este cliente.</p>
        <?php endif; ?>
    </section>

    <script>
        // Mostrar seção e esconder outras
        function mostrarSecao(secaoId) {
            document.getElementById('cadastroVenda').style.display = 'none';
            document.getElementById('cadastroCliente').style.display = 'none';
            document.getElementById('listarVendas').style.display = 'none';
            document.getElementById(secaoId).style.display = 'block';
        }

        // Abrir diretamente a seção de cadastrar venda por padrão
        window.onload = function() {
            mostrarSecao('cadastroVenda');
        };
    </script>
</body>
</html>
