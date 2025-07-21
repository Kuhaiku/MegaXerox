<?php
require 'databaseconfig.php';

// Pega o ID do cliente da URL e garante que seja um inteiro
$id_cliente = isset($_GET['id_cliente']) ? intval($_GET['id_cliente']) : 0;

$vendas = [];
$cliente_nome = ''; // Variável para armazenar o nome do cliente
$total = 0.00;

// Consulta ao banco de dados apenas se o ID do cliente for válido
if ($id_cliente > 0) {
    // Prepara a consulta para evitar injeção de SQL
    $stmt = $conn->prepare("
        SELECT c.nome, v.descricao, v.data_venda, v.valor_total
        FROM vendas v
        INNER JOIN clientes c ON v.id_cliente = c.id_cliente
        WHERE v.id_cliente = ?
        ORDER BY v.data_venda DESC
    ");
    $stmt->bind_param("i", $id_cliente); // "i" para inteiro
    $stmt->execute();
    $result = $stmt->get_result();

    // Busca os resultados
    while ($row = $result->fetch_assoc()) {
        $vendas[] = $row;
        $total += $row['valor_total'];
        // Pega o nome do cliente do primeiro resultado encontrado
        if (empty($cliente_nome)) {
            $cliente_nome = $row['nome'];
        }
    }
    $stmt->close();
}
$conn->close();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Recibo do Cliente - Mega Xerox</title>
    <link rel="stylesheet" href="../css/recibo.css">
    <style>
        /* Adicione estilos de impressão aqui para garantir que o recibo seja impresso corretamente */
        @media print {
            body {
                font-family: 'Courier New', Courier, monospace;
            }
            .no-print {
                display: none;
            }
            .recibo {
                box-shadow: none;
                border: 1px solid #ccc;
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="recibo">
        <header>
            <h1>Mega Xerox</h1>
            <p>Recibo de Vendas</p>
        </header>
        <section class="cliente-info">
            <h2>Cliente: <?php echo htmlspecialchars($cliente_nome); ?></h2>
        </section>
        <section class="vendas-info">
            <table>
                <thead>
                    <tr>
                        <th>Data</th>
                        <th>Descrição</th>
                        <th>Valor</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($vendas)): ?>
                        <?php foreach ($vendas as $venda): ?>
                            <tr>
                                <td><?php echo date("d/m/Y", strtotime($venda['data_venda'])); ?></td>
                                <td><?php echo htmlspecialchars($venda['descricao']); ?></td>
                                <td>R$ <?php echo number_format($venda['valor_total'], 2, ',', '.'); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="3">Nenhuma venda encontrada para este cliente.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="2">Total Geral</th>
                        <th>R$ <?php echo number_format($total, 2, ',', '.'); ?></th>
                    </tr>
                </tfoot>
            </table>
        </section>
        <footer>
            <p>Obrigado pela sua preferência!</p>
            <p>Data de emissão: <?php echo date("d/m/Y H:i:s"); ?></p>
        </footer>
    </div>
    <div class="no-print" style="text-align: center; margin-top: 20px;">
        <button onclick="window.print()">Imprimir Recibo</button>
    </div>
</body>
</html>
