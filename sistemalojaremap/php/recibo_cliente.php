<?php
require 'databaseconfig.php';

// Pega o ID do cliente da URL e garante que seja um inteiro
$id_cliente = isset($_GET['id_cliente']) ? intval($_GET['id_cliente']) : 0;

$vendas = [];
$cliente_nome = ''; // Variável para armazenar o nome do cliente
$total = 0.00;
$safe_cliente_nome = 'recibo'; // Nome padrão para o arquivo

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
            // Cria um nome de arquivo seguro a partir do nome do cliente
            $safe_cliente_nome = 'recibo_' . preg_replace('/[^a-zA-Z0-9_]/', '', str_replace(' ', '_', strtolower($cliente_nome)));
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recibo - <?php echo htmlspecialchars($cliente_nome); ?></title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #005f73;
            margin: 0;
            padding: 20px;
            color: #333;
        }
        .content {
            padding: 20px;
            max-width: 800px;
            margin: 0 auto;
            background-color: white;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 5px;
        }
        h1, h2 {
            color: #007bff;
            text-align: center;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
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
        tfoot th {
            text-align: right;
        }
        button {
            background-color: #28a745; /* Cor verde para salvar */
            color: white;
            border: none;
            padding: 10px 15px;
            margin-top: 20px;
            border-radius: 4px;
            cursor: pointer;
            display: block;
            width: 100%;
            font-size: 16px;
        }
        button:hover {
            background-color: #218838;
        }
        .recibo-header, .recibo-footer {
            text-align: center;
            margin-bottom: 20px;
        }
        .recibo-header h1 {
            margin: 0;
            color: #333;
        }
        .cliente-info h2 {
            text-align: left;
            border-bottom: 2px solid #eee;
            padding-bottom: 10px;
        }
    </style>
</head>
<body>

    <div id="recibo-para-salvar" class="content">
        <div class="recibo-header">
            <h1>Mega Xerox</h1>
            <p>Recibo de Vendas</p>
        </div>

        <div class="cliente-info">
            <h2>Cliente: <?php echo htmlspecialchars($cliente_nome); ?></h2>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Data</th>
                    <th>Descrição</th>
                    <th style="text-align: right;">Valor</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($vendas)): ?>
                    <?php foreach ($vendas as $venda): ?>
                        <tr>
                            <td><?php echo date("d/m/Y", strtotime($venda['data_venda'])); ?></td>
                            <td><?php echo htmlspecialchars($venda['descricao']); ?></td>
                            <td style="text-align: right;">R$ <?php echo number_format($venda['valor_total'], 2, ',', '.'); ?></td>
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
                    <th colspan="2">Total Geral:</th>
                    <th style="text-align: right;">R$ <?php echo number_format($total, 2, ',', '.'); ?></th>
                </tr>
            </tfoot>
        </table>

        <div class="recibo-footer">
            <p>Obrigado pela sua preferência!</p>
            <p>Data de emissão: <?php echo date("d/m/Y H:i:s"); ?></p>
        </div>
    </div>
    
    <div class="content" style="background: none; box-shadow: none; padding-top: 0;">
         <button id="btn-salvar-imagem">Salvar como Imagem</button>
    </div>

    <script>
        // Adiciona o evento de clique ao botão
        document.getElementById('btn-salvar-imagem').addEventListener('click', function() {
            // Seleciona a div que contém o recibo
            const elementoRecibo = document.getElementById('recibo-para-salvar');
            
            // Usa html2canvas para capturar o elemento
            html2canvas(elementoRecibo, {
                scale: 2 // Aumenta a escala para uma imagem de melhor qualidade
            }).then(canvas => {
                // Cria um link temporário
                const link = document.createElement('a');
                // Define o nome do arquivo para download usando o nome do cliente
                link.download = '<?php echo $safe_cliente_nome; ?>.png';
                // Converte o canvas para uma imagem PNG e a define como o href do link
                link.href = canvas.toDataURL('image/png');
                // Aciona o clique no link para iniciar o download
                link.click();
            });
        });
    </script>

</body>
</html>
