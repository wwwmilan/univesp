<?php
session_start();
include('conexao.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ra = $_POST['ra'] ?? '';
    $senha = $_POST['senha'] ?? '';
    
    // Validação básica
    if (empty($ra) || empty($senha)) {
        header("Location: error.html?reason=empty_fields");
        exit();
    }

    try {
        $stmt = $conn->prepare("SELECT id, nome, ra, serie, turma, senha_hash FROM alunos WHERE ra = ?");
        $stmt->bind_param("s", $ra);
        
        if (!$stmt->execute()) {
            throw new Exception("Erro na consulta ao banco de dados");
        }
        
        $result = $stmt->get_result();
        
        if ($result->num_rows === 1) {
            $aluno = $result->fetch_assoc();
            
            // Verifica a senha
            if (password_verify($senha, $aluno['senha_hash'])) {
                // Login bem-sucedido
                $_SESSION['aluno'] = [
                    'id' => $aluno['id'],
                    'nome' => $aluno['nome'],
                    'ra' => $aluno['ra'],
                    'serie' => $aluno['serie'],
                    'turma' => $aluno['turma']
                ];
                
                header("Location: trocalivros.php");
                exit();
            }
        }
        
        // Login falhou - redireciona para error.html
        header("Location: error.html?reason=invalid_credentials");
        exit();
        
    } catch (Exception $e) {
        header("Location: error.html?reason=server_error");
        exit();
    }
}

// Se acessado diretamente sem POST
header("Location: index.php");
exit();
?>