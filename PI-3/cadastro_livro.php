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
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar Livro</title>
    <style>
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
        }
        
        h2 {
            color: #333;
            margin-bottom: 20px;
            text-align: center;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #444;
        }
        
        input[type="text"],
        input[type="number"],
        textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
        }
        
        textarea {
            min-height: 100px;
            resize: vertical;
        }
        
        button {
            background-color: #3498db;
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            width: 100%;
            transition: background-color 0.3s;
        }
        
        button:hover {
            background-color: #2980b9;
        }
        
        .error-message {
            color: #e74c3c;
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
        
        .required-field::after {
            content: " *";
            color: #e74c3c;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Cadastrar Novo Livro</h2>
        
        <?php if (isset($_GET['error'])): ?>
            <div class="error-message">
                <?= htmlspecialchars(urldecode($_GET['error'])) ?>
            </div>
        <?php endif; ?>
        
        <form method="post">
            <div class="form-group">
                <label for="titulo" class="required-field">Título</label>
                <input type="text" id="titulo" name="titulo" required>
            </div>
            
            <div class="form-group">
                <label for="subtitulo">Subtítulo</label>
                <input type="text" id="subtitulo" name="subtitulo">
            </div>
            
            <div class="form-group">
                <label for="autor" class="required-field">Autor</label>
                <input type="text" id="autor" name="autor" required>
            </div>
            
            <div class="form-group">
                <label for="editora">Editora</label>
                <input type="text" id="editora" name="editora">
            </div>
            
            <div class="form-group">
                <label for="ano">Ano de Publicação</label>
                <input type="number" id="ano" name="ano" min="1000" max="<?= date('Y') ?>">
            </div>
            
            <div class="form-group">
                <label for="edicao">Edição</label>
                <input type="text" id="edicao" name="edicao">
            </div>
            
            <div class="form-group">
                <label for="isbn">ISBN</label>
                <input type="text" id="isbn" name="isbn">
            </div>
            
            <div class="form-group">
                <label for="descricao">Descrição</label>
                <textarea id="descricao" name="descricao"></textarea>
            </div>
            
            <button type="submit">Cadastrar Livro</button>
        </form>
    </div>
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