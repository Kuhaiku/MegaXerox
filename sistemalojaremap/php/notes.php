<?php
// Incluir o arquivo de configuração do banco de dados
include 'databaseconfig.php';


// Verificar a conexão
if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

// Função para adicionar uma nova nota
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_note'])) {
    $note_name = $_POST['note_name'];
    $descricao = $_POST['descricao'];

    $sql = "INSERT INTO notes (note_name, descricao) VALUES ('$note_name', '$descricao')";

     if ($conn->query($sql) === TRUE) {
     } else {
         echo "Erro: " . $sql . "<br>" . $conn->error;
     }
}

// Função para apagar uma nota
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_note'])) {
    $note_id = $_POST['note_id'];

    $sql = "DELETE FROM notes WHERE id=$note_id";

    if ($conn->query($sql) === TRUE) {
    } else {
        echo "Erro ao apagar nota: " . $conn->error;
    }
}

// Função para carregar todas as notas
$sql = "SELECT * FROM notes ORDER BY data DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <link rel="icon" type="image/x-icon" href="../src/favicon.ico" />
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notas</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            padding: 20px;
            text-align:center;
        }
        .container {
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            margin:auto;
            width: 60%;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            
        }
        h1 {
            color: #005f73;
        }
        form {
            
            margin-bottom: 20px;
        }
        input[type="text"],
        textarea {
            margin:auto;  
            margin-top:20px;
            margin-bottom:20px;
            display: block;
            width: 50%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        input[type="submit"] {
            background-color: #005f73;
            color: white;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            border-radius: 4px;
        }
        input[type="submit"]:hover {
            background-color: #003f50;
        }
        .note {
            border-bottom: 1px solid #ccc;
            padding: 10px 0;
        }
        .note form {
            display: inline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Adicionar Nota</h1>
        <h3><a href="../index.html">Voltar</a></h3>
        <form action="notes.php" method="post">
            <label for="note_name">Titulo da anotação:</label>
            <input type="text" id="note_name" name="note_name" required>
            <label for="descricao">Descrição:</label>
            <textarea id="descricao" name="descricao" rows="4" required></textarea>
            <input type="submit" name="add_note" value="Adicionar Nota">
        </form>
        <h1>Notas</h1>
        <?php
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo "<div class='note'>";
                echo "<h2>" . $row["note_name"] . "</h2>";
                echo "<p>" . $row["descricao"] . "</p>";
                echo "<p><small><i>Data: " . $row["data"] . "</i></small></p>";
                echo "<form action='notes.php' method='post' onsubmit='return confirm(\"Você tem certeza que deseja apagar esta nota?\");'>";
                echo "<input type='hidden' name='note_id' value='" . $row["id"] . "'>";
                echo "<input type='submit' name='delete_note' value='Apagar' style='background-color: #ff4d4d;'>";
                echo "</form>";
                echo "</div>";
            }
        } else {
            echo "Nenhuma nota encontrada.";
        }
        $conn->close();
        ?>
    </div>
</body>
</html>
