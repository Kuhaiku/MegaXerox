<?php
require 'databaseconfig.php';

if (isset($_GET['id_cliente'])) {
    $id_cliente = intval($_GET['id_cliente']);

    $stmt = $conn->prepare("SELECT * FROM vendas WHERE id_cliente = ?");
    $stmt->bind_param("i", $id_cliente);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo '<ul>';
        while ($row = $result->fetch_assoc()) {
            echo '<li>';
            echo '<span>Descrição: ' . htmlspecialchars($row['descricao']) . '</span>';
            echo '<span>Data: ' . htmlspecialchars($row['data_venda']) . '</span>';
            echo '<span>Valor: R$ ' . number_format($row['valor_total'], 2, ',', '.') . '</span>';
            echo '</li>';
        }
        echo '</ul>';
    } else {
        echo '<p>Nenhuma venda encontrada para este cliente.</p>';
    }
    
    $stmt->close();
}

$conn->close();
?>
