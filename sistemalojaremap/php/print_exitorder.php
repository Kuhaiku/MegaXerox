<?php
include_once('databaseconfig.php');
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="icon" type="image/x-icon" href="../src/favicon.ico" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    <title>Sistema Mega-Xerox - Comprovante de Saída</title>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol"; font-size: 14px; background-color: #f4f4f4; }
        .ordemdeentrada { max-width: 800px; margin: 20px auto; background-color: #fff; padding: 20px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        header { display: flex; justify-content: space-between; align-items: flex-start; border-bottom: 2px solid #333; padding-bottom: 10px; }
        .header-left p, .header-left h2 { margin: 2px 0; }
        .header-right { text-align: right; }
        .header-right h3, .header-right p { margin: 0; }
        .header-right p { margin-top: 30px; }
        fieldset { border: none; padding: 10px 0; }
        .form-row { display: flex; gap: 15px; width: 100%; }
        .inputBox { margin-bottom: 12px; width: 100%; }
        .info-field { width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; font-size:14px; background-color: #e9ecef; min-height: 35px; line-height: 1.5; }
        textarea.info-field { height: auto; resize: none; }
        .data-fields { display: flex; justify-content: space-between; border: 1px solid #ccc; padding: 8px; border-radius: 4px; margin-bottom: 12px; }
        .data-fields b { margin-right: 5px; }
        .dispositivo { border: 1px solid #e0e0e0; padding: 15px; margin-bottom: 10px; border-radius: 5px; }
        .termos-servico { margin: 15px 0; padding: 15px; border: 2px solid #000; border-radius: 5px; background-color: #f5f5f5; }
        .termos-servico p { margin: 0 0 10px 0; font-weight: bold; text-align: center; font-size: 16px; }
        .termos-servico ol { margin: 0; padding-left: 20px; font-size: 12px; }
        .termos-servico li { margin-bottom: 8px; line-height: 1.4; }
        .botoes { text-align: right; margin-top: 20px; }
        button, .navegar { padding: 10px 15px; border-radius: 5px; text-decoration: none; border: none; cursor: pointer; font-size: 14px; }
        .navegar { background-color: #6c757d; color: white; }
        button { background-color: #007bff; color: white; margin-left: 10px; }
        @media print {
            body { background-color: #fff; }
            .ordemdeentrada { box-shadow: none; margin: 0; }
            .botoes { display: none; }
        }
    </style>
</head>
<body>

<?php
if (isset($_GET['id'])) {
    $id_saida = intval($_GET['id']);
    $dados_completos = null;

    // CORREÇÃO: Unindo os dados da 'exit_notes' com a 'entryorder'
    $sql = "SELECT 
                en.id as id_saida, en.data_saida, en.servico_realizado, 
                en.metodo_pagamento, en.valor, en.garantia, eo.* FROM exit_notes AS en
            JOIN entryorder AS eo ON en.entry_id = eo.id
            WHERE en.id = ?";

    if ($stmt = mysqli_prepare($conn, $sql)) {
        mysqli_stmt_bind_param($stmt, "i", $id_saida);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        if ($result) {
            $dados_completos = mysqli_fetch_assoc($result);
        }
        mysqli_stmt_close($stmt);
    }
    
    if ($dados_completos) {
?>
    <div class="ordemdeentrada">
        <header>
            <div class="header-left">
                <h2>Mega Xerox</h2>
                <p>Cnpj: 12.689.177/0001-26</p>
                <p>Whatsapp: 2665-5910</p>
            </div>
            <div class="header-right">
                <h3>Comprovante de Saída (Ref. Entrada Nº <?php echo htmlspecialchars($dados_completos['id']); ?>)</h3>
                <p>Ass.____________________________________</p>
            </div>
        </header>

        <div class="termos-servico">
            <p>TERMOS DE GARANTIA</p>
            <ol>
                <li>A garantia é limitada exclusivamente ao defeito reparado e válida até <b><?php echo htmlspecialchars($dados_completos['garantia']); ?></b>. O prazo segue o artigo 26, inciso II, do Código de Defesa do Consumidor.</li>
                <li>A garantia não cobre mau uso, quedas, oxidação, violação do lacre ou defeitos diferentes do reparado.</li>
            </ol>
        </div>
        
        <fieldset>
            <div class="inputBox"><div class="info-field"><b>Cliente:</b> <?php echo htmlspecialchars($dados_completos['nome']); ?></div></div>
            <div class="inputBox"><div class="info-field"><b>Telefone:</b> <?php echo htmlspecialchars($dados_completos['telefone']); ?></div></div>
            
            <div class="data-fields">
                <div><b>Data de Entrada:</b> <?php echo date('d/m/Y H:i', strtotime($dados_completos['data_entrada'])); ?></div>
                <div><b>Data de Saída:</b> <?php echo date('d/m/Y', strtotime($dados_completos['data_saida'])); ?></div>
            </div>

            <div class="dispositivo">
                <div class="form-row">
                    <div class="inputBox"><div class="info-field"><b>Tipo:</b> <?php echo htmlspecialchars($dados_completos['tipo']); ?></div></div>
                    <div class="inputBox"><div class="info-field"><b>Marca:</b> <?php echo htmlspecialchars($dados_completos['marca']); ?></div></div>
                    <div class="inputBox"><div class="info-field"><b>Modelo:</b> <?php echo htmlspecialchars($dados_completos['modelo']); ?></div></div>
                </div>
                 <div class="inputBox"><div class="info-field"><b>Periféricos:</b> <?php echo htmlspecialchars($dados_completos['perifericos']); ?></div></div>
                <div class="inputBox"><textarea class="info-field" readonly>Defeito Apresentado: <?php echo htmlspecialchars($dados_completos['defeito']); ?></textarea></div>
                <hr>
                <div class="inputBox"><textarea class="info-field" readonly>Serviço Realizado: <?php echo htmlspecialchars($dados_completos['servico_realizado']); ?></textarea></div>
                 <div class="inputBox"><div class="info-field"><b>Pagamento:</b> <?php echo htmlspecialchars($dados_completos['metodo_pagamento']); ?> | <b>Valor Total: R$ <?php echo number_format($dados_completos['valor'], 2, ',', '.'); ?></b></div></div>
            </div>
        </fieldset>
        
        <div class="botoes">
            <a class="navegar" href="../index.html">Voltar</a>
            <button type="button" onclick="window.print()">
                <i class="fas fa-print"></i> Imprimir
            </button>
        </div>
    </div>
<?php
    } else {
        echo "<div class='ordemdeentrada'><p>Erro: Registro de saída não encontrado ou inconsistente.</p></div>";
    }
} else {
    echo "<div class='ordemdeentrada'><p>Erro: ID não fornecido.</p></div>";
}
mysqli_close($conn);
?>
</body>
</html>
