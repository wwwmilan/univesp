// conectar com o db
// importar dados do formulario
// inserir na DB 
// conferir se seu certo 
// conferir se deu errado 

<?php

$host = "sql109.infinityfree.com";
$db = "if0_38781370_univesp";
$user = "if0_38781370";
$pass = "Univesp2025";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    //inicio da importação dos dados
    $titulo = $_POST['titulo'];
    $subtitulo = $_POST['subtitulo'];
    $autor = $_POST['autor'];
    $editora = $_POST['editora'];
    $ano = $_POST['ano'];
    $edicao = $_POST['edicao'];
    $isbn = $_POST['isbn'];
    $descricao = $_POST['descricao'];


    $stmt = $pdo->prepare("INSERT INTO cadastroLivros (titulo, subtitulo, autor, editora, ano, edicao, isbn, descricao) 
                           VALUES (:titulo, :subtitulo, :autor, :editora, :ano, :edicao, :isbn, :descricao)");

    $stmt->execute([
        ':titulo' => $titulo,
        ':subtitulo' => $subtitulo,
        ':autor' => $autor,
        ':editora' => $editora,
        ':ano' => $ano,
        ':edicao' => $edicao,
        ':isbn' => $isbn,
        ':descricao' => $descricao
    ]);
    header("Location: index.html?status=success"); //se tiver sucesso voltar para index?????
    exit();
    
} catch (Exception $e) { //se der erro vamos direcionar para onde???
    header("Location: cadastro_livros.html?status=error&message=" . urlencode($e->getMessage()));
    exit();




// aqui finaliza o try
}

?>