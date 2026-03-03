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

        // Validação do RA - deve ter exatamente 7 dígitos
        if (!preg_match('/^\d{7}$/', $dados['ra'])) {
            throw new Exception("O RA deve conter exatamente 7 dígitos numéricos");
        }

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
    <meta http-equiv="Content-Language" content="pt-BR">
    <meta name="description" content="Formulário de cadastro de aluno">
    <meta name="theme-color" content="#3498db">
    <meta name="color-scheme" content="light dark">
    
    <title>Cadastro de Aluno</title>
    <style>
        :root {
            --primary-color: #2c7be5;
            --primary-hover: #1a68d1;
            --text-color: #2d3748;
            --border-color: #ddd;
            --focus-color: #0056b3;
            --base-font-size: 1rem;
        }
        
        html {
            font-size: 100%;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            color: var(--text-color);
            line-height: 1.6;
            background-color: #fff;
            font-size: var(--base-font-size);
            text-align: start;
        }
        
        .skip-link {
            position: absolute;
            top: -40px;
            left: 0;
            background: #000;
            color: white;
            padding: 0.5rem;
            z-index: 100;
            transition: top 0.3s;
            font-size: 1rem;
        }
        
        .skip-link:focus {
            top: 0;
        }
        
        .header {
            background-color: white;
            padding: 1rem 1.25rem;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .logo {
            font-size: 1.5rem;
            font-weight: bold;
            color: var(--primary-color);
        }
        
        /* Estilo específico para o campo RA com máscara */
        .ra-mask {
            position: relative;
        }
        
        .ra-mask input {
            padding-left: 1.4em;
            letter-spacing: 1.4em;
            font-family: monospace;
            font-size: 1.2em;
        }
        
        .ra-mask::before {
            content: "_______";
            position: absolute;
            left: 10px;
            top: 50%;
            transform: translateY(-50%);
            color: #999;
            font-family: monospace;
            letter-spacing: 1.4em;
            pointer-events: none;
            font-size: 1.2em;
        }
        
        .form-container {
            max-width: 50rem;
            margin: 2rem auto;
            padding: 1.5rem;
            background: white;
            box-shadow: 0 0 1rem rgba(0,0,0,0.1);
            border-radius: 0.5rem;
        }
        
        .form-group {
            margin-bottom: 1.5rem;
        }
        
        label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
        }
        
        .required-field::after {
            content: " *";
            color: #e74c3c;
        }
        
        input[type="text"],
        input[type="tel"],
        input[type="email"],
        input[type="password"],
        select {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid var(--border-color);
            border-radius: 0.375rem;
            font-size: 1rem;
            box-sizing: border-box;
            transition: border-color 0.2s;
        }
        
        input:focus,
        select:focus {
            border-color: var(--primary-color);
            outline: none;
            box-shadow: 0 0 0 0.1875rem rgba(44, 123, 229, 0.25);
        }
        
        .button-group {
            display: flex;
            justify-content: flex-end;
            gap: 1rem;
            margin-top: 2rem;
        }
        
        button, .button {
            display: inline-block;
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 0.375rem;
            cursor: pointer;
            font-size: 1rem;
            transition: background-color 0.2s;
            text-align: center;
            text-decoration: none;
        }
        
        button[type="submit"] {
            background-color: var(--primary-color);
        }
        
        button[type="submit"]:hover {
            background-color: var(--primary-hover);
        }
        
        .button-home {
            background-color: #2ecc71;
        }
        
        .button-home:hover {
            background-color: #27ae60;
        }
        
        .button-reset, button[type="reset"] {
            background-color: #95a5a6;
        }
        
        .button-reset:hover, button[type="reset"]:hover {
            background-color: #7f8c8d;
        }
        
        .error-message {
            color: #e74c3c;
            background-color: #fdecea;
            padding: 1rem;
            border-radius: 0.375rem;
            margin-bottom: 1.5rem;
            text-align: center;
        }
        
        .footer {
            text-align: center;
            padding: 0.5rem;
            font-size: 0.75rem;
            color: #777;
            background-color: white;
            border-top: 1px solid var(--border-color);
        }
        
        @media (max-width: 48rem) {
            .form-container {
                margin: 1rem;
                padding: 1rem;
            }
            
            .button-group {
                flex-direction: column;
            }
            
            button, .button {
                width: 100%;
            }
            
            .ra-mask input {
                padding-left: 1.2em;
                letter-spacing: 1.2em;
                font-size: 1em;
            }
            
            .ra-mask::before {
                letter-spacing: 1.2em;
                font-size: 1em;
            }
        }
    </style>
    <script>
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
    
    function mascaraRA(event) {
        // Permite apenas números
        let ra = event.target.value.replace(/\D/g, '');
        
        // Limita a 7 dígitos
        ra = ra.substring(0, 7);
        
        // Atualiza o valor do campo
        event.target.value = ra;
    }
    
    function validarForm() {
        const ra = document.getElementById('ra').value;
        const senha = document.getElementById('senha').value;
        const confirmarSenha = document.getElementById('confirmar_senha').value;
        
        if (!/^\d{7}$/.test(ra)) {
            alert('O RA deve conter exatamente 7 dígitos numéricos!');
            return false;
        }
        
        if (senha !== confirmarSenha) {
            alert('As senhas não coincidem!');
            return false;
        }
        
        return true;
    }
    
    document.addEventListener('DOMContentLoaded', function() {
        const telefoneInput = document.getElementById('telefone');
        telefoneInput.addEventListener('input', mascaraTelefone);
        
        const raInput = document.getElementById('ra');
        raInput.addEventListener('input', mascaraRA);
        
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
    <header class="header">
        <div class="logo">Sistema Escolar</div>
    </header>

    <main class="main-content">
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
                
                <div class="form-group ra-mask">
                    <label for="ra" class="required-field">RA (7 dígitos)</label>
                    <input type="text" id="ra" name="ra" required pattern="\d{7}" title="Digite exatamente 7 dígitos numéricos" maxlength="7">
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
    </main>

    <footer class="footer">
        <p>Powered by DRP01-pji310-grupo-006</p>
    </footer>
</body>
</html>
<?php 
if (isset($conn)) {
    $conn->close();
}