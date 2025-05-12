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
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Área do Aluno</title>
    
  <style>
:root {
    --primary-color: #3498db;
    --primary-hover: #2980b9;
    --error-color: #e74c3c;
    --text-color: #333;
    --border-color: #ddd;
    --table-header-bg: #f2f2f2;
    --table-row-even: #f9f9f9;
    --table-row-hover: #f1f1f1;
}

* {
    box-sizing: border-box;
    margin: 0;
    padding: 0;
}

/* teste navegção pelo teclado focus*/
:focus {
    outline: 2px solid var(--primary-color);
    outline-offset: 2px;
}
button:focus, input:focus {
    box-shadow: 0 0 0 2px var(--primary-hover);
}

body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background-color: #f5f5f5;
    margin: 0;
    padding: 0;
    color: var(--text-color);
    line-height: 1.6;
    display: flex;
    flex-direction: column;
    min-height: 100vh;
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
}

.login-form button:hover {
    background-color: var(--primary-hover);
}

/* User Info */
.user-info {
    display: flex;
    align-items: center;
    gap: 15px;
}

.user-greeting {
    font-weight: bold;
}

.user-details {
    font-size: 14px;
    color: #666;
}

/* Main Content */
.main-content {
    flex: 1;
    max-width: 1200px;
    margin: 30px auto;
    padding: 0 20px;
    width: 100%;
}

/* Tables */
.table-container {
    width: 100%;
    overflow-x: auto;
    margin: 20px 0;
    -webkit-overflow-scrolling: touch;
}

table {
    width: 100%;
    border-collapse: collapse;
    background-color: white;
    box-shadow: 0 0 10px rgba(0,0,0,0.1);
}

th, td {
    padding: 12px 15px;
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

tr:hover {
    background-color: var(--table-row-hover);
}

/* Footer */
.footer {
    text-align: center;
    padding: 15px;
    font-size: 13px;
    color: #777;
    background-color: white;
    border-top: 1px solid var(--border-color);
}

/* Responsividade */
@media (max-width: 768px) {
    .header {
        flex-direction: column;
        gap: 15px;
    }
    
    .login-form {
        width: 100%;
        flex-wrap: wrap;
    }
    
    .login-form input {
        flex: 1;
        min-width: 100%;
    }
    
    .user-info {
        flex-direction: column;
        align-items: flex-start;
        gap: 5px;
    }
    
    table {
        font-size: 14px;
    }
    
    th, td {
        padding: 8px 10px;
    }
}
  </style>
</head>
<body>
    <header class="header">
        <div class="user-info">
            <span class="user-greeting">Olá, <?= htmlspecialchars($aluno['nome']) ?></span>
        </div>
        <div class="message-container" style="text-align: center; margin: 20px 0;">
            <p style="font-size: 16px; color: var(--text-color);">
            <a href="cadastro_livro.php" style="color: var(--primary-color); text-decoration: none; font-weight: bold;">Cadastre seus livros</a>
            </p>
        </div>
        <div>
            <a href="logout.php" style="color: var(--primary-color); text-decoration: none;">Sair</a>
        </div>
    </header>
    
    
    <div class="main-content">
        <h2>Acervo Completo</h2>
        
        <table>
            <thead>
                <tr>
                    <th>Título</th>
                    <th>Subtítulo</th>
                    <th>Autor</th>
                    <th>Editora</th>
                    <th>Ano</th>
                    <th>Edição</th>
                    <th>ISBN</th>
                    <th>Data Cadastro</th>
                    <th>Cadastrado por</th>
                    <th>Telefone</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result_livros->num_rows > 0): ?>
                    <?php while ($livro = $result_livros->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($livro['titulo']) ?></td>
                            <td><?= htmlspecialchars($livro['subtitulo'] ?? '-') ?></td>
                            <td><?= htmlspecialchars($livro['autor']) ?></td>
                            <td><?= htmlspecialchars($livro['editora'] ?? '-') ?></td>
                            <td><?= htmlspecialchars($livro['ano'] ?? '-') ?></td>
                            <td><?= htmlspecialchars($livro['edicao'] ?? '-') ?></td>
                            <td><?= htmlspecialchars($livro['isbn'] ?? '-') ?></td>
                            <td><?= date('d/m/Y', strtotime($livro['data_cadastro'])) ?></td>
                            <td><?= htmlspecialchars($livro['aluno_nome'] ?? '-') ?></td>
                            <td><?= htmlspecialchars($livro['aluno_telefone'] ?? '-') ?></td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8">Nenhum livro cadastrado</td>
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