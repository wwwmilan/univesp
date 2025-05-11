<?php
include('conexao.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Validação dos campos
        if ($_POST['senha'] !== $_POST['confirmar_senha']) {
            throw new Exception("As senhas não coincidem");
        }

        $dados = [
            'nome' => trim($_POST['nome']),
            'ra' => trim($_POST['ra']),
            'serie' => $_POST['serie'],
            'turma' => trim($_POST['turma']),
            'telefone' => trim($_POST['telefone']),
            'email' => trim($_POST['email']),
            'senha' => password_hash($_POST['senha'], PASSWORD_DEFAULT)
        ];

        // Verifica se RA já existe
        $stmt_check = $conn->prepare("SELECT id FROM alunos WHERE ra = ?");
        $stmt_check->bind_param("s", $dados['ra']);
        $stmt_check->execute();
        $stmt_check->store_result();
        
        if ($stmt_check->num_rows > 0) {
            throw new Exception("RA já cadastrado");
        }

        $stmt = $conn->prepare("INSERT INTO alunos 
            (nome, ra, serie, turma, telefone, email, senha_hash) 
            VALUES (?, ?, ?, ?, ?, ?, ?)");
        
        if (!$stmt) {
            throw new Exception("Erro na preparação: " . $conn->error);
        }
        
        $stmt->bind_param("sssssss",
            $dados['nome'],
            $dados['ra'],
            $dados['serie'],
            $dados['turma'],
            $dados['telefone'],
            $dados['email'],
            $dados['senha']);
        
        if ($stmt->execute()) {
            header("Location: index.php?status=success");
        } else {
            throw new Exception("Erro ao cadastrar: " . $stmt->error);
        }
        exit();
    } catch (Exception $e) {
        header("Location: cadastro_aluno.php?status=error&message=" . urlencode($e->getMessage()));
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Aluno</title>
    <style>
        :root {
            --primary-color: #3498db;
            --primary-hover: #2980b9;
            --error-color: #e74c3c;
            --text-color: #333;
            --border-color: #ddd;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 20px;
        }
        
        .form-container {
            max-width: 600px;
            margin: 30px auto;
            padding: 30px;
            background-color: white;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
            border-radius: 8px;
            position: relative;
        }
        
        h2 {
            color: var(--text-color);
            margin-bottom: 20px;
            text-align: center;
            padding-bottom: 10px;
            border-bottom: 2px solid var(--primary-color);
        }

        /* teste navegção pelo teclado focus*/
        :focus {
            outline: 2px solid var(--primary-color);
            outline-offset: 2px;
        }
        button:focus, input:focus {
            box-shadow: 0 0 0 2px var(--primary-hover);
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: var(--text-color);
        }
        
        .required-field::after {
            content: " *";
            color: var(--error-color);
        }
        
        input[type="text"],
        input[type="tel"],
        input[type="email"],
        input[type="password"],
        select {
            width: 100%;
            padding: 10px;
            border: 1px solid var(--border-color);
            border-radius: 4px;
            font-size: 16px;
            box-sizing: border-box;
        }
        
        input:focus,
        select:focus {
            border-color: var(--primary-color);
            outline: none;
            box-shadow: 0 0 5px rgba(52, 152, 219, 0.5);
        }
        
        .button-group {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin-top: 20px;
        }
        
        /* Estilo base para todos os botões */
        button, .button {
            display: inline-block;
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s;
            text-align: center;
            text-decoration: none;
        }
        
        /* Botão de submit */
        button[type="submit"] {
            background-color: var(--primary-color);
        }
        
        button[type="submit"]:hover {
            background-color: var(--primary-hover);
        }
        
        /* Botão Home */
        .button-home {
            background-color: #2ecc71;
        }
        
        .button-home:hover {
            background-color: #27ae60;
        }
        
        /* Botão Reset */
        .button-reset, button[type="reset"] {
            background-color: #95a5a6;
        }
        
        .button-reset:hover, button[type="reset"]:hover {
            background-color: #7f8c8d;
        }
        
        .error-message {
            color: var(--error-color);
            background-color: #fdecea;
            padding: 15px;
            border-radius: 4px;
            margin-bottom: 20px;
            text-align: center;
        }
        
        .success-message {
            color: #27ae60;
            background-color: #e8f5e9;
            padding: 15px;
            border-radius: 4px;
            margin-bottom: 20px;
            text-align: center;
        }
    </style>
    <script>
    // Função para máscara de telefone
    function mascaraTelefone(event) {
        let telefone = event.target.value.replace(/\D/g, '');
        let telefoneFormatado = '';
        
        if (telefone.length > 0) {
            telefoneFormatado += '(' + telefone.substring(0, 2);
        }
        if (telefone.length > 2) {
            telefoneFormatado += ')' + telefone.substring(2, 7);
        }
        if (telefone.length > 7) {
            telefoneFormatado += '-' + telefone.substring(7, 11);
        }
        
        event.target.value = telefoneFormatado;
    }
    
    // Validação do formulário
    function validarForm() {
        const senha = document.getElementById('senha').value;
        const confirmarSenha = document.getElementById('confirmar_senha').value;
        
        if (senha !== confirmarSenha) {
            alert('As senhas não coincidem!');
            return false;
        }
        
        return true;
    }
    
    // Configurações iniciais
    document.addEventListener('DOMContentLoaded', function() {
        // Máscara de telefone
        const telefoneInput = document.getElementById('telefone');
        telefoneInput.addEventListener('input', mascaraTelefone);
        
        // Tratamento de mensagens
        const urlParams = new URLSearchParams(window.location.search);
        const status = urlParams.get('status');
        
        if(status === 'success') {
            alert('Cadastro realizado com sucesso!');
            window.history.replaceState({}, document.title, window.location.pathname);
            setTimeout(function() {
                window.location.href = 'index.php';
            }, 1000);
        } else if(status === 'error') {
            const message = urlParams.get('message');
            alert('Erro no cadastro: ' + decodeURIComponent(message));
            window.history.replaceState({}, document.title, window.location.pathname);
        }
    });
    </script>
</head>
<body>
    <div class="form-container">
        <h2>Cadastro de Aluno</h2>
        
        <?php if (isset($_GET['status']) && $_GET['status'] === 'error'): ?>
            <div class="error-message">
                <?= isset($_GET['message']) ? htmlspecialchars(urldecode($_GET['message'])) : 'Erro no cadastro' ?>
            </div>
        <?php endif; ?>
        
        <form method="post" onsubmit="return validarForm()">
            <div class="form-group">
                <label for="nome" class="required-field">Nome completo</label>
                <input type="text" id="nome" name="nome" required>
            </div>
            
            <div class="form-group">
                <label for="ra" class="required-field">RA (7 dígitos)</label>
                <input type="text" id="ra" name="ra" required pattern="[0-9]{7}" title="Digite os 7 dígitos do RA">
            </div>
            
            <div class="form-group">
                <label for="serie" class="required-field">Série</label>
                <select id="serie" name="serie" required>
                    <option value="">Selecione o ano</option>
                    <option value="1">6º ano</option>
                    <option value="2">7º ano</option>
                    <option value="3">8º ano</option>
                    <option value="4">9º ano</option>
                </select>
            </div>
            
            <div class="form-group">
                <label for="turma" class="required-field">Turma</label>
                <input type="text" id="turma" name="turma" required>
            </div>
            
            <div class="form-group">
                <label for="telefone" class="required-field">Telefone</label>
                <input type="tel" id="telefone" name="telefone" required 
                       placeholder="(__) _____-____" maxlength="14">
            </div>
            
            <div class="form-group">
                <label for="email" class="required-field">E-mail</label>
                <input type="email" id="email" name="email" required>
            </div>
            
            <div class="form-group">
                <label for="senha" class="required-field">Senha (mínimo 6 caracteres)</label>
                <input type="password" id="senha" name="senha" required minlength="6">
            </div>
            
            <div class="form-group">
                <label for="confirmar_senha" class="required-field">Confirmar Senha</label>
                <input type="password" id="confirmar_senha" name="confirmar_senha" required minlength="6">
            </div>
            
            <div class="button-group">
                <button type="submit">Cadastrar</button>
                <button type="reset" class="button-reset">Limpar</button>
                <a href="index.php" class="button button-home">Home</a>
            </div>
        </form>
    </div>
</body>
    <footer class="footer">
        <p>Powered by DRP01-pji310-grupo-006</p>
    </footer>
</html>
<?php 
if (isset($conn)) {
    $conn->close();
}