<?php
include 'databaseconfig.php';

$produtoParaEditar = null;

// Adiciona um serviço
if (isset($_POST['add'])) {
    $descricao = $_POST['descricao'];
    $valor = $_POST['valor'];

    $sql = "INSERT INTO service_dtbase (descricao, valor) VALUES ('$descricao', '$valor')";
    if ($conn->query($sql) === TRUE) {
       
    } else {
        echo "Erro: " . $sql . "<br>" . $conn->error;
    }
}

// Solicita edição de um serviço
if (isset($_POST['request_edit'])) {
    $identifier = $_POST['identifier'];
    $sql = "SELECT * FROM service_dtbase WHERE id='$identifier' OR descricao='$identifier'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $produtoParaEditar = $result->fetch_assoc();
    } else {
        echo "Produto não encontrado!";
    }
}

// Atualiza um serviço
if (isset($_POST['edit'])) {
    $id = $_POST['id'];
    $descricao = $_POST['descricao'];
    $valor = $_POST['valor'];

    $sql = "UPDATE service_dtbase SET descricao='$descricao', valor='$valor' WHERE id='$id'";
    if ($conn->query($sql) === TRUE) {
        echo "Produto atualizado com sucesso!";
    } else {
        echo "Erro: " . $sql . "<br>" . $conn->error;
    }
}

// Obtém todos os serviços
$sql = "SELECT * FROM service_dtbase";
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
    <title>Gerenciamento de serviços</title>
    <link rel="stylesheet" href="../css/service_dtbase.css">
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

        function converterValor() {
            var valorInputs = document.getElementsByName("valor");
            for (var i = 0; i < valorInputs.length; i++) {
                var valor = valorInputs[i].value;
                
                // Substitui a vírgula por ponto
                valor = valor.replace(',', '.');
                
                // Atualiza o valor do input com o novo formato
                valorInputs[i].value = valor;
            }
        }
    </script>
</head>
<body>
    <header>
     <h1>Gerenciamento de servicos</h1>
     <h2> <a href="../index.html">Inicio</a>  </h2>
    </header>
    <div id="main" class="container">
        <div id="form-container">
            <div id="add-form">
                <h2>Adicionar Produto</h2>
                <form action="service_dtbase.php" method="post" onsubmit="converterValor()">
                    <label for="descricao">Nome do Produto:</label>
                    <input type="text" name="descricao" id="descricao" required>
                    
                    <label for="valor">Preço:</label>
                    <input type="text" name="valor" id="valor" required>
                    <br>
                    <button type="submit" name="add">Adicionar</button>
                </form>
            </div>

            <div id="edit-form">
                <h2>Editar Produto</h2>
                <form action="service_dtbase.php" method="post">
                    <label for="identifier">Nome ou ID do Produto:</label>
                    <input type="text" name="identifier" id="identifier" required>
                    <button type="submit" name="request_edit">Procurar</button>
                </form>

                <?php if ($produtoParaEditar): ?>
                    <div>
                        <h2>Editar Produto: <?php echo $produtoParaEditar['descricao']; ?></h2>
                        <form action="service_dtbase.php" method="post" onsubmit="converterValor()">
                            <input type="hidden" name="id" value="<?php echo $produtoParaEditar['id']; ?>">
                            <label for="descricao">Nome do Produto:</label>
                            <input type="text" name="descricao" id="descricao" value="<?php echo $produtoParaEditar['descricao']; ?>" required>
                            <label for="valor">Preço:</label>
                            <input type="text" name="valor" id="valor" value="<?php echo $produtoParaEditar['valor']; ?>" required>
                            <br>
                            <button type="submit" name="edit">Salvar Alterações</button>
                        </form>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <h2>Buscar Serviço</h2>
        <input type="text" id="busca" onkeyup="filtrarProdutos()" placeholder="Buscar por nome do produto..">

        <h2>Serviços Cadastrados</h2>
        <table id="tabelaProdutos">
            <tr>
                <th>ID</th>
                <th>Nome do Serviço</th>
                <th>Preço</th>
                
            </tr>
            <?php if (count($produtos) > 0): ?>
                <?php foreach($produtos as $row): ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo $row['descricao']; ?></td>
                        <td><?php echo $row['valor']; ?></td>
                        
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
