<?php
session_start();

$host = "sql109.infinityfree.com";
$banco = "if0_38781370_univesp";
$usuario = "if0_38781370";
$senha = "Univesp2025";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$banco;charset=utf8", $usuario, $senha);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $ra = $_POST['ra'];
    $senha = $_POST['senha'];

    $stmt = $pdo->prepare("SELECT * FROM alunos WHERE ra = :ra");
    $stmt->bindParam(':ra', $ra);
    $stmt->execute();

    $aluno = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($aluno && password_verify($senha, $aluno['senha_hash'])) {
        $_SESSION['aluno'] = $aluno['nome'];
        header("Location: troca_livros.html");
        exit();
    } else {
        header("Location: login.html?status=error&message=" . urlencode("RA ou senha inválidos."));
        exit();
    }
} catch (Exception $e) {
    error_log("Erro no login: " . $e->getMessage());
    header("Location: login.html?status=error&message=" . urlencode("Erro ao conectar com o banco."));
    exit();
}
?>
