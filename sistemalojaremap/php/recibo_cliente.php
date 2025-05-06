
<?php
/*
 https://loja-megaxerox.qtgmyu.easypanel.host/php/recibo_cliente.php?cliente=Luis%20Sindicato
*/
require 'databaseconfig.php';

$cliente = isset($_GET['cliente']) ? trim($_GET['cliente']) : '';
$vendas = [];
$total = 0.00;

// Consulta ao banco de dados
if ($cliente !== '') {
    $stmt = $conn->prepare("
        SELECT c.nome, v.descricao, v.data_venda, v.valor_total 
        FROM vendas v 
        INNER JOIN clientes c ON v.id_cliente = c.id_cliente 
        WHERE c.nome = ? 
        ORDER BY v.data_venda
    ");
    $stmt->bind_param("s", $cliente);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $vendas[] = $row;
        $total += $row['valor_total'];
    }
    $stmt->close();
}
$conn->close();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Recibo de Vendas</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <style>
        body { font-family: Arial, sans-serif; text-align: center; }
        .container { width: 148mm; margin: auto; padding: 20px; border: 2px solid black; }
        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px; }
        .logo { background: yellow; border-radius: 50%; width: 270px; height: 100px; display: flex; justify-content: center; align-items: center; font-size: 18px; font-weight: bold; }
        .header-text { font-weight: bold; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid black; padding: 6px; text-align: left; font-size: 14px; }
        th { background-color: #f0f0f0; }
        .total-box { margin-top: 10px; font-size: 18px; font-weight: bold; text-align: right; }
        button { margin-top: 20px; padding: 10px 20px; font-size: 16px; }
    </style>
</head>
<body>

<div class="container" id="capture">
    <div class="header">
        <div class="logo">Mega Xerox<br>Tel.: (22) 2665-5910</div>
        <div class="header-text">
            Rua Bernardo Vasconcelos 293 - sala 4A - Centro<br>
            CNPJ: 12689.177/0001-26 - Araruama - RJ
        </div>
    </div>

    <h2>Recibo de Vendas - <?= htmlspecialchars($cliente) ?></h2>

    <table>
        <tr>
            <th>Data</th>
            <th>Descrição</th>
            <th>Valor (R$)</th>
        </tr>
        <?php if (count($vendas) > 0): ?>
            <?php foreach ($vendas as $venda): ?>
                <tr>
                    <td><?= date('d/m/Y', strtotime($venda['data_venda'])) ?></td>
                    <td><?= htmlspecialchars($venda['descricao']) ?></td>
                    <td>R$ <?= number_format($venda['valor_total'], 2, ',', '.') ?></td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr><td colspan="3">Nenhuma venda encontrada.</td></tr>
        <?php endif; ?>
    </table>

    <div class="total-box">
        Total Geral: R$ <?= number_format($total, 2, ',', '.') ?>
    </div>
</div>

<?php if (count($vendas) > 0): ?>
<button onclick="gerarImagem()">Salvar como Imagem</button>
<?php endif; ?>

<script>
function gerarImagem() {
    html2canvas(document.querySelector("#capture")).then(canvas => {
        let link = document.createElement("a");
        link.href = canvas.toDataURL("image/png");
        link.download = "recibo_<?= urlencode($cliente) ?>.png";
        link.click();
    });
}
</script>

</body>
</html>
