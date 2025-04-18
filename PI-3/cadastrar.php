<?php
// ConexoesComDb
$host = "localhost";
$usuario = "wsl";
$senha = "wsl";
$banco = "univesp";

$conexao = new mysqli($host, $usuario, $senha, $banco);

// PegandoDadosDoFormulario
$nome = $_POST['nome'];
$ra = $_POST['ra'];
$serie = $_POST['serie'];
$turma = $_POST['turma'];
$telefone = $_POST['telefone'];

// AbreSessaoSqleGrava
$sql = "INSERT INTO alunos (nome, ra, serie, turma, telefone) 
        VALUES (?, ?, ?, ?, ?)";

$stmt = $conexao->prepare($sql);
$stmt->bind_param("sssss", $nome, $ra, $serie, $turma, $telefone);

if ($stmt->execute()) {
    echo "Você foi cadastrado com sucesso!";
} else {
    echo "Erro ao te cadastrar: " . $conexao->error;
}

// FechandoAConexão
$stmt->close();
$conexao->close();
?>