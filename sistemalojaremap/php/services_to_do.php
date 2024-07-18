<?php

include 'databaseconfig.php';  // Incluindo a configuração do banco de dados

// Função para carregar tarefas
function loadTasks($conn) {
    $sql = "SELECT * FROM services_to_do";
    $result = $conn->query($sql);
    $tasks = array();
    while ($row = $result->fetch_assoc()) {
        $tasks[] = $row;
    }
    return json_encode($tasks);
}

// Função para adicionar tarefa
function addTask($conn) {
    $nome_cliente = $_POST['nome_cliente'];
    $numero_cliente = $_POST['numero_cliente'];
    $servico = $_POST['servico'];
    $descricao = $_POST['descricao'];
    $valor = $_POST['valor'];
    $status_pagamento = $_POST['status_pagamento'];

    $sql = "INSERT INTO services_to_do (nome_cliente, numero_cliente, servico, descricao, valor, status_pagamento) 
            VALUES ('$nome_cliente', '$numero_cliente', '$servico', '$descricao', '$valor', '$status_pagamento')";

    if ($conn->query($sql) === TRUE) {
        return loadTasks($conn);
    } else {
        return "Erro: " . $sql . "<br>" . $conn->error;
    }
}

// Função para concluir tarefa
function completeTask($conn) {
    $id = $_POST['id'];
    
    // Transferir dados da services_to_do para services_performed
    $sql = "INSERT INTO services_performed (nome_cliente, numero_cliente, servico, descricao, valor)
            SELECT nome_cliente, numero_cliente, servico, descricao, valor
            FROM services_to_do WHERE id = $id";
    
    if ($conn->query($sql) === TRUE) {
        // Excluir da services_to_do
        $deleteSql = "DELETE FROM services_to_do WHERE id = $id";
        if ($conn->query($deleteSql) === TRUE) {
            return loadTasks($conn);
        } else {
            return "Erro ao excluir: " . $deleteSql . "<br>" . $conn->error;
        }
    } else {
        return "Erro ao transferir: " . $sql . "<br>" . $conn->error;
    }
}

// Função para excluir tarefa
function deleteTask($conn) {
    $id = $_GET['id'];
    $sql = "DELETE FROM services_to_do WHERE id = $id";

    if ($conn->query($sql) === TRUE) {
        return loadTasks($conn);
    } else {
        return "Erro: " . $sql . "<br>" . $conn->error;
    }
}

// Manipulação das ações
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] === 'add') {
        echo addTask($conn);
        exit;
    } elseif ($_POST['action'] === 'complete') {
        echo completeTask($conn);
        exit;
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action'])) {
    if ($_GET['action'] === 'load') {
        echo loadTasks($conn);
        exit;
    } elseif ($_GET['action'] === 'delete') {
        echo deleteTask($conn);
        exit;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="../css/services_to_do.css" />
    <link rel="icon" type="image/x-icon" href="../src/favicon.ico" />
    <title>To do List</title>
</head>
<body>
    <div class="container">
        <h1>Lista de Trabalhos</h1>
        <a href="../index.html">Voltar</a>
        <form id="taskForm">
            <input type="text" id="nome_cliente" name="nome_cliente" placeholder="Nome Cliente" required>
            <input type="text" id="numero_cliente" name="numero_cliente" placeholder="Número Cliente" required>
            <input type="text" id="servico" name="servico" placeholder="Serviço" required>
            <input type="text" id="descricao" name="descricao" placeholder="Descrição" required>
            <input type="number" id="valor" name="valor" placeholder="Valor" required>
            <input type="text" id="status_pagamento" name="status_pagamento" placeholder="Status Pagamento" required>
            <button type="submit">Adicionar Tarefa</button>
            <input type="hidden" name="action" value="add">
        </form>
        <div id="taskListContainer">
            <table id="taskList">
                <thead>
                    <tr>
                        <th>Nome do Cliente</th>
                        <th>Número do Cliente</th>
                        <th>Serviço</th>
                        <th>Descrição</th>
                        <th>Valor</th>
                        <th>Status Pagamento</th>
                        <th>Ação</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', loadTasks);
        document.getElementById('taskForm').addEventListener('submit', addTask);

        function loadTasks() {
            fetch('?action=load')
                .then(response => response.json())
                .then(data => {
                    const taskList = document.getElementById('taskList').getElementsByTagName('tbody')[0];
                    taskList.innerHTML = '';
                    data.forEach(task => {
                        const taskRow = document.createElement('tr');
                        taskRow.innerHTML = `
                            <td>${task.nome_cliente}</td>
                            <td>${task.numero_cliente}</td>
                            <td>${task.servico}</td>
                            <td>${task.descricao}</td>
                            <td>${task.valor}</td>
                            <td>${task.status_pagamento}</td>
                            <td><button onclick="completeTask(${task.id})">Trabalho Concluído</button></td>
                        `;
                        taskList.appendChild(taskRow);
                    });
                });
        }

        function addTask(event) {
            event.preventDefault();
            const formData = new FormData(document.getElementById('taskForm'));
            fetch('', {
                method: 'POST',
                body: formData
            }).then(response => response.json())
              .then(data => {
                  const taskList = document.getElementById('taskList').getElementsByTagName('tbody')[0];
                  taskList.innerHTML = '';
                  data.forEach(task => {
                      const taskRow = document.createElement('tr');
                      taskRow.innerHTML = `
                          <td>${task.nome_cliente}</td>
                          <td>${task.numero_cliente}</td>
                          <td>${task.servico}</td>
                          <td>${task.descricao}</td>
                          <td>${task.valor}</td>
                          <td>${task.status_pagamento}</td>
                          <td><button onclick="completeTask(${task.id})">Trabalho Concluído</button></td>
                      `;
                      taskList.appendChild(taskRow);
                  });
                  document.getElementById('taskForm').reset();
              });
        }

        function completeTask(id) {
            fetch('', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'action=complete&id=' + id
            }).then(response => response.json())
              .then(data => {
                  const taskList = document.getElementById('taskList').getElementsByTagName('tbody')[0];
                  taskList.innerHTML = '';
                  data.forEach(task => {
                      const taskRow = document.createElement('tr');
                      taskRow.innerHTML = `
                          <td>${task.nome_cliente}</td>
                          <td>${task.numero_cliente}</td>
                          <td>${task.servico}</td>
                          <td>${task.descricao}</td>
                          <td>${task.valor}</td>
                          <td>${task.status_pagamento}</td>
                          <td><button onclick="completeTask(${task.id})">Trabalho Concluído</button></td>
                      `;
                      taskList.appendChild(taskRow);
                  });
              });
        }
    </script>
</body>
</html>
