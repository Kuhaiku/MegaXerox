<?php
require 'databaseconfig.php';

$result = $conn->query("SELECT vf.*, c.nome FROM vendas_fechadas vf
                        JOIN clientes c ON vf.id_cliente = c.id_cliente
                        ORDER BY vf.data_fechamento DESC");

echo "<h1>Vendas Fechadas</h1>";
echo "<table border='1'>
<tr><th>Cliente</th><th>Descrição</th><th>Data Venda</th><th>Valor</th><th>Data Fechamento</th></tr>";

while ($row = $result->fetch_assoc()) {
    echo "<tr>
        <td>".$row['nome']."</td>
        <td>".$row['descricao']."</td>
        <td>".$row['data_venda']."</td>
        <td>R$ ".number_format($row['valor_total'], 2, ',', '.')."</td>
        <td>".$row['data_fechamento']."</td>
    </tr>";
}

echo "</table>";

$conn->close();
?>
