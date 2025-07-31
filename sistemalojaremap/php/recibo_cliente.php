<?php
// Inclui o arquivo de configuração do banco de dados
require 'databaseconfig.php';

// Pega o ID do cliente da URL e garante que seja um inteiro. Se não houver, define como 0.
$id_cliente = isset($_GET['id_cliente']) ? intval($_GET['id_cliente']) : 0;

// Inicializa as variáveis
$vendas = [];
$cliente_nome = '';
$total_geral = 0.00;
$safe_cliente_nome = 'recibo'; // Nome padrão para o arquivo de imagem

// Executa a consulta apenas se um ID de cliente válido for fornecido
if ($id_cliente > 0) {
    // Prepara a consulta para buscar os dados das vendas e o nome do cliente
    $stmt = $conn->prepare("
        SELECT c.nome, v.descricao, v.data_venda, v.valor_total
        FROM vendas v
        INNER JOIN clientes c ON v.id_cliente = c.id_cliente
        WHERE v.id_cliente = ?
        ORDER BY v.data_venda ASC
    ");
    $stmt->bind_param("i", $id_cliente); // "i" indica que o parâmetro é um inteiro
    $stmt->execute();
    $result = $stmt->get_result();

    // Processa os resultados da consulta
    while ($row = $result->fetch_assoc()) {
        $vendas[] = $row;
        $total_geral += $row['valor_total'];
        
        // Pega o nome do cliente (só precisa fazer isso uma vez)
        if (empty($cliente_nome)) {
            $cliente_nome = $row['nome'];
            // Cria um nome de arquivo seguro para o download
            $safe_cliente_nome = 'recibo_' . preg_replace('/[^a-zA-Z0-9_]/', '', str_replace(' ', '_', strtolower($cliente_nome)));
        }
    }
    $stmt->close();
}
$conn->close();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recibo - <?php echo htmlspecialchars($cliente_nome); ?></title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <style>
        body { 
            font-family: Arial, sans-serif; 
            text-align: center; 
            background-color: #f0f0f0;
            padding: 20px;
        }
        .container { 
            width: 148mm; 
            min-height: 210mm; /* Usa min-height para se ajustar ao conteúdo */
            margin: auto; 
            padding: 20px; 
            border: 2px solid black; 
            background-color: white;
            display: flex; 
            flex-direction: column; 
            align-items: center; 
        }
        .header { 
            display: flex; 
            align-items: center; 
            justify-content: space-between; 
            width: 100%; 
            margin-bottom: 10px; 
        }
        .logo { 
            width: 270px; 
            height: 100px; 
            border-radius: 10px; 
            border: 1px solid #ccc;
            display: flex; 
            align-items: center; 
            justify-content: center; 
            font-weight: bold; 
            font-size: 20px; 
            text-align: center;
        }
        .header-text { 
            text-align: center; 
            font-weight: bold; 
        }
        .info-line { 
            display: flex; 
            justify-content: space-between; 
            width: 100%; 
            margin-bottom: 15px; 
            font-size: 18px;
            padding-bottom: 10px;
            border-bottom: 1px solid #ccc;
        }
        table { 
            width: 100%; 
            border-collapse: collapse; 
        }
        th, td { 
            border: 1px solid black; 
            padding: 5px; 
            text-align: center; 
            font-size: 12px;
        }
        th {
            background-color: #f2f2f2;
        }
        td {
             height: 25px; /* Altura fixa para cada linha */
        }
        /* Definição de largura das colunas */
        th:nth-child(1), td:nth-child(1) { width: 45%; } /* Descrição maior */
        th:nth-child(2), td:nth-child(2) { width: 20%; } /* Data */
        th:nth-child(3), td:nth-child(3) { width: 15%; } /* Quantidade */
        th:nth-child(4), td:nth-child(4) { width: 20%; } /* Total */

        .total-box { 
            text-align: right; 
            margin-top: 10px; 
            font-size: 20px; 
            font-weight: bold; 
            width: 100%;
            padding-right: 5px;
        }
        button {
            background-color: #28a745;
            color: white;
            border: none;
            padding: 12px 20px;
            margin-top: 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            width: calc(148mm + 44px); /* Largura do recibo + padding */
        }
        button:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>

<div class="container" id="recibo-para-salvar">
    <div class="header">
        <div class="logo">Mega Xerox<br>Tel.: (22) 2665-5910</div>
        <div class="header-text">
            <p>Rua Bernardo Vasconcelos 293 - sala 4A - Centro</p>
            <p>CNPJ: 12.689.177/0001-26 Araruama - RJ</p>
        </div>
    </div>
    <div class="info-line">
        <span><b>Cliente:</b> <?php echo htmlspecialchars($cliente_nome); ?></span>
        <span><b>Data:</b> <?php echo date("d/m/Y"); ?></span>
    </div>
    <table>
        <thead>
            <tr>
                <th>Descrição</th>
                <th>Data da Venda</th>
                <th>Quantidade</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($vendas)): ?>
                <?php foreach ($vendas as $venda): ?>
                    <tr>
                        <td style="text-align: left; padding-left: 5px;"><?php echo htmlspecialchars($venda['descricao']); ?></td>
                        <td><?php echo date("d/m/Y", strtotime($venda['data_venda'])); ?></td>
                        <td>1</td>
                        <td>R$ <?php echo number_format($venda['valor_total'], 2, ',', '.'); ?></td>
                    </tr>
                <?php endforeach; ?>
                
                <?php 
                // Preenche com linhas vazias para manter um layout consistente (total de 15 linhas)
                $linhas_a_preencher = 15 - count($vendas);
                if ($linhas_a_preencher > 0) {
                    for ($i = 0; $i < $linhas_a_preencher; $i++) {
                        echo '<tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>';
                    }
                }
                ?>

            <?php else: ?>
                <tr>
                    <td colspan="4">Nenhuma venda encontrada para este cliente.</td>
                </tr>
                 <?php 
                // Preenche com 15 linhas vazias se não houver vendas
                for ($i = 0; $i < 15; $i++) {
                    echo '<tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>';
                }
                ?>
            <?php endif; ?>
        </tbody>
    </table>
    <div class="total-box">
        Total: R$ <?php echo number_format($total_geral, 2, ',', '.'); ?>
    </div>
</div>

<button onclick="gerarImagem()">Salvar como Imagem</button>

<script>
    function gerarImagem() {
        // Seleciona o container do recibo
        const elementoRecibo = document.getElementById('recibo-para-salvar');
        
        // Usa html2canvas para capturar o elemento
        html2canvas(elementoRecibo, {
            scale: 2 // Aumenta a escala para uma imagem de melhor qualidade
        }).then(canvas => {
            // Cria um link temporário para o download
            const link = document.createElement('a');
            
            // Define o nome do arquivo usando a variável segura do PHP
            link.download = '<?php echo $safe_cliente_nome; ?>.png';
            
            // Converte o canvas para imagem PNG e define como href do link
            link.href = canvas.toDataURL('image/png');
            
            // Clica no link para iniciar o download
            link.click();
        });
    }
</script>

</body>
</html>
