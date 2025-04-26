<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include('conexao.php');

$sql = "SELECT titulo, autor, data_cadastro FROM cadastroLivros ORDER BY id DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login - Projeto Integrador III</title>
  <style>
    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background-color: #f5f5f5;
      margin: 0;
      padding: 0;
      min-height: 100vh;
      display: flex;
      flex-direction: column;
    }

    .login-container {
      display: flex;
      align-items: center;
      justify-content: flex-end;
      padding: 20px;
      background-color: #fff;
      box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }

    .input-group {
      display: flex;
      gap: 10px;
      align-items: center;
    }

    input[type="text"],
    input[type="password"] {
      padding: 8px;
      border-radius: 5px;
      border: 1px solid #ccc;
      box-sizing: border-box;
      width: 200px;
    }

    button {
      background-color: #3498db;
      color: white;
      border: none;
      padding: 8px 16px;
      border-radius: 5px;
      cursor: pointer;
      width: 100px;
      font-size: 14px;
    }

    button:hover {
      background-color: #2980b9;
    }

    .register-link {
      margin-left: 15px;
      font-size: 14px;
    }

    .register-link a {
      color: #3498db;
      text-decoration: none;
    }

    .register-link a:hover {
      text-decoration: underline;
    }

    .conteudo-central {
      flex: 1;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      text-align: center;
      padding: 20px;
    }

    footer {
      margin-top: auto;
      background-color: #fff;
      text-align: center;
      padding: 10px;
      font-size: 12px;
      color: #888;
      border-top: 1px solid #ddd;
    }
  </style>
</head>
<body>

  <div class="login-container">
    <form action="login.php" method="post" class="input-group">
      <input type="text" name="ra" placeholder="RA (matrícula)" required>
      <input type="password" name="senha" placeholder="Senha" required>
      <button type="submit">Entrar</button>
      <div class="register-link">
        <a href="cadastro_aluno.html">Não é cadastrado? Clique aqui</a>
      </div>
    </form>
  </div>

  <div class="conteudo-central">

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <title>Login - Projeto Integrador III</title>
  <style>
    table {
      margin: 50px auto;
      border-collapse: collapse;
      width: 50%;
      font-family: Arial, sans-serif;
    }
    th, td {
      border: 1px solid #ccc;
      padding: 10px 15px;
      text-align: center;
    }
    th {
      background-color: #f2f2f2;
    }
    footer {
      margin-top: 50px;
      text-align: center;
      font-size: 12px;
      color: #888;
    }
  </style>
</head>
<body>
  <table>
    <thead>
      <tr>
        <th>Título</th>
        <th>Autor</th>
        <th>Data de Postagem</th>
      </tr>
    </thead>
    <tbody>
      <?php 
      if ($result->num_rows > 0) {
          while ($livro = $result->fetch_assoc()) {
              echo "<tr>";
              echo "<td>" . htmlspecialchars($livro['titulo']) . "</td>";
              echo "<td>" . htmlspecialchars($livro['autor']) . "</td>";
              echo "<td>" . date('d/m/y', strtotime($livro['data_cadastro'])) . "</td>";
              echo "</tr>";
          }
      } else {
          echo "<tr><td colspan='3'>Nenhum livro cadastrado</td></tr>";
      }
      ?>
        </td>
      </tr>
    </tbody>
  </table>
  <footer>
    Powered by DRP01-pji310-grupo-006
  </footer>

</body>
</html>
