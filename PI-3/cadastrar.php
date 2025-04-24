<?php

// Conexões com o banco de dados (não mudar)
$host = "sql109.infinityfree.com";
$usuario = "if0_38781370";
$senha = "Univesp2025";
$banco = "if0_38781370_univesp";

try {
    $conexao = new mysqli($host, $usuario, $senha, $banco);

    if ($conexao->connect_error) {
        throw new Exception("Falha na conexão: " . $conexao->connect_error);
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (
            empty($_POST['nome']) || empty($_POST['ra']) || empty($_POST['serie']) ||
            empty($_POST['turma']) || empty($_POST['telefone']) || 
            empty($_POST['email']) || empty($_POST['senha']) || empty($_POST['confirmar_senha'])
        ) {
            throw new Exception("Todos os campos são obrigatórios");
        }

        $nome = $conexao->real_escape_string($_POST['nome']);
        $ra = $conexao->real_escape_string($_POST['ra']);
        $serie = $conexao->real_escape_string($_POST['serie']);
        $turma = $conexao->real_escape_string($_POST['turma']);
        $telefone = $conexao->real_escape_string($_POST['telefone']);
        $email = $conexao->real_escape_string($_POST['email']);
        $senha = $_POST['senha'];
        $confirmar_senha = $_POST['confirmar_senha'];

        // Validações
        if (!preg_match('/^[0-9]{7}$/', $ra)) {
            throw new Exception("RA deve conter 7 dígitos numéricos");
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception("E-mail inválido");
        }

        if (!preg_match('/^\([0-9]{2}\)[0-9]{4,5}-[0-9]{4}$/', $telefone)) {
            throw new Exception("Telefone deve estar no formato (xx)xxxxx-xxxx");
        }

        if ($senha !== $confirmar_senha) {
            throw new Exception("As senhas estão diferentes!!!");
        }

        if (strlen($senha) < 6) {
            throw new Exception("A senha deve ter pelo menos 6 caracteres");
        }

        // Verifica se o RA já está cadastrado
        $sql_verifica = "SELECT id FROM alunos WHERE ra = ?";
        $stmt_verifica = $conexao->prepare($sql_verifica);

        if (!$stmt_verifica) {
            throw new Exception("Tem certeza que deveria estar aqui? por favor chame o suporte! " . $conexao->error);
        }

        $stmt_verifica->bind_param("s", $ra);
        $stmt_verifica->execute();
        $stmt_verifica->store_result();

        if ($stmt_verifica->num_rows > 0) {
            throw new Exception("RA já cadastrado tente recuperar a senha");
        }

        $stmt_verifica->close();

        $senha_hash = password_hash($senha, PASSWORD_DEFAULT);

        // Insere os dados
        $sql = "INSERT INTO alunos (nome, ra, serie, turma, telefone, email, senha_hash)
                VALUES (?, ?, ?, ?, ?, ?, ?)";

        $stmt = $conexao->prepare($sql);
        if (!$stmt) {
            throw new Exception("Tem alguma coisa errada com: " . $conexao->error);
        }

        $stmt->bind_param("sssssss", $nome, $ra, $serie, $turma, $telefone, $email, $senha_hash);

        if ($stmt->execute()) {
            header("Location: cadastro_aluno.html?status=success");
            exit();
        } else {
            throw new Exception("Não conseguimos cadastrar: " . $stmt->error);
        }
    }

} catch (Exception $e) {
    error_log($e->getMessage());
    $mensagemAmigavel = "Algo deu errado. Confira os campos e tente novamente.";
    header("Location: cadastro_aluno.html?status=error&message=" . urlencode($mensagemAmigavel));
    exit();
} finally {
    if (isset($stmt)) $stmt->close();
    if (isset($conexao)) $conexao->close();
}
?>
