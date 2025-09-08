<?php
include '../php/databaseconfig.php';
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="../src/favicon.ico" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    <title>Sistema Mega-Xerox - Imprimir Ordem de Entrada</title>
    <style>
        /* Estilos do layout compacto (baseado no formulário) */
        body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol"; font-size: 14px; background-color: #f4f4f4; }
        .ordemdeentrada { max-width: 800px; margin: 20px auto; background-color: #fff; padding: 20px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        
        header { display: flex; justify-content: space-between; align-items: flex-start; border-bottom: 2px solid #333; padding-bottom: 10px; margin-bottom: 15px; }
        .header-left p, .header-left h2 { margin: 2px 0; }
        .header-right { text-align: right; }
        .header-right h3, .header-right p { margin: 0; }
        .header-right p { margin-top: 30px; }

        fieldset { border: none; padding: 0; }
        
        .form-row { display: flex; gap: 15px; width: 100%; }
        
        .data-fields { display: flex; justify-content: space-between; border: 1px solid #ccc; padding: 8px; border-radius: 4px; margin-bottom: 12px; }
        .data-fields b { margin-right: 5px; }

        .dispositivo { border: 1px solid #e0e0e0; padding: 15px; margin-bottom: 10px; border-radius: 5px; }
        
        .display-field {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
            font-size: 14px;
            margin-bottom: 12px;
            background-color: #f8f9fa; /* Fundo cinza claro para indicar campo de visualização */
            min-height: 38px; /* Altura similar a um input */
            line-height: 1.5;
        }

        .termos-servico { margin: 15px 0; padding: 15px; border: 2px solid #000; border-radius: 5px; background-color: #f5f5f5; }
        .termos-servico p { margin: 0 0 10px 0; font-weight: bold; text-align: center; font-size: 16px; }
        .termos-servico ol { margin: 0; padding-left: 20px; font-size: 12px; }
        .termos-servico li { margin-bottom: 8px; line-height: 1.4; font-weight: bold;}

        .botoes { text-align: right; margin-top: 20px; }
        .imprimir, .navegar { padding: 10px 15px; border-radius: 5px; text-decoration: none; border: none; cursor: pointer; font-size: 14px; }
        .navegar { background-color: #6c757d; color: white; }
        .imprimir { background-color: #007bff; color: white; margin-left: 10px; }

        /* Estilos específicos para impressão */
        @media print {
            body { background-color: #fff; }
            .ordemdeentrada { box-shadow: none; margin: 0; max-width: 100%; }
            .botoes { display: none; }
        }
    </style>
</head>
<body>
<?php
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    // Consulta para buscar os dados da ordem de serviço específica e dos dispositivos associados
    $sql = "SELECT * FROM sistemaloja.entryorder WHERE id = $id";
    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
?>
    <div class="ordemdeentrada">
        <header>
            <div class="header-left">
                <h2>Mega Xerox</h2>
                <p>Cnpj: 12.689.177/0001-26</p>
                <p>Whatsapp: 2665-5910</p>
            </div>
            <div class="header-right">
                <h3>Ordem de Entrada Nº <?php echo htmlspecialchars($row['id']); ?></h3>
                <p>Ass.____________________________________</p>
            </div>
        </header>

        <div class="termos-servico">
            <p>TERMOS DE GARANTIA E CONDIÇÕES DE DEPÓSITO</p>
            <ol>
                <li>Após 7 (sete) dias da comunicação do orçamento (aprovado ou não), o equipamento deve ser retirado. Não ocorrendo a retirada no prazo, será cobrada uma taxa diária de R$ 10,00 (dez reais) a título de armazenamento.</li>
                <li>Conforme o Código Civil (arts. 643, 644 e 1.275) e o Código de Defesa do Consumidor, o cliente declara estar ciente e de acordo que, caso não retire o aparelho em até 30 (trinta) dias, o equipamento será considerado abandonado, podendo o prestador adotar medidas cabíveis para ressarcir os custos de serviço e armazenagem, inclusive mediante descarte ou venda do bem.</li>
                <li>A garantia é limitada exclusivamente ao defeito reparado. O prazo de garantia segue o artigo 26, inciso II, do Código de Defesa do Consumidor, sendo de 90 (noventa) dias para serviços prestados em produtos duráveis. A garantia não cobre mau uso, quedas, oxidação, violação do lacre ou defeitos diferentes do reparado.</li>
            </ol>
        </div>

        <fieldset>
            <div class="display-field"><b>Nome do Cliente:</b> <?php echo htmlspecialchars($row['nome']); ?></div>
            <div class="display-field"><b>Telefone / Whatsapp:</b> <?php echo htmlspecialchars($row['telefone']); ?></div>

            <div class="data-fields">
                <div><b>Data de Entrada:</b> <span id="dataFormatada"></span></div>
                <span id="dataOriginal" style="display: none;"><?php echo $row['data_entrada']; ?></span>
            </div>
            
            <div class="dispositivo">
                <div class="display-field"><b>Tipo de Dispositivo:</b> <?php echo htmlspecialchars($row['tipo']); ?></div>
                <div class="form-row">
                    <div class="display-field"><b>Marca:</b> <?php echo htmlspecialchars($row['marca']); ?></div>
                    <div class="display-field"><b>Modelo:</b> <?php echo htmlspecialchars($row['modelo']); ?></div>
                </div>
                <div class="display-field"><b>Periféricos/Acessórios:</b> <?php echo htmlspecialchars($row['perifericos']); ?></div>
                <div class="display-field"><b>Defeito Apresentado:</b><br><?php echo nl2br(htmlspecialchars($row['defeito'])); ?></div>
                <div class="display-field"><b>Orçamento Prévio:</b> <?php echo htmlspecialchars($row['preorc']); ?></div>
            </div>

            <div class="botoes">
                <a class="navegar" href="../index.html">Voltar</a>
                <button type="button" onclick="window.print()" class="imprimir">
                    <i class="fas fa-print"></i> Imprimir
                </button>
            </div>
        </fieldset>
    </div>
<?php
    } else {
        echo "<div class='ordemdeentrada'><p>Registro com ID " . htmlspecialchars($id) . " não encontrado.</p></div>";
    }
    mysqli_free_result($result);
} else {
    echo "<div class='ordemdeentrada'><p>Nenhum ID de ordem de serviço foi fornecido.</p></div>";
}
mysqli_close($conn);
?>
<script>
    function formatarDataBR(dataString) {
        if (!dataString) return '';
        const data = new Date(dataString);
        // Formata para DD/MM/AAAA HH:MM
        return new Intl.DateTimeFormat('pt-BR', {
            day: '2-digit',
            month: '2-digit',
            year: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        }).format(data);
    }

    document.addEventListener('DOMContentLoaded', function() {
        const dataOriginalEl = document.getElementById('dataOriginal');
        if (dataOriginalEl) {
            const dataFormatada = formatarDataBR(dataOriginalEl.innerText);
            document.getElementById('dataFormatada').innerText = dataFormatada;
        }
    });
</script>
</body>
</html>
