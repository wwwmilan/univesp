<?php
session_start();
unset($_SESSION['erro_login']);
include('conexao.php');

// Consulta os livros cadastrados
$sql_livros = "SELECT titulo, autor, data_cadastro FROM cadastroLivros ORDER BY data_cadastro DESC LIMIT 10";
$result_livros = $conn->query($sql_livros);
?>

<!DOCTYPE html>

<html lang="pt-BR" dir="ltr">
    
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Sistema de biblioteca do Projeto Integrador III">
    <meta name="theme-color" content="#3498db">
    <title>Biblioteca - Projeto Integrador III</title>
    <style>
        :root {
            --primary-color: #2c7be5; /* Azul com melhor contraste */
            --primary-hover: #1a68d1;
            --text-color: #2d3748; /* Preto mais escuro */
            --border-color: #ddd;
            --focus-color: #0056b3;
        }
        
        html {
            font-size: 100%; /* teste para o redimensionamento do navegador  */
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            color: var(--text-color);
            font-size: 1rem; /* teste para o redimensionamento do navegador  */
            line-height: 1.6;
            background-color: #fff;
        }
        
        /* Skip Link - Acessibilidade */
        .skip-link {
            position: absolute;
            top: -40px;
            left: 0;
            background: #000;
            color: white;
            padding: 8px;
            z-index: 100;
            transition: top 0.3s;
        }
        
        .skip-link:focus {
            top: 0;
        }
        
        /* Header */
        .header {
            background-color: white;
            padding: 15px 20px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .logo {
            font-size: 24px;
            font-weight: bold;
            color: var(--primary-color);
        }
        
        /* Login Form */
        .login-form {
            display: flex;
            gap: 10px;
            align-items: center;
        }
        
        .login-form input {
            padding: 8px 12px;
            border: 1px solid var(--border-color);
            border-radius: 4px;
        }
        
        .login-form button {
            background-color: var(--primary-color);
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.2s;
        }
        
        .login-form button:hover {
            background-color: var(--primary-hover);
        }
        
        .register-link {
            font-size: 12px;
            margin-left: 10px;
            white-space: nowrap;
        }

        .register-link a {
            color: var(--primary-color);
            text-decoration: none;
        }

        .register-link a:hover {
            text-decoration: underline;
        }
        
        /* Focus Styles - Melhorias para navegação por teclado */
        :focus {
            outline: 3px solid var(--focus-color);
            outline-offset: 2px;
        }
        
        button:focus, input:focus {
            box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.25);
            border-color: var(--focus-color);
        }
        
        a:focus {
            text-decoration: underline;
            outline: none;
        }
        
        /* Visually hidden - para elementos acessíveis apenas a leitores de tela */
        .sr-only {
            position: absolute;
            width: 1px;
            height: 1px;
            padding: 0;
            margin: -1px;
            overflow: hidden;
            clip: rect(0, 0, 0, 0);
            white-space: nowrap;
            border: 0;
        }
        
        /* Main Content */
        .main-content {
            max-width: 1000px;
            margin: 30px auto;
            padding: 0 20px;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        
        th, td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid var(--border-color);
        }
        
        th {
            background-color: #f8f9fa;
            font-weight: 600;
        }
        
        tr:hover {
            background-color: #f1f1f1;
        }
        
        /* Links na tabela - melhor foco */
        tr:focus-within {
            background-color: #e9ecef;
            outline: 2px solid var(--focus-color);
        }
        
        /* Rodapé */
        .footer {
            text-align: center;
            padding: 8px;
            font-size: 12px;
            color: #777;
            background-color: white;
            border-top: 1px solid var(--border-color);
        }
        
        /* Responsividade */
        @media (max-width: 768px) {
            .login-form {
                flex-direction: column;
                align-items: stretch;
                gap: 8px;
            }
            
            :focus {
                outline-offset: 1px;
            }
        }
    </style>
</head>
<body>
    <!-- Link para pular para conteúdo principal -->
    <a href="#main-content" class="skip-link">Pular para conteúdo principal</a>
    
    <header class="header">
        <div class="logo">Biblioteca - Projeto Integrador III</div>
        
        <form class="login-form" action="autenticar.php" method="post" aria-label="Formulário de login">
            <label for="ra" class="sr-only">RA (Registro Acadêmico)</label>
            <input type="text" id="ra" name="ra" placeholder="RA" required maxlength="7" aria-required="true">
            
            <label for="senha" class="sr-only">Senha</label>
            <input type="password" id="senha" name="senha" placeholder="Senha" required aria-required="true">
            
            <button type="submit" aria-label="Entrar no sistema">Entrar</button>
            
            <div class="register-link">
                <a href="cadastro_aluno.php" aria-label="Cadastrar novo aluno">Não é cadastrado? Clique aqui</a>
            </div>
        </form>
    </header>
    
    <main id="main-content" class="main-content" tabindex="-1">
        <h2>Últimos Livros Cadastrados</h2>
        <table aria-label="Últimos livros cadastrados">
            <thead>
                <tr>
                    <th scope="col">Título</th>
                    <th scope="col">Autor</th>
                    <th scope="col">Data de Cadastro</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result_livros->num_rows > 0): ?>
                    <?php while ($livro = $result_livros->fetch_assoc()): ?>
                        <tr tabindex="0" aria-label="Livro <?= htmlspecialchars($livro['titulo']) ?> de <?= htmlspecialchars($livro['autor']) ?>">
                            <td><?= htmlspecialchars($livro['titulo']) ?></td>
                            <td><?= htmlspecialchars($livro['autor']) ?></td>
                            <td><?= date('d/m/Y', strtotime($livro['data_cadastro'])) ?></td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="3">Nenhum livro cadastrado ainda</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </main>
    
    <footer class="footer">
        <p>Powered by DRP01-pji310-grupo-006</p>
    </footer>
</body>
</html>
<?php $conn->close(); ?>