<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calculadora de Serviços</title>
    <link rel="stylesheet" href="../css/service_budget.css">
    <script src="https://cdn.jsdelivr.net/npm/html2canvas@1.0.0-rc.7/dist/html2canvas.min.js"></script>
    <script>
        function adicionarServico() {
            var servicos = document.getElementById('servicos');
            var servicoSelecionado = servicos.options[servicos.selectedIndex];
            var valor = parseFloat(servicoSelecionado.getAttribute('data-valor'));
            var descricao = servicoSelecionado.text;
            var quantidade = parseInt(document.getElementById('quantidade').value);

            if (isNaN(quantidade) || quantidade <= 0) {
                alert("Por favor, insira uma quantidade válida.");
                return;
            }

            var listaServicos = document.getElementById('listaServicos');
            var novoItem = document.createElement('li');
            novoItem.textContent = descricao + ' - R$' + (valor * quantidade).toFixed(2) + ' (' + quantidade + 'x)';
            listaServicos.appendChild(novoItem);

            var total = document.getElementById('total');
            total.value = (parseFloat(total.value) + (valor * quantidade)).toFixed(2);
            calcularTotalComDesconto();
        }

        function salvarOrcamento() {
            var orcamentoDiv = document.querySelector('.container');
            html2canvas(orcamentoDiv).then(canvas => {
                var link = document.createElement('a');
                link.href = canvas.toDataURL('image/png');
                link.download = 'orcamento.png';
                link.click();
            });
        }

        function formatarDataHora(data) {
            return data.toLocaleString('pt-BR', {
                day: '2-digit',
                month: '2-digit',
                year: 'numeric',
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit'
            });
        }

        function toggleDesconto() {
            var checkbox = document.getElementById('aplicarDesconto');
            var desconto = document.getElementById('desconto');
            if (checkbox.checked) {
                desconto.style.display = 'block';
                document.getElementById('valorDesconto').value = '';
            } else {
                desconto.style.display = 'none';
                document.getElementById('valorDesconto').value = '';
                calcularTotalComDesconto();
            }
        }

        function calcularTotalComDesconto() {
            var total = parseFloat(document.getElementById('total').value);
            var valorDesconto = parseFloat(document.getElementById('valorDesconto').value);
            if (isNaN(valorDesconto) || valorDesconto <= 0) {
                valorDesconto = 0;
            }
            var totalComDesconto = total - valorDesconto;
            document.getElementById('totalComDesconto').value = totalComDesconto.toFixed(2);
        }

        window.onload = function() {
            var dataHoraAtual = new Date();
            document.getElementById('dataHora').value = formatarDataHora(dataHoraAtual);
        }
    </script>
    <link rel="icon" type="image/x-icon" href="../src/favicon.ico" />
</head>
<body>
    <header>
        <a href="../index.html">Inicio</a>
    </header>
    <div class="container">
        <h1>Orçamento - Mega Xerox</h1>
        <div class="input-group">
            <label for="dataHora">Data e Hora:</label>
            <input type="text" id="dataHora" readonly>
        </div>
        <label for="servicos">Escolha um serviço:</label>
        <select id="servicos">
            <?php
            include 'databaseconfig.php';

            $sql = "SELECT * FROM service_dtbase";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo '<option value="' . $row["id"] . '" data-valor="' . $row["valor"] . '">' . $row["descricao"] . '</option>';
                }
            } else {
                echo '<option value="">Nenhum serviço disponível</option>';
            }

            $conn->close();
            ?>
        </select>
        <label for="quantidade">Quantidade:</label>
        <input type="number" id="quantidade" value="1" min="1">
        <button type="button" onclick="adicionarServico()">Adicionar Serviço</button>

        <h2>Serviços Selecionados</h2>
        <ul id="listaServicos"></ul>

        <h3>Sub-Total: R$<input type="text" id="total" value="0.00" readonly></h3>

        <div class="input-group">
            <input type="checkbox" id="aplicarDesconto" onclick="toggleDesconto()">
            <label for="aplicarDesconto"></label>
        </div>
        <div id="desconto">
            <label for="valorDesconto">Valor do Desconto:</label>
            <input type="number" id="valorDesconto" value="0" oninput="calcularTotalComDesconto()">
        </div>
        <div id="total-com-desconto">
            <h3>Total: R$<input type="text" id="totalComDesconto" value="0.00" readonly></h3>
        </div>

        <button type="button" onclick="salvarOrcamento()">Salvar Orçamento</button>
    </div>
</body>
</html>
