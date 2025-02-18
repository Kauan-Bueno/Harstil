<?php
$servername = "45.152.44.154";
$username = "u451416913_2024grupo27";
$password = "Grupo27@123";
$dbname = "u451416913_2024grupo27";


try {
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    // Configurando PDO para lançar exceções em erros
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    //echo "Conexão bem sucedida!";
} catch(PDOException $e) {
    echo "Erro na conexão com o banco de dados: " . $e->getMessage();
}
?>