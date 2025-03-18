<?php
include 'databaseconfig.php';

$result = $conn->query("SELECT * FROM clientes_fidelidade");
$clientes = $result->fetch_all(MYSQLI_ASSOC);

echo "<h1>Clientes Cadastrados</h1>";
echo "<table border='1'>
        <tr>
            <th>ID</th>
            <th>Nome</th>
        </tr>";
foreach ($clientes as $cliente) {
    echo "<tr>
            <td>{$cliente['id']}</td>
            <td>{$cliente['nome']}</td>
          </tr>";
}
echo "</table>";
?>
