<?php
session_start();
unset($_SESSION['erro_login']);
include('conexao.php');

// Consulta os livros cadastrados
$sql_livros = "SELECT titulo, autor, data_cadastro FROM cadastroLivros ORDER BY data_cadastro DESC LIMIT 10";
$result_livros = $conn->query($sql_livros);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Biblioteca - Projeto Integrador III</title>
    <style>
        :root {
            --primary-color: #3498db;
            --primary-hover: #2980b9;
            --text-color: #333;
            --border-color: #ddd;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            color: var(--text-color);
            line-height: 1.6;
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

        /* teste navegção pelo teclado focus*/
        :focus {
            outline: 2px solid var(--primary-color);
            outline-offset: 2px;
        }
        button:focus, input:focus {
            box-shadow: 0 0 0 2px var(--primary-hover);
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
        }
        
        .login-form button:hover {
            background-color: var(--primary-hover);
        }
        .register-link {
            font-size: 12px; /* Tamanho reduzido (ajuste conforme necessário) */
            margin-left: 10px; /* Espaçamento à esquerda */
            white-space: nowrap; /* Evita quebra de linha */
        }

        .register-link a {
            color: var(--primary-color); /* Cor consistente com o tema */
            text-decoration: none; /* Remove sublinhado padrão */
        }

        .register-link a:hover {
            text-decoration: underline; /* Sublinhado ao passar o mouse */
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
}
    </style>
</head>
<body>
    <header class="header">
        <div class="logo">Biblioteca - Projeto Integrador III</div>
        
        <form class="login-form" action="autenticar.php" method="post">
            <input type="text" name="ra" placeholder="RA" required maxlength="7">
            <input type="password" name="senha" placeholder="Senha" required>
            <button type="submit">Entrar</button>
        <button type="submit">Entrar</button>
        
        <div class="register-link">
            <a href="cadastro_aluno.php">Não é cadastrado? Clique aqui</a>
        </div>
        </form>
    </header>
    
    <div class="main-content">
        <h2>Últimos Livros Cadastrados</h2>
        <table>
            <thead>
                <tr>
                    <th>Título</th>
                    <th>Autor</th>
                    <th>Data de Cadastro</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result_livros->num_rows > 0): ?>
                    <?php while ($livro = $result_livros->fetch_assoc()): ?>
                        <tr>
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
    </div>
    
    <footer class="footer">
        <p>Powered by DRP01-pji310-grupo-006</p>
    </footer>
</body>
</html>
<?php $conn->close(); ?>