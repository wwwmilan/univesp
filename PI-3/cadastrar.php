<?php
// ConexoesComDb
$host = "localhost";
$usuario = "wsl";
$senha = "wsl";
$banco = "univesp.sql";

$conexao = new mysqli($host, $usuario, $senha, $banco);

// Verifica se o formulário foi submetido
if ($_SERVER["REQUEST_METHOD"] == "POST") {
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
        // Redireciona de volta para o formulário com mensagem de sucesso
        header("Location: cadastro_aluno.html?status=success");
        exit();
    } else {
        // Redireciona com mensagem de erro
        header("Location: cadastro_aluno.html?status=error&message=" . urlencode($conexao->error));
        exit();
    }

    // FechandoAConexão
    $stmt->close();
}

$conexao->close();
?>