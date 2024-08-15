<?php
require 'databaseconfig.php';

$mensagem = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_cliente = isset($_POST['id_cliente']) ? intval($_POST['id_cliente']) : null;
    $descricao = isset($_POST['descricao']) ? $_POST['descricao'] : null;
    $data_venda = isset($_POST['data_venda']) ? $_POST['data_venda'] : null;
    $valor_total = isset($_POST['valor_total']) ? floatval($_POST['valor_total']) : null;

    if ($id_cliente && $descricao && $data_venda && $valor_total) {
        $stmt = $conn->prepare("INSERT INTO vendas (id_cliente, descricao, data_venda, valor_total) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("issd", $id_cliente, $descricao, $data_venda, $valor_total);
        
        if ($stmt->execute()) {
            $mensagem = "<p class='success-message'>Venda cadastrada com sucesso!</p>";
        } else {
            $mensagem = "<p class='error-message'>Erro ao cadastrar venda: " . $stmt->error . "</p>";
        }

        $stmt->close();
    } else {
        $mensagem = "<p class='error-message'>Todos os campos são obrigatórios.</p>";
    }
}

$conn->close();
?>

<form action="" method="POST">
    <label for="id_cliente">Cliente:</label>
    <select id="id_cliente" name="id_cliente" required>
        <option value="">Selecione um Cliente</option>
        <?php
        // Código para buscar clientes do banco de dados
        require 'databaseconfig.php';
        $result = $conn->query("SELECT id_cliente, nome FROM clientes");
        while ($row = $result->fetch_assoc()) {
            echo '<option value="'.$row['id_cliente'].'">'.$row['nome'].'</option>';
        }
        ?>
    </select>

    <label for="descricao">Descrição da Venda:</label>
    <input type="text" id="descricao" name="descricao" required>
<--
    <label for="data_venda">Data da Venda:</label>
    <input type="date" id="data_venda" name="data_venda" value="" required>
-->
    <input type="date" id="dataHoje" name="dataHoje" value="">
<script>
    document.getElementById('dataHoje').valueAsDate = new Date();
</script>
    <label for="valor_total">Valor Total:</label>
    <input type="number" step="0.01" id="valor_total" name="valor_total" required>

    <input type="submit" value="Cadastrar Venda">
</form>

<!-- Exibe a mensagem de sucesso ou erro após o envio -->
<?php
if ($mensagem) {
    echo $mensagem;
}
?>
