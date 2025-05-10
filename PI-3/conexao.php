<?php
$host = "sql109.infinityfree.com";
$usuario = "if0_38781370";
$senha = "Univesp2025";
$banco = "if0_38781370_univesp";

// Cria a conexão
$conn = new mysqli($host, $usuario, $senha, $banco);

// Verifica a conexão
if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}

// Define o charset para UTF-8
$conn->set_charset("utf8mb4");
?>