<?php
include_once('databaseconfig.php');

$id = isset($_GET['id']) ? intval($_GET['id']) : (isset($_POST['id']) ? intval($_POST['id']) : 0);
$mensagem = '';

// --- 1. Lógica de Atualização (UPDATE) ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_update']) && $id > 0) {
    $nome = $_POST['nome'] ?? '';
    $telefone = $_POST['telefone'] ?? '';
    $preorc = $_POST['preorc'] ?? '';
    
    // Assumindo estrutura 1-to-1 para 1 OS ID -> 1 Dispositivo (índice [0])
    $tipo = $_POST['tipo'][0] ?? '';
    $marca = $_POST['marca'][0] ?? '';
    $modelo = $_POST['modelo'][0] ?? '';
    $perifericos = $_POST['perifericos'][0] ?? 'NÃO';
    $defeito = $_POST['defeito'][0] ?? '';

    $stmt = $conn->prepare("UPDATE entryorder SET 
        nome = ?, 
        telefone = ?, 
        tipo = ?, 
        marca = ?, 
        modelo = ?, 
        perifericos = ?, 
        defeito = ?, 
        preorc = ?
        WHERE id = ?");

    if ($stmt) {
        $stmt->bind_param("ssssssssi", $nome, $telefone, $tipo, $marca, $modelo, $perifericos, $defeito, $preorc, $id);
        if ($stmt->execute()) {
            $mensagem = "<p style='color:green;'>Ordem de Entrada Nº $id atualizada com sucesso!</p>";
        } else {
            $mensagem = "<p style='color:red;'>Erro ao atualizar: " . $stmt->error . "</p>";
        }
        $stmt->close();
    } else {
        $mensagem = "<p style='color:red;'>Erro na preparação da consulta de atualização.</p>";
    }
}

// --- 2. Lógica de Busca (SELECT) ---
$dados = null;
if ($id > 0) {
    $stmt = $conn->prepare("SELECT * FROM entryorder WHERE id = ?");
    if ($stmt) {
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $dados = $result->fetch_assoc();
        $stmt->close();
    }
}
$conn->close();

if (!$dados) {
    $mensagem .= "<p style='color:red;'>Ordem de Entrada não encontrada ou ID inválido.</p>";
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="icon" type="image/x-icon" href="../src/favicon.ico" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    <title>Sistema Mega-Xerox - Editar Ordem de Entrada</title>
    <style>
        /* Estilos copiados de entryorder.php e print_entryorder.php para layout */
        body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol"; font-size: 14px; background-color: #f4f4f4; }
        .ordemdeentrada { max-width: 800px; margin: 20px auto; background-color: #fff; padding: 20px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        header { display: flex; justify-content: space-between; align-items: flex-start; border-bottom: 2px solid #333; padding-bottom: 10px; }
        .header-left p, .header-left h2 { margin: 2px 0; }
        .header-right { text-align: right; }
        .header-right h3, .header-right p { margin: 0; }
        fieldset { border: none; padding: 10px 0; }
        .form-row { display: flex; gap: 15px; width: 100%; }
        .inputBox { margin-bottom: 12px; width: 100%; }
        .inputUser { width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; font-size:14px; }
        .data-fields { display: flex; justify-content: space-between; border: 1px solid #ccc; padding: 8px; border-radius: 4px; margin-bottom: 12px; }
        .data-fields b { margin-right: 5px; }
        .dispositivo { border: 1px solid #e0e0e0; padding: 15px; margin-bottom: 10px; border-radius: 5px; }
        .tipodispositivo { display: flex; align-items: center; flex-wrap: wrap; gap: 10px; margin-bottom: 15px; }
        .tipodispositivo b { margin-right: 10px; }
        textarea.inputUser { height: 45px; } 
        .botoes { text-align: right; margin-top: 20px; }
        button, .navegar { padding: 10px 15px; border-radius: 5px; text-decoration: none; border: none; cursor: pointer; font-size: 14px; }
        .navegar { background-color: #6c757d; color: white; }
        button[type="submit"] { background-color: #ffc107; color: #333; margin-left: 10px; }
        button[type="submit"]:hover { background-color: #e0a800; }
    </style>
</head>
<body>
    <div class="ordemdeentrada">
        <header>
            <div class="header-left">
                <h2>Mega Xerox</h2>
                <p>Cnpj: 12.689.177/0001-26</p>
                <p>Whatsapp: 2665-5910</p>
            </div>
            <div class="header-right">
                <h3>Editar Ordem de Entrada Nº <?php echo $id; ?></h3>
                <?php echo $mensagem; ?>
            </div>
        </header>

        <?php if ($dados): ?>
            <form method="post" action="edit_entryorder.php?id=<?php echo $id; ?>">
                <input type="hidden" name="id" value="<?php echo $id; ?>">
                <input type="hidden" name="submit_update" value="1">
                
                <fieldset>
                    <div class="inputBox">
                        <input class="inputUser" type="text" name="nome" id="nome" value="<?php echo htmlspecialchars($dados['nome']); ?>" required oninput="this.value = this.value.toUpperCase()" placeholder="Nome do Cliente:"/>
                    </div>
                    <div class="inputBox">
                        <input class="inputUser" type="text" name="telefone" id="telefone" value="<?php echo htmlspecialchars($dados['telefone']); ?>" required placeholder="Telefone / Whatsapp:"/>
                    </div>

                    <div class="data-fields">
                        <div><b>Data de Entrada:</b> <?php echo date('d/m/Y H:i', strtotime($dados['data_entrada'])); ?></div>
                        </div>
                    
                    <div class="dispositivo">
                        <div class="tipodispositivo">
                            <b>Tipo de Dispositivo:</b>
                            <?php $tipo_atual = htmlspecialchars($dados['tipo']); ?>
                            <input type="radio" name="tipo[0]" value="IMPRESSORA" <?php echo $tipo_atual == 'IMPRESSORA' ? 'checked' : ''; ?> required /> Impressora
                            <input type="radio" name="tipo[0]" value="NOTEBOOK" <?php echo $tipo_atual == 'NOTEBOOK' ? 'checked' : ''; ?> /> Notebook
                            <input type="radio" name="tipo[0]" value="DESKTOP" <?php echo $tipo_atual == 'DESKTOP' ? 'checked' : ''; ?> /> Desktop
                            <input type="radio" name="tipo[0]" value="CONTROLE" <?php echo $tipo_atual == 'CONTROLE' ? 'checked' : ''; ?> /> Controle
                            <input type="radio" name="tipo[0]" value="CONSOLE" <?php echo $tipo_atual == 'CONSOLE' ? 'checked' : ''; ?> /> Console
                            <input type="radio" name="tipo[0]" value="CELULAR" <?php echo $tipo_atual == 'CELULAR' ? 'checked' : ''; ?> /> Celular
                            <input type="radio" name="tipo[0]" value="OUTRO" <?php echo $tipo_atual == 'OUTRO' ? 'checked' : ''; ?> /> Outro
                        </div>
                        <div class="form-row">
                            <div class="inputBox">
                                <input class="inputUser" type="text" name="marca[0]" value="<?php echo htmlspecialchars($dados['marca']); ?>" required oninput="this.value = this.value.toUpperCase()" placeholder="Marca:" />
                            </div>
                            <div class="inputBox">
                                <input class="inputUser" type="text" name="modelo[0]" value="<?php echo htmlspecialchars($dados['modelo']); ?>" required oninput="this.value = this.value.toUpperCase()" placeholder="Modelo:" />
                            </div>
                        </div>
                        <div class="inputBox">
                            <input class="inputUser" type="text" name="perifericos[0]" value="<?php echo htmlspecialchars($dados['perifericos']); ?>" oninput="this.value = this.value.toUpperCase()" placeholder="Periféricos/Acessórios:"/>
                        </div>
                        <div class="inputBox">
                            <textarea class="inputUser" name="defeito[0]" oninput="this.value = this.value.toUpperCase()" placeholder="Defeito Apresentado:"><?php echo htmlspecialchars($dados['defeito']); ?></textarea>
                        </div>
                         <div class="inputBox">
                            <input class="inputUser" type="text" name="preorc" value="<?php echo htmlspecialchars($dados['preorc']); ?>" required oninput="this.value = this.value.toUpperCase()" placeholder="Orçamento Prévio:"/>
                        </div>
                    </div>
                    
                    <div class="botoes">
                        <a class="navegar" href="entryorderdtbase.php">Voltar para a Lista</a>
                        <button type="submit" name="submit_update">
                            <i class="fas fa-save"></i> Salvar Correções
                        </button>
                    </div>
                </fieldset>
            </form>
        <?php else: ?>
            <p>Por favor, verifique o ID e tente novamente.</p>
        <?php endif; ?>
    </div>
</body>
</html>
