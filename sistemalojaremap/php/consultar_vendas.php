<?php 
require 'databaseconfig.php';

if (isset($_GET['id_cliente']) && !empty($_GET['id_cliente'])) {
    $id_cliente = intval($_GET['id_cliente']);

    $sql = "SELECT v.id_venda, c.nome AS cliente, v.data_venda, v.descricao, v.valor_total
            FROM vendas v
            JOIN clientes c ON v.id_cliente = c.id_cliente
            WHERE v.id_cliente = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_cliente);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "<table border='1' width='100%'>
                <tr>
                    <th>Cliente</th>
                    <th>Data</th>
                    <th>Descrição</th>
                    <th>Valor (R$)</th>
                </tr>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>{$row['cliente']}</td>
                    <td>{$row['data_venda']}</td>
                    <td>{$row['descricao']}</td>
                    <td class='valor-total'>R$ {$row['valor_total']}</td>
                  </tr>";
        }
        echo "</table>";

        // Botão Fechar Caixa com senha
        echo '<form action="fechar_caixa.php" method="POST" onsubmit="return validarSenha();" style="margin-top: 10px;">';
        echo '<input type="hidden" name="id_cliente" value="' . $id_cliente . '">';
        echo '<button type="submit">Fechar Caixa</button>';
        echo '</form>';

        echo "<p id='totalGeral' style='font-weight:bold; margin-top:10px;'></p>";
    } else {
        echo "<p class='error-message'>Nenhuma venda encontrada para este cliente.</p>";
    }
    $stmt->close();
}
?>

<script>
function validarSenha() {
    const senhaCorreta = "8812";
    const senha = prompt("Digite a senha para fechar o caixa:");

    if (senha === null) {
        // Cancelou
        return false;
    }

    if (senha === senhaCorreta) {
        return confirm("Confirma o fechamento do caixa?");
    } else {
        alert("Senha incorreta. Operação cancelada.");
        return false;
    }
}
</script>
