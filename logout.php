<?php
session_start();

// Destruir todas as variáveis de sessão
$_SESSION = array();

// Se desejar destruir a sessão completamente, descomente a linha abaixo
// session_destroy();

// Redirecionar para a página de login ou para a página inicial
header("Location: index.php");
exit;
?>
