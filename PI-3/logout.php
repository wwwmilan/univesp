<?php
session_start();

// Limpa todos os dados da sessão
$_SESSION = array();

// Destroi a sessão
session_destroy();

// Redireciona para a página de login
header("Location: index.php");
exit();
?>