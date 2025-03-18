<?php
include 'databaseconfig.php';

$result = $conn->query("SELECT v.id, c.nome AS cliente, v.unidade, v.descricao_compra, v.valor, v.data_compra 
                        FROM vendas_fidelidade v
                        JOIN clientes_fidelidade c ON v.cliente_id = c.id");
$vendas = $result->fetch_all(MYSQLI_ASSOC);

echo "<h1>Vendas Cadastradas</h1>";
echo "<table border='1'>
        <tr>
            <th>ID</th>
            <th>Cliente</th>
            <th>Unidade</th>
            <th>Descrição</th>
            <th>Valor</th>
            <th>Data da Compra</th>
        </tr>";
foreach ($vendas as $venda) {
    echo "<tr>
            <td>{$venda['id']}</td>
            <td>{$venda['cliente']}</td>
            <td>{$venda['unidade']}</td>
            <td>{$venda['descricao_compra']}</td>
            <td>{$venda['valor']}</td>
            <td>{$venda['data_compra']}</td>
          </tr>";
}
echo "</table>";
?>
