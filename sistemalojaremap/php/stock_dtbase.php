<?php
include '../php/databaseconfig.php';

$produtoParaEditar = null;

// Adiciona um produto
if (isset($_POST['add'])) {
    $nome_produto = $_POST['nome_produto'];
    $quantidade = $_POST['quantidade'];
    $preco = $_POST['preco'];

    $sql = "INSERT INTO stock_dtbase (nome_produto, quantidade, preco) VALUES ('$nome_produto', '$quantidade', '$preco')";
    if ($conn->query($sql) === TRUE) {
        
    } else {
        echo "Erro: " . $sql . "<br>" . $conn->error;
    }
}

// Solicita edição de um produto
if (isset($_POST['request_edit'])) {
    $identifier = $_POST['identifier'];
    $sql = "SELECT * FROM stock_dtbase WHERE id='$identifier' OR nome_produto='$identifier'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $produtoParaEditar = $result->fetch_assoc();
    } else {
        echo "Produto não encontrado!";
    }
}

// Atualiza um produto
if (isset($_POST['edit'])) {
    $id = $_POST['id'];
    $nome_produto = $_POST['nome_produto'];
    $quantidade = $_POST['quantidade'];
    $preco = $_POST['preco'];

    $sql = "UPDATE stock_dtbase SET nome_produto='$nome_produto', quantidade='$quantidade', preco='$preco' WHERE id='$id'";
    if ($conn->query($sql) === TRUE) {

    } else {
        echo "Erro: " . $sql . "<br>" . $conn->error;
    }
}

//Obtém todos os produtos
$sql = "SELECT * FROM stock_dtbase";
$result = $conn->query($sql);
$produtos = [];
while($row = $result->fetch_assoc()) {
    $produtos[] = $row;
}
?>

<!DOCTYPE html>
<html lang="pt-br">
    <head>
    <link rel="icon" type="image/x-icon" href="../src/favicon.ico" />
    <meta charset="UTF-8">
    <title>Gerenciamento de Estoque</title>
    <link rel="stylesheet" href="../css/stock_dtbase.css">
    <script>
        function filtrarProdutos() {
            var input, filter, table, tr, td, i, txtValue;
            input = document.getElementById("busca");
            filter = input.value.toLowerCase();
            table = document.getElementById("tabelaProdutos");
            tr = table.getElementsByTagName("tr");

            for (i = 1; i < tr.length; i++) {
                td = tr[i].getElementsByTagName("td")[1];
                if (td) {
                    txtValue = td.textContent || td.innerText;
                    if (txtValue.toLowerCase().indexOf(filter) > -1) {
                        tr[i].style.display = "";
                    } else {
                        tr[i].style.display = "none";
                    }
                }       
            }
        }
        function converterpreco() {
            var precoInputs = document.getElementsByName("preco");
            for (var i = 0; i < precoInputs.length; i++) {
                var preco = precoInputs[i].value;
                
                // Substitui a vírgula por ponto
                preco = preco.replace(',', '.');
                
                // Atualiza o preco do input com o novo formato
                precoInputs[i].value = preco;
            }
        }
    </script>
</head>
<body>
    <header>
    <h1>Gerenciamento de Estoque</h1>
    <h2> <a href="../index.html">Inicio</a>  </h2>
    </header>
    <div id="main" class="container">
        <div id="form-container">
            <div id="add-form">
                <h2>Adicionar Produto</h2>
                <form action="stock_dtbase.php" method="post" onsubmit="converterpreco()">
                    <label for="nome_produto">Nome do Produto:</label>
                    <input type="text" name="nome_produto" id="nome_produto" required>
                    <br>
                    <label for="quantidade">Quantidade:</label>
                    <input type="number" name="quantidade" id="quantidade" required>
                    <br>
                    <label for="preco">Preço:</label>
                    <input type="text" name="preco" id="preco" required>
                    <br>
                    <button type="submit" name="add">Adicionar</button>
                </form>
            </div>

            <div id="edit-form">
                <h2>Editar Produto</h2>
                <form action="stock_dtbase.php" method="post" onsubmit="converterpreco()">
                    <label for="identifier">Nome ou ID do Produto:</label>
                    <input type="text" name="identifier" id="identifier" required>
                    <button type="submit" name="request_edit">Procurar</button>
                </form>

                <?php if ($produtoParaEditar): ?>
                    <div>
                        <h2>Editar Produto: <?php echo $produtoParaEditar['nome_produto']; ?></h2>
                        <form action="stock_dtbase.php" method="post">
                            <input type="hidden" name="id" value="<?php echo $produtoParaEditar['id']; ?>">
                            <label for="nome_produto">Nome do Produto:</label>
                            <input type="text" name="nome_produto" id="nome_produto" value="<?php echo $produtoParaEditar['nome_produto']; ?>" required>
                            <br>
                            <label for="quantidade">Quantidade:</label>
                            <input type="number" name="quantidade" id="quantidade" value="<?php echo $produtoParaEditar['quantidade']; ?>" required>
                            <br>
                            <label for="preco">Preço:</label>
                            <input type="text" name="preco" id="preco" value="<?php echo $produtoParaEditar['preco']; ?>" required>
                            <br>
                            <button type="submit" name="edit">Salvar Alterações</button>
                        </form>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <h2>Buscar Produto</h2>
        <input type="text" id="busca" onkeyup="filtrarProdutos()" placeholder="Buscar por nome do produto..">

        <h2>Produtos no Estoque</h2>
        <table id="tabelaProdutos">
            <tr>
                <th>ID</th>
                <th>Nome do Produto</th>
                <th>Quantidade</th>
                <th>Preço</th>
                
            </tr>
            <?php if (count($produtos) > 0): ?>
                <?php foreach($produtos as $row): ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo $row['nome_produto']; ?></td>
                        <td><?php echo $row['quantidade']; ?></td>
                        <td><?php echo $row['preco']; ?></td>
                        
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5">Nenhum produto encontrado</td>
                </tr>
            <?php endif; ?>
        </table>

        <?php $conn->close(); ?>
    </div>
</body>
</html>
