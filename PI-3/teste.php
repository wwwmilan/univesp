<?php
session_start();
include('conexao.php');

if (!isset($_SESSION['aluno'])) {
    header("Location: index.php");
    exit();
}

$aluno = $_SESSION['aluno'];
$sql_livros = "SELECT 
    l.*, 
    a.nome AS aluno_nome, 
    a.telefone AS aluno_telefone 
    FROM cadastroLivros l
    JOIN alunos a ON l.id_aluno = a.id
    ORDER BY l.data_cadastro DESC";
$result_livros = $conn->query($sql_livros);
?>

<!DOCTYPE html>
<html lang="pt-BR" dir="ltr">
<head>
    <!-- Meta Tags de Acessibilidade -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Language" content="pt-BR">
    <meta name="description" content="Área do aluno - Sistema de biblioteca acessível">
    <meta name="theme-color" content="#3498db">
    <meta name="color-scheme" content="light dark">
    
    <title>Área do Aluno - Biblioteca</title>
    
    <style>
:root {
    --primary-color: #3498db;
    --primary-hover: #2980b9;
    --error-color: #e74c3c;
    --text-color: #2d3748;
    --border-color: #ddd;
    --table-header-bg: #f2f2f2;
    --table-row-even: #f9f9f9;
    --table-row-hover: #f1f1f1;
    --focus-color: #0056b3;
    --base-font-size: 1rem;
}
        
html {
    font-size: 100%;
}

* {
    box-sizing: border-box;
    margin: 0;
    padding: 0;
}

/* Skip Link - Acessibilidade */
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

/* Navegação por teclado */
:focus {
    outline: 0.1875rem solid var(--focus-color);
    outline-offset: 0.125rem;
}

button:focus, input:focus, a:focus {
    box-shadow: 0 0 0 0.1875rem rgba(0, 123, 255, 0.25);
    border-color: var(--focus-color);
}

/* Elementos visualmente ocultos */
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

body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background-color: #f5f5f5;
    margin: 0;
    padding: 0;
    color: var(--text-color);
    font-size: var(--base-font-size);
    line-height: 1.6;
    display: flex;
    flex-direction: column;
    min-height: 100vh;
}

/* Header */
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

/* User Info */
.user-info {
    display: flex;
    align-items: center;
    gap: 0.9375rem;
}

.user-greeting {
    font-weight: bold;
}

.user-details {
    font-size: 0.875rem;
    color: #666;
}

/* Main Content */
.main-content {
    flex: 1;
    max-width: 75rem;
    margin: 1.875rem auto;
    padding: 0 1.25rem;
    width: 100%;
}

/* Tables */
.table-container {
    width: 100%;
    overflow-x: auto;
    margin: 1.25rem 0;
    -webkit-overflow-scrolling: touch;
}

table {
    width: 100%;
    border-collapse: collapse;
    background-color: white;
    box-shadow: 0 0 0.625rem rgba(0,0,0,0.1);
}

th, td {
    padding: 0.75rem 0.9375rem;
    text-align: left;
    border-bottom: 1px solid var(--border-color);
}

th {
    background-color: var(--table-header-bg);
    font-weight: 600;
}

tr:nth-child(even) {
    background-color: var(--table-row-even);
}

tr:hover, tr:focus-within {
    background-color: var(--table-row-hover);
}

/* Footer */
.footer {
    text-align: center;
    padding: 0.9375rem;
    font-size: 0.8125rem;
    color: #777;
    background-color: white;
    border-top: 1px solid var(--border-color);
}

/* Links */
a {
    color: var(--primary-color);
    text-decoration: none;
}

a:hover, a:focus {
    text-decoration: underline;
}

/* Responsividade */
@media (max-width: 48rem) {
    .header {
        flex-direction: column;
        gap: 0.9375rem;
    }
    
    .user-info {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.3125rem;
    }
    
    table {
        font-size: 0.875rem;
    }
    
    th, td {
        padding: 0.5rem 0.625rem;
    }
    
    :focus {
        outline-offset: 0.0625rem;
    }
}
    </style>
</head>
<body>
    <!-- Skip Link para acessibilidade -->
    <a href="#main-content" class="skip-link">Pular para conteúdo principal</a>
    
    <header class="header">
        <div class="user-info">
            <span class="user-greeting">Olá, <?= htmlspecialchars($aluno['nome']) ?></span>
        </div>
        <div class="message-container" style="text-align: center; margin: 1.25rem 0;">
            <p style="font-size: 1rem; color: var(--text-color);">
                <a href="cadastro_livro.php" style="color: var(--primary-color); text-decoration: none; font-weight: bold;">Cadastre seus livros</a>
            </p>
        </div>
        <div>
            <a href="logout.php" style="color: var(--primary-color); text-decoration: none;" aria-label="Sair do sistema">Sair</a>
        </div>
    </header>
    
    <main id="main-content" class="main-content" tabindex="-1">
        <h1>Acervo Completo</h1>
        
        <div class="table-container">
            <table aria-describedby="table-desc">
                <caption id="table-desc" class="sr-only">Tabela com todos os livros cadastrados no sistema</caption>
                <thead>
                    <tr>
                        <th scope="col">Título</th>
                        <th scope="col">Subtítulo</th>
                        <th scope="col">Autor</th>
                        <th scope="col">Editora</th>
                        <th scope="col">Ano</th>
                        <th scope="col">Edição</th>
                        <th scope="col">ISBN</th>
                        <th scope="col">Data Cadastro</th>
                        <th scope="col">Cadastrado por</th>
                        <th scope="col">Telefone</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result_livros->num_rows > 0): ?>
                        <?php while ($livro = $result_livros->fetch_assoc()): ?>
                            <tr tabindex="0">
                                <td><?= htmlspecialchars($livro['titulo']) ?></td>
                                <td><?= htmlspecialchars($livro['subtitulo'] ?? '-') ?></td>
                                <td><?= htmlspecialchars($livro['autor']) ?></td>
                                <td><?= htmlspecialchars($livro['editora'] ?? '-') ?></td>
                                <td><?= htmlspecialchars($livro['ano'] ?? '-') ?></td>
                                <td><?= htmlspecialchars($livro['edicao'] ?? '-') ?></td>
                                <td dir="ltr"><?= htmlspecialchars($livro['isbn'] ?? '-') ?></td>
                                <td dir="ltr"><?= date('d/m/Y', strtotime($livro['data_cadastro'])) ?></td>
                                <td><?= htmlspecialchars($livro['aluno_nome'] ?? '-') ?></td>
                                <td dir="ltr"><?= htmlspecialchars($livro['aluno_telefone'] ?? '-') ?></td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="10">Nenhum livro cadastrado</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </main>
    
    <footer class="footer">
        <p>Powered by DRP01-pji310-grupo-006</p>
    </footer>
</body>
</html>
<?php $conn->close(); ?>