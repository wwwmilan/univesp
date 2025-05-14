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
    <!-- Meta Tags Essenciais -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- Meta Tags de Acessibilidade -->
    <meta http-equiv="Content-Language" content="pt-BR">
    <meta name="language" content="Portuguese">
    <meta name="description" content="Sistema de biblioteca acessível do Projeto Integrador III">
    <meta name="keywords" content="biblioteca, livros, acessível, projeto integrador">
    <meta name="author" content="DRP01-pji310-grupo-006">
    <meta name="robots" content="index, follow">
    <meta name="revisit-after" content="7 days">
    <meta name="color-scheme" content="light dark">
    <meta name="theme-color" content="#2c7be5" media="(prefers-color-scheme: light)">
    <meta name="theme-color" content="#1a68d1" media="(prefers-color-scheme: dark)">
    <meta name="supported-color-schemes" content="light dark">
    
    <title>Biblioteca - Projeto Integrador III</title>
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
        
        /* Skip Link de acessibilidade para pular para a area principal */
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
        
        /* Login Form */
        .login-form {
            display: flex;
            gap: 0.625rem;
            align-items: center;
        }
        
        .login-form input {
            padding: 0.5rem 0.75rem;
            border: 1px solid var(--border-color);
            border-radius: 0.25rem;
            font-size: 1rem;
        }
        
        .login-form button {
            background-color: var(--primary-color);
            color: white;
            border: none;
            padding: 0.5rem 0.9375rem;
            border-radius: 0.25rem;
            cursor: pointer;
            transition: background-color 0.2s;
            font-size: 1rem;
        }
        
        .login-form button:hover {
            background-color: var(--primary-hover);
        }
        
        .register-link {
            font-size: 0.75rem;
            margin-left: 0.625rem;
            white-space: nowrap;
        }

        .register-link a {
            color: var(--primary-color);
            text-decoration: none;
            font-size: inherit;
        }

        .register-link a:hover {
            text-decoration: underline;
        }
        
        /* Focus */
        :focus {
            outline: 0.1875rem solid var(--focus-color);
            outline-offset: 0.125rem;
        }
        
        button:focus, input:focus {
            box-shadow: 0 0 0 0.1875rem rgba(0, 123, 255, 0.25);
            border-color: var(--focus-color);
        }
        
        a:focus {
            text-decoration: underline;
            outline: none;
        }
        
        /* Visually hidden - esconder elementos e mostra nos eitores de telas*/
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
            max-width: 62.5rem;
            margin: 1.875rem auto;
            padding: 0 1.25rem;
        }
        
        h2 {
            font-size: 1.5rem;
            margin-bottom: 1.25rem;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 1.25rem 0;
            box-shadow: 0 0 0.625rem rgba(0,0,0,0.1);
            font-size: 1rem;
        }
        
        th, td {
            padding: 0.75rem 0.9375rem;
            text-align: left;
            border-bottom: 1px solid var(--border-color);
        }
        
        th {
            background-color: #f8f9fa;
            font-weight: 600;
            font-size: 1rem;
        }
        
        tr:hover {
            background-color: #f1f1f1;
        }
        
        tr:focus-within {
            background-color: #e9ecef;
            outline: 0.125rem solid var(--focus-color);
        }
        
        /* Rodapé */
        .footer {
            text-align: center;
            padding: 0.5rem;
            font-size: 0.75rem;
            color: #777;
            background-color: white;
            border-top: 1px solid var(--border-color);
        }
        
        /* Responsividade */
        @media (max-width: 48rem) {
            .login-form {
                flex-direction: column;
                align-items: stretch;
                gap: 0.5rem;
            }
            
            :focus {
                outline-offset: 0.0625rem;
            }
            
            html {
                font-size: 100%;
            }
            
            .logo {
                font-size: 1.25rem;
            }
            
            .login-form input, 
            .login-form button {
                font-size: 1rem;
            }
        }
        
        /* implementar para telas maiores */
        
        @media screen and (min-width: 1200px) {
            html {
                font-size: 110%;
            }
        }
    </style>
</head>
<body>
    <a href="#main-content" class="skip-link">Pular para conteúdo principal</a>
    
    <header class="header">
        <div class="logo">Biblioteca - Projeto Integrador III</div>
        
        <form class="login-form" action="autenticar.php" method="post" aria-label="Formulário de login">
            <label for="ra" class="sr-only">RA (Registro Acadêmico)</label>
            <input type="text" id="ra" name="ra" placeholder="RA" required maxlength="7" aria-required="true" dir="ltr">
            
            <label for="senha" class="sr-only">Senha</label>
            <input type="password" id="senha" name="senha" placeholder="Senha" required aria-required="true" dir="ltr">
            
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
                            <td dir="ltr"><?= date('d/m/Y', strtotime($livro['data_cadastro'])) ?></td>
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