<?php

// ConexoesComDb
$host = "sql109.infinityfree.com";
$usuario = "if0_38781370";
$senha = "Univesp2025";
$banco = "if0_38781370_univesp";

try {
    $conexao = new mysqli($host, $usuario, $senha, $banco);
    
    // Verificar conexão
    if ($conexao->connect_error) {
        throw new Exception("Falha na conexão: " . $conexao->connect_error);
    }

    // Verifica se o formulário foi submetido
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (empty($_POST['nome']) || empty($_POST['ra']) || empty($_POST['serie']) || 
            empty($_POST['turma']) || empty($_POST['telefone']) || empty($_POST['senha']) || empty($_POST['confirmar_senha'])) {
            throw new Exception("Todos os campos são obrigatórios");
        }
        
        $nome = $conexao->real_escape_string($_POST['nome']);
        $ra = $conexao->real_escape_string($_POST['ra']);
        $serie = $conexao->real_escape_string($_POST['serie']);
        $turma = $conexao->real_escape_string($_POST['turma']);
        $telefone = $conexao->real_escape_string($_POST['telefone']);
        $senha_hash = password_hash($_POST['senha'], PASSWORD_DEFAULT);

        // conferir se o RA tem 7 digitos
        if (!preg_match('/^[0-9]{7}$/', $ra)) {
            throw new Exception("RA deve conter 7 dígitos numéricos");
        }

        // conferir se o telefone esta certo
        if (!preg_match('/^\([0-9]{2}\)[0-9]{4,5}-[0-9]{4}$/', $telefone)) {
            throw new Exception("Telefone deve estar no formato (xx)xxxx-xxxx ou (xx)xxxxx-xxxx");

        if (password_verify($senha_digitada, $hash_do_banco)) {
            } else {
            }

        // conferir se confirmou a senha igual
        if ($senha !== $confirmar_senha) {
            throw new Exception("As senhas não coincidem");
        }
    
        // conferir se a senha tem 6 caracteres
        if (strlen($senha) < 6) {
            throw new Exception("A senha deve ter pelo menos 6 caracteres");
        }

         // conferir
            $sql_verifica = "SELECT id FROM alunos WHERE ra = ?";
            $stmt_verifica = $conexao->prepare($sql_verifica);
            
            if (!$stmt_verifica) {
                throw new Exception("Erro na preparação da query de verificação: " . $conexao->error);
            }
    
            $stmt_verifica->bind_param("s", $ra);
            $stmt_verifica->execute();
            $stmt_verifica->store_result();
            
            if ($stmt_verifica->num_rows > 0) {
                throw new Exception("RA já cadastrado no sistema");
            }
            $stmt_verifica->close();
        }

        // Prepara e executa a query
        $sql = "INSERT INTO alunos (nome, ra, serie, turma, telefone) 
                VALUES (?, ?, ?, ?, ?)";

        $stmt = $conexao->prepare($sql);
        if (!$stmt) {
            throw new Exception("Erro na preparação da query: " . $conexao->error);
        }

        $stmt->bind_param("sssss", $nome, $ra, $serie, $turma, $telefone);

        if ($stmt->execute()) {
            header("Location: cadastro_aluno.html?status=success");
            exit();
        } else {
            throw new Exception("Erro ao cadastrar: " . $stmt->error);
        }
    }
} catch (Exception $e) {
    // Log do erro (em produção, grave em um arquivo de log)
    error_log($e->getMessage());
    
    // Redireciona com mensagem de erro
    header("Location: cadastro_aluno.html?status=error&message=" . urlencode($e->getMessage()));
    exit();
} finally {
    // Fechando conexões
    if (isset($stmt)) $stmt->close();
    if (isset($conexao)) $conexao->close();
}
?>