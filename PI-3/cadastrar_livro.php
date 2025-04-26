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

    //inicio da inserção dos dados
    $stmt = $pdo->prepare("INSERT INTO cadastroLivros (titulo) 
                           VALUES (:titulo) 
    
    
    $stmt->execute([
        ':titulo' => $titulo,
    ]);


// aqui finaliza o try
}

?>