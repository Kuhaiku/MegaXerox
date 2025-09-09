<?php
include 'databaseconfig.php';

// Iniciar o buffer de saída para ver mensagens em tempo real
ob_implicit_flush(true);
ob_end_flush();

echo "<!DOCTYPE html><html lang='pt-br'><head><meta charset='UTF-8'><title>Arquivando Ordens Antigas</title>";
echo "<style>
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; background-color: #f4f4f4; padding: 20px; }
        .container { max-width: 800px; margin: auto; background-color: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        p { line-height: 1.6; color: #333; }
        .success { color: #28a745; font-weight: bold; }
        .error { color: #dc3545; font-weight: bold; }
        .info { color: #007bff; }
        a { display: inline-block; margin-top: 20px; padding: 10px 15px; background-color: #007bff; color: white; text-decoration: none; border-radius: 5px;}
      </style>";
echo "</head><body><div class='container'>";
echo "<h1>Processo de Arquivamento de Ordens de Entrada</h1>";

// 1. Criar a tabela de backup se ela não existir
$sql_create_table = "CREATE TABLE IF NOT EXISTS `entryorder_backup` LIKE `entryorder`;";

echo "<p class='info'>Verificando se a tabela de backup 'entryorder_backup' existe...</p>";
if ($conn->query($sql_create_table)) {
    echo "<p class='success'>Tabela de backup pronta para uso.</p>";
} else {
    echo "<p class='error'>Erro ao criar a tabela de backup: " . $conn->error . "</p>";
    exit;
}

// Inicia a transação
$conn->begin_transaction();

try {
    // 2. Copiar os dados com mais de 3 meses para a tabela de backup
    $sql_insert_select = "INSERT INTO `entryorder_backup` SELECT * FROM `entryorder` WHERE `data_entrada` < DATE_SUB(NOW(), INTERVAL 3 MONTH)";
    
    echo "<p class='info'>Copiando ordens com mais de 3 meses para a tabela de backup...</p>";
    $conn->query($sql_insert_select);
    $registros_movidos = $conn->affected_rows;
    echo "<p class='success'>$registros_movidos registros foram copiados com sucesso.</p>";

    // 3. Apagar os dados da tabela original
    if ($registros_movidos > 0) {
        $sql_delete = "DELETE FROM `entryorder` WHERE `data_entrada` < DATE_SUB(NOW(), INTERVAL 3 MONTH)";
        
        echo "<p class='info'>Apagando registros antigos da tabela principal...</p>";
        $conn->query($sql_delete);
        $registros_apagados = $conn->affected_rows;
        echo "<p class='success'>$registros_apagados registros antigos foram apagados da tabela principal.</p>";
    } else {
        echo "<p class='info'>Nenhum registro com mais de 3 meses encontrado para arquivar.</p>";
    }

    // Se tudo deu certo, confirma as alterações
    $conn->commit();
    echo "<p class='success'>Processo de arquivamento concluído com sucesso!</p>";

} catch (mysqli_sql_exception $exception) {
    // Se algo deu errado, desfaz tudo
    $conn->rollback();
    echo "<p class='error'>Ocorreu um erro durante a operação e todas as alterações foram revertidas. Detalhes: " . $exception->getMessage() . "</p>";
}

echo "<a href='../index.html'>Voltar para o Início</a>";
echo "</div></body></html>";

$conn->close();
?>
