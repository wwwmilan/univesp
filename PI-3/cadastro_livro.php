<?php
session_start();
if (!isset($_SESSION['aluno'])) {
    header("Location: index.php");
    exit();
}

include('conexao.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Validação dos campos obrigatórios
        if (empty($_POST['titulo']) || empty($_POST['autor'])) {
            throw new Exception("Título e Autor são campos obrigatórios");
        }

        // Preparação dos dados
        $dados = [
            'titulo' => trim($_POST['titulo']),
            'subtitulo' => !empty($_POST['subtitulo']) ? trim($_POST['subtitulo']) : null,
            'autor' => trim($_POST['autor']),
            'editora' => !empty($_POST['editora']) ? trim($_POST['editora']) : null,
            'ano' => !empty($_POST['ano']) ? (int)$_POST['ano'] : null,
            'edicao' => !empty($_POST['edicao']) ? trim($_POST['edicao']) : null,
            'isbn' => !empty($_POST['isbn']) ? trim($_POST['isbn']) : null,
            'descricao' => !empty($_POST['descricao']) ? trim($_POST['descricao']) : null,
            'id_aluno' => $_SESSION['aluno']['id']
        ];

        // Prepara a query SQL
        $sql = "INSERT INTO cadastroLivros 
                (titulo, subtitulo, autor, editora, ano, edicao, isbn, descricao, id_aluno, data_cadastro) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";
        
        $stmt = $conn->prepare($sql);
        
        if (!$stmt) {
            throw new Exception("Erro na preparação da query: " . $conn->error);
        }

        // Bind dos parâmetros
        $stmt->bind_param("ssssisssi",
            $dados['titulo'],
            $dados['subtitulo'],
            $dados['autor'],
            $dados['editora'],
            $dados['ano'],
            $dados['edicao'],
            $dados['isbn'],
            $dados['descricao'],
            $dados['id_aluno']);
        
        // Executa a query
        if ($stmt->execute()) {
            // Redireciona para trocalivros.php com mensagem de sucesso
            header("Location: trocalivros.php?success=1");
            exit();
        } else {
            throw new Exception("Erro ao executar a query: " . $stmt->error);
        }
    } catch (Exception $e) {
        // Redireciona com mensagem de erro
        header("Location: cadastro_livro.php?error=" . urlencode($e->getMessage()));
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR" dir="ltr">
<head>
    <!-- Meta Tags de Acessibilidade -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Language" content="pt-BR">
    <meta name="description" content="Formulário para cadastro de livros">
    <meta name="theme-color" content="#3498db">
    <meta name="color-scheme" content="light dark">
    
    <title>Cadastrar Livro - Biblioteca</title>
    <style>
        :root {
            --primary-color: #3498db;
            --primary-hover: #2980b9;
            --error-color: #e74c3c;
            --success-color: #27ae60;
            --text-color: #2d3748;
            --border-color: #ddd;
            --focus-color: #0056b3;
            --base-font-size: 1rem;
        }
        
        html {
            font-size: 100%;
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
        
        :focus {
            outline: 0.1875rem solid var(--focus-color);
            outline-offset: 0.125rem;
        }

        button:focus, input:focus, textarea:focus, select:focus {
            box-shadow: 0 0 0 0.1875rem rgba(0, 123, 255, 0.25);
            border-color: var(--focus-color);
        }

        
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
            padding: 1.25rem;
            font-size: var(--base-font-size);
            line-height: 1.6;
        }
        
        .form-container {
            max-width: 37.5rem;
            margin: 1.875rem auto;
            padding: 1.875rem;
            background-color: white;
            box-shadow: 0 0 0.9375rem rgba(0,0,0,0.1);
            border-radius: 0.5rem;
        }
        
        h1 {
            color: var(--text-color);
            margin-bottom: 1.25rem;
            text-align: center;
            padding-bottom: 0.625rem;
            border-bottom: 0.125rem solid var(--primary-color);
            font-size: 1.5rem;
        }

        .form-group {
            margin-bottom: 1.25rem;
        }
        
        label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: var(--text-color);
        }
        
        input[type="text"],
        input[type="number"],
        textarea {
            width: 100%;
            padding: 0.625rem;
            border: 0.0625rem solid var(--border-color);
            border-radius: 0.25rem;
            font-size: 1rem;
        }
        
        textarea {
            min-height: 6.25rem;
            resize: vertical;
        }
        .button-container {
            display: flex;
            gap: 10px;
            margin-top: 20px;
        }
        
        .button {
            flex: 1;
            padding: 12px 20px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            transition: all 0.3s;
            text-align: center;
            text-decoration: none;
            border: none;
        }
        
        .submit-button {
            background-color: var(--primary-color);
            color: white;
        }
        
        .submit-button:hover, .submit-button:focus {
            background-color: var(--primary-hover);
        }
        
        .cancel-button {
            background-color: #95a5a6;
            color: white;
        }
        
        .cancel-button:hover, .cancel-button:focus {
            background-color: #7f8c8d;
        }
        
        button:hover, button:focus {
            background-color: var(--primary-hover);
        }
        
        .error-message {
            color: var(--error-color);
            background-color: #fdecea;
            padding: 0.9375rem;
            border-radius: 0.25rem;
            margin-bottom: 1.25rem;
            text-align: center;
        }
        
        .success-message {
            color: var(--success-color);
            background-color: #e8f5e9;
            padding: 0.9375rem;
            border-radius: 0.25rem;
            margin-bottom: 1.25rem;
            text-align: center;
        }
        
        .required-field::after {
            content: " *";
            color: var(--error-color);
        }

        .footer {
            text-align: center;
            padding: 0.9375rem;
            font-size: 0.8125rem;
            color: #777;
            background-color: white;
            border-top: 0.0625rem solid var(--border-color);
            margin-top: 1.25rem;
        }

        @media (max-width: 48rem) {
            .form-container {
                padding: 1.25rem;
            }
        }
    </style>
</head>
<body>
    <!-- Skip Link para acessibilidade -->
    <a href="#main-content" class="skip-link">Pular para conteúdo principal</a>
    
    <main id="main-content" class="form-container" tabindex="-1">
        <h1>Cadastrar Novo Livro</h1>
        
        <?php if (isset($_GET['error'])): ?>
            <div class="error-message" role="alert">
                <?= htmlspecialchars(urldecode($_GET['error'])) ?>
            </div>
        <?php endif; ?>
        
        <form method="post" aria-label="Formulário de cadastro de livro">
            <div class="form-group">
                <label for="titulo" class="required-field">Título</label>
                <input type="text" id="titulo" name="titulo" required aria-required="true">
            </div>
            
            <div class="form-group">
                <label for="subtitulo">Subtítulo</label>
                <input type="text" id="subtitulo" name="subtitulo" aria-describedby="subtitulo-help">
                <span id="subtitulo-help" class="sr-only">Campo opcional para subtítulo do livro</span>
            </div>
            
            <div class="form-group">
                <label for="autor" class="required-field">Autor</label>
                <input type="text" id="autor" name="autor" required aria-required="true">
            </div>
            
            <div class="form-group">
                <label for="editora">Editora</label>
                <input type="text" id="editora" name="editora">
            </div>
            
            <div class="form-group">
                <label for="ano">Ano de Publicação</label>
                <input type="number" id="ano" name="ano" min="1000" max="<?= date('Y') ?>" 
                       aria-describedby="ano-help">
                <span id="ano-help" class="sr-only">Digite o ano com 4 dígitos</span>
            </div>
            
            <div class="form-group">
                <label for="edicao">Edição</label>
                <input type="text" id="edicao" name="edicao">
            </div>
            
            <div class="form-group">
                <label for="isbn">ISBN</label>
                <input type="text" id="isbn" name="isbn" aria-describedby="isbn-help">
                <span id="isbn-help" class="sr-only">Código ISBN do livro (opcional)</span>
            </div>
            
            <div class="form-group">
                <label for="descricao">Descrição</label>
                <textarea id="descricao" name="descricao" aria-describedby="descricao-help"></textarea>
                <span id="descricao-help" class="sr-only">Descrição opcional sobre o livro</span>
            </div>
    
    <form method="post" aria-label="Formulário de cadastro de livro">
        
        <div class="button-container">
            <button type="submit" class="button submit-button" aria-label="Cadastrar livro">Cadastrar Livro</button>
            <a href="trocalivros.php" class="button cancel-button" aria-label="Cancelar e voltar para a lista de livros">Cancelar</a>
        </div>
    </form>
        </form>
    </main>
    
    <footer class="footer">
        <p>Powered by DRP01-pji310-grupo-006</p>
    </footer>
</body>
</html>
<?php 
// Fecha a conexão apenas se existir
if (isset($conn)) {
    $conn->close();
}
?>