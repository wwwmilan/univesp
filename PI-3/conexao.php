<?php

$host = "sql109.infinityfree.com";
$usuario = "if0_38781370";
$senha = "Univesp2025";
$banco = "if0_38781370_univesp";
$conn = new mysqli($host, $usuario, $senha, $banco);


if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}
?>
