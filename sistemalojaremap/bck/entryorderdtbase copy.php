
<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="icon" type="image/x-icon" href="../src/favicon.ico" />

<title>Clientes Cadastrados</title>
  <style>
*{
  text-decoration: none;
}
.menu-bar {
    background-color: #333;
    overflow: hidden;
}

.menu-bar ul {
    list-style-type: none;
    margin: 0;
    padding: 0;
}

.menu-bar ul li {
    float: left;
}

.menu-bar ul li a {
    display: block;
    color: white;
    text-align: center;
    padding: 14px 20px;
    text-decoration: none;
}

.menu-bar ul li a:hover {
    background-color: #111;
}
  body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
    background-color: #f4f4f9;
    color: #333;
    
  }
  .container {
    width: 90%;
    margin: 20px auto;
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
  }
  .box {
    background-color: #fff;
    width: 400px;
    margin: 10px;
    padding: 15px;
    box-sizing: border-box;
    border: 1px solid #ddd;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
  }
  ul {
    list-style: none;
    padding: 0;
  }
  li {
    padding: 8px 0;
    border-bottom: 1px solid #eee;
  }
  li:last-child {
    border-bottom: none;
  }
  strong {
    color: #555;
  }
</style>
</head>
<body>
  <?php
include '../php/databaseconfig.php';

echo 

"<nav class='menu-bar'>
        <ul>
            <li><a href='../index.html'>Inicio</a></li>
            <li><a href='entryorder.php'>Cadastrar Ordem de serviço</a></li>
        </ul>
    </nav>
    
  ";

$sql = "SELECT * FROM sistemaloja.entryorder";
$result = mysqli_query($conn, $sql);
if (mysqli_num_rows($result) > 0) {
    echo "<div class='container'>";
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<div class='box'><ul>";
        foreach ($row as $campo => $valor) {
            echo "<li><strong>{$campo}:</strong> {$valor}</li>";
        }
        echo "</ul></div>";
    }
    echo "</div>";
} else {
    echo "Nenhum resultado encontrado.";
}

mysqli_free_result($result);
mysqli_close($conn);
?>
</body>
</html>
