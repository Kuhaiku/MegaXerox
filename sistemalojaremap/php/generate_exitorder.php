<?php
include_once('databaseconfig.php'); // Verifique se o caminho para seu arquivo de conexão está correto
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="icon" type="image/x-icon" href="../src/favicon.ico" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    <title>Sistema Mega-Xerox - Ordem de Saída</title>
    <style>
        /* Estilos do layout de entrada aplicados aqui */
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
        .inputUser { width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; font-size:14px; }
        .inputUser[readonly] { background-color: #e9ecef; cursor: not-allowed; }

        .data-fields { display: flex; justify-content: space-between; border: 1px solid #ccc; padding: 8px; border-radius: 4px; margin-bottom: 12px; }
        .data-fields b { margin-right: 5px; }

        .dispositivo { border: 1px solid #e0e0e0; padding: 15px; margin-bottom: 10px; border-radius: 5px; }
        
        .tipodispositivo { margin-bottom: 15px; padding: 8px; background-color: #f8f9fa; border: 1px solid #dee2e6; border-radius: 4px; }

        textarea.inputUser { height: 60px; }

        .termos-servico { margin: 15px 0; padding: 15px; border: 2px solid #000; border-radius: 5px; background-color: #f5f5f5; }
        .termos-servico p { margin: 0 0 10px 0; font-weight: bold; text-align: center; font-size: 16px; }
        .termos-servico ol { margin: 0; padding-left: 20px; font-size: 12px; }
        .termos-servico li { margin-bottom: 8px; line-height: 1.4; }

        .botoes { text-align: right; margin-top: 20px; }
        button, .navegar { padding: 10px 15px; border-radius: 5px; text-decoration: none; border: none; cursor: pointer; font-size: 14px; }
        .navegar { background-color: #6c757d; color: white; }
        #gerarOrdem { background-color: #28a745; color: white; margin-left: 10px; }
    </style>
</head>
<body>

<?php
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $sql = "SELECT * FROM entryorder WHERE id = ?"; 
    
    if ($stmt = mysqli_prepare($conn, $sql)) {
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($row = mysqli_fetch_assoc($result)) {
?>
    <div class="ordemdeentrada">
        <header>
            <div class="header-left">
                <h2>Mega Xerox</h2>
                <p>Cnpj: 12.689.177/0001-26</p>
                <p>Whatsapp: 2665-5910</p>
            </div>
            <div class="header-right">
                <h3>Ordem de Saída Nº <?php echo htmlspecialchars($row['id']); ?></h3>
                <p>Ass.____________________________________</p>
            </div>
        </header>

        <div class="termos-servico">
            <p>TERMOS DE GARANTIA E CONDIÇÕES DE DEPÓSITO</p>
            <ol>
                <li>A garantia é limitada exclusivamente ao defeito reparado. O prazo de garantia segue o artigo 26, inciso II, do Código de Defesa do Consumidor, sendo de 90 (noventa) dias para serviços prestados em produtos duráveis.</li>
                <li>A garantia não cobre mau uso, quedas, oxidação, violação do lacre ou defeitos diferentes do reparado.</li>
            </ol>
        </div>

        <form id="saidaForm">
            <fieldset>
                <input type='hidden' name='entry_id' value='<?php echo htmlspecialchars($row['id']); ?>'>
                
                <div class="inputBox">
                    <input class="inputUser" type="text" value="Cliente: <?php echo htmlspecialchars($row['nome']); ?>" readonly />
                </div>
                <div class="inputBox">
                    <input class="inputUser" type="text" value="Telefone: <?php echo htmlspecialchars($row['telefone']); ?>" readonly />
                </div>

                <div class="data-fields">
                    <div><b>Data de Entrada:</b> <?php echo date('d/m/Y H:i', strtotime($row['data_entrada'])); ?></div>
                    <div><b>Data de Entrega:</b> <input type="date" name="data_saida" id="dataEntrega" required style="border:none; background:none;"></div>
                </div>
                
                <div class="dispositivo">
                    <div class="tipodispositivo">
                        <b>Dispositivo:</b> <?php echo htmlspecialchars($row['tipo']); ?> | 
                        <b>Marca:</b> <?php echo htmlspecialchars($row['marca']); ?> | 
                        <b>Modelo:</b> <?php echo htmlspecialchars($row['modelo']); ?>
                    </div>
                    <div class="inputBox">
                        <textarea class="inputUser" name="servico_realizado" id="servico_realizado" required placeholder="Serviço Realizado:"></textarea>
                    </div>
                    <div class="form-row">
                        <div class="inputBox">
                             <input class="inputUser" type="text" name="valor" id="valorServico" required placeholder="Valor Total (R$):" />
                        </div>
                        <div class="inputBox">
                            <select class="inputUser" name='metodo_pagamento' id='metodoPagamento' required>
                                <option value="" disabled selected>Método de Pagamento...</option>
                                <option value='Dinheiro'>Dinheiro</option>
                                <option value='Cartão de Crédito'>Cartão de Crédito</option>
                                <option value='Cartão de Débito'>Cartão de Débito</option>
                                <option value='Pix'>Pix</option>
                            </select>
                        </div>
                    </div>
                     <div class="inputBox">
                        <input class="inputUser" type="text" id="garantiaDisplay" readonly required placeholder="Garantia Válida até:">
                        <input type="hidden" name="garantia" id="garantiaValue">
                    </div>
                </div>
                
                <div class="botoes">
                    <button type="button" id="gerarOrdem">
                        <i class="fas fa-print"></i> Salvar e Imprimir
                    </button>
                </div>
            </fieldset>
        </form>
    </div>
<?php
        } else {
            echo "<div class='ordemdeentrada'><p>Erro: Registro de entrada não encontrado.</p></div>";
        }
        mysqli_stmt_close($stmt);
    }
} else {
    echo "<div class='ordemdeentrada'><p>Erro: ID da ordem de entrada não fornecido.</p></div>";
}
mysqli_close($conn);
?>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const dataEntregaInput = document.getElementById('dataEntrega');
    if(dataEntregaInput) {
        dataEntregaInput.value = new Date().toISOString().split('T')[0];

        function calcularGarantia() {
            // MUDANÇA 3: Selecionar os dois campos de garantia
            const garantiaDisplayInput = document.getElementById('garantiaDisplay');
            const garantiaValueInput = document.getElementById('garantiaValue');

            if (dataEntregaInput.value) {
                const dataEntrega = new Date(dataEntregaInput.value + 'T00:00:00');
                if (!isNaN(dataEntrega.getTime())) {
                    dataEntrega.setMonth(dataEntrega.getMonth() + 3);
                    const dataGarantia = dataEntrega.toLocaleDateString('pt-BR');
                    
                    // MUDANÇA 4: Atualizar os dois campos separadamente
                    garantiaDisplayInput.value = "Garantia Válida até: " + dataGarantia; // Campo visível
                    garantiaValueInput.value = dataGarantia; // Campo oculto (só a data)
                }
            }
        }

        calcularGarantia();
        dataEntregaInput.addEventListener('change', calcularGarantia);

        document.getElementById('gerarOrdem').addEventListener('click', function () {
            const form = document.getElementById('saidaForm');
            if (!form.checkValidity()) {
                alert('Por favor, preencha todos os campos obrigatórios.');
                form.reportValidity();
                return;
            }
            
            const formData = new FormData(form);

            fetch('../php/salvar_ordem_saida.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(data => {
                alert(data);
                if (data.toLowerCase().includes("sucesso")) {
                    window.print(); 
                }
            })
            .catch(error => {
                console.error('Erro ao salvar a ordem de saída:', error);
                alert('Ocorreu um erro ao tentar salvar. Verifique o console para mais detalhes.');
            });
        });
    }
});
</script>

</body>
</html>
