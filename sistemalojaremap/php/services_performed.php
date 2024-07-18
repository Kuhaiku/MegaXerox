<?php
include 'databaseconfig.php'; 


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
        $deleteSql = "DELETE FROM services_performed WHERE id = $id";
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
    $sql = "DELETE FROM services_performed WHERE id = $id";

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

?>
<!DOCTYPE html>
<html>
<head>
    <link rel="icon" type="image/x-icon" href="../src/favicon.ico" />
    <title>Serviços Concluidos</title>
    <style>
        
        .taskaa{
            border: black 1px solid;
            border-radius: 20px;
            padding: 12px;
        }
         body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f0f0f0;
            color: #333;
            margin: 0;
            padding: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            width: 100%;
            max-width: 800px;
            background-color: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            text-align: center;

        }

        h1 {
            color: #005f73;
            text-align: center;
            margin-bottom: 20px;
            font-size: 36px;
            text-transform: uppercase;
        }

        #taskForm {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        #taskForm input, #taskForm button {
            flex: 1 1 calc(50% - 10px);
            margin-bottom: 10px;
            padding: 12px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 5px;
            transition: all 0.3s ease;
        }

        #taskForm button {
            background-color: #005f73;
            color: #fff;
            border: none;
            cursor: pointer;
            text-transform: uppercase;
            font-weight: bold;
            letter-spacing: 1px;
            transition: background-color 0.3s ease;
        }

        #taskForm button:hover {
            background-color: #004d5c;
        }

        #taskList {
            margin-top: 20px;
        }

        .task {
            background-color: #fff;
            padding: 15px;
            margin-bottom: 15px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .task span {
            font-size: 16px;
            color: #333;
        }

        .task button {
            background-color: #005f73;
            color: #fff;
            border: none;
            padding: 8px 12px;
            margin-left: 10px;
            cursor: pointer;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .task button:hover {
            background-color: #004d5c;
        }

        @media (max-width: 768px) {
            #taskForm input, #taskForm button {
                flex: 1 1 100%;
                margin-bottom: 10px;
            }
        }

        @media (max-width: 480px) {
            #taskForm input, #taskForm button {
                flex: 1 1 100%;
                margin-bottom: 10px;
            }

            h1 {
                font-size: 28px;
                margin-bottom: 15px;
            }
        }

        a{
            text-decoration: none;
            color:#005f73 ;
        }
    </style>
</head>
<body>
     <div class="container">
        <h1>Trabalhos Realizados</h1>
        <a href="../index.html"> Voltar</a>
       
        <div id="taskList"></div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', loadTasks);
        document.getElementById('taskForm').addEventListener('submit', addTask);

        function loadTasks() {
            fetch('?action=load')
                .then(response => response.json())
                .then(data => {
                    const taskList = document.getElementById('taskList');
                    taskList.innerHTML = '';
                    data.forEach(task => {
                        const taskDiv = document.createElement('div');
                        taskDiv.className = 'task';
                        taskDiv.innerHTML = `
                            <span class="taskaa">${task.nome_cliente} - ${task.numero_cliente} - ${task.servico} - ${task.descricao} - ${task.valor} - ${task.status_pagamento}</span>
                        `;
                        taskList.appendChild(taskDiv);
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
                  const taskList = document.getElementById('taskList');
                  taskList.innerHTML = '';
                  data.forEach(task => {
                      const taskDiv = document.createElement('div');
                      taskDiv.className = 'task';
                      taskDiv.innerHTML = `
                          <span>${task.nome_cliente} - ${task.numero_cliente} - ${task.servico} - ${task.descricao} - ${task.valor} - ${task.status_pagamento}</span>
                          <button onclick="completeTask(${task.id})">Trabalho Concluído</button>
                      `;
                      taskList.appendChild(taskDiv);
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
                  const taskList = document.getElementById('taskList');
                  taskList.innerHTML = '';
                  data.forEach(task => {
                      const taskDiv = document.createElement('div');
                      taskDiv.className = 'task';
                      taskDiv.innerHTML = `
                          <span>${task.nome_cliente} - ${task.numero_cliente} - ${task.servico} - ${task.descricao} - ${task.valor} - ${task.status_pagamento}</span>
                          <button onclick="completeTask(${task.id})">Trabalho Concluído</button>
                      `;
                      taskList.appendChild(taskDiv);
                  });
              });
        }
    </script>
</body>
</html>
