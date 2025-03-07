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
                    <th>ID Venda</th>
                    <th>Cliente</th>
                    <th>Data</th>
                    <th>Descrição</th>
                    <th>Valor (R$)</th>
                </tr>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>{$row['id_venda']}</td>
                    <td>{$row['cliente']}</td>
                    <td>{$row['data_venda']}</td>
                    <td>{$row['descricao']}</td>
                    <td>R$ {$row['valor_total']}</td>
                  </tr>";
        }
        echo "</table>";
    } else {
        echo "<p class='error-message'>Nenhuma venda encontrada para este cliente.</p>";
    }
    $stmt->close();
}
?>
