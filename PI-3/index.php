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
  <meta name="theme-color" content="#3498db">
  <meta name="apple-mobile-web-app-capable" content="yes">
  <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
  <style>
    :root {
      --primary-color: #3498db;
      --primary-hover: #2980b9;
      --background-color: #f5f5f5;
      --text-color: #333;
      --border-color: #ccc;
      --table-header-bg: #f2f2f2;
      --table-row-even: #f9f9f9;
      --table-row-hover: #f1f1f1;
    }
    
    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }
    
    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background-color: var(--background-color);
      min-height: 100vh;
      display: flex;
      flex-direction: column;
      color: var(--text-color);
      line-height: 1.6;
    }

    .login-container {
      display: flex;
      justify-content: flex-end;
      padding: 15px;
      background-color: #fff;
      box-shadow: 0 2px 5px rgba(0,0,0,0.1);
      position: sticky;
      top: 0;
      z-index: 100;
    }

    .input-group {
      display: flex;
      flex-wrap: wrap;
      gap: 10px;
      align-items: center;
      justify-content: flex-end;
    }

    input[type="text"],
    input[type="password"] {
      padding: 10px 12px;
      border-radius: 5px;
      border: 1px solid var(--border-color);
      width: 100%;
      max-width: 200px;
      font-size: 16px;
    }

    button {
      background-color: var(--primary-color);
      color: white;
      border: none;
      padding: 10px 16px;
      border-radius: 5px;
      cursor: pointer;
      min-width: 100px;
      font-size: 16px;
      transition: background-color 0.3s;
    }

    button:hover {
      background-color: var(--primary-hover);
    }

    .register-link {
      margin-left: 15px;
      font-size: 14px;
      white-space: nowrap;
    }

    .register-link a {
      color: var(--primary-color);
      text-decoration: none;
      font-weight: 500;
    }

    .register-link a:hover {
      text-decoration: underline;
    }

    .conteudo-central {
      flex: 1;
      display: flex;
      flex-direction: column;
      justify-content: flex-start;
      align-items: center;
      text-align: center;
      padding: 20px;
      width: 100%;
    }

    .table-container {
      width: 100%;
      overflow-x: auto;
      margin: 20px 0;
      -webkit-overflow-scrolling: touch;
    }

    table {
      width: 100%;
      max-width: 1000px;
      border-collapse: collapse;
      margin: 20px auto;
      font-family: Arial, sans-serif;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
      background-color: white;
    }
    
    th, td {
      border: 1px solid var(--border-color);
      padding: 12px 15px;
      text-align: center;
    }
    
    th {
      background-color: var(--table-header-bg);
      font-weight: bold;
      position: sticky;
      top: 0;
    }
    
    tr:nth-child(even) {
      background-color: var(--table-row-even);
    }
    
    tr:hover {
      background-color: var(--table-row-hover);
    }

    footer {
      margin-top: auto;
      background-color: #fff;
      text-align: center;
      padding: 15px;
      font-size: 14px;
      color: #888;
      border-top: 1px solid #ddd;
    }

    /* teste responsividade a partir daqui */
    @media (max-width: 768px) {
      .login-container {
        justify-content: center;
        padding: 10px;
      }
      
      .input-group {
        justify-content: center;
        width: 100%;
      }
      
      input[type="text"],
      input[type="password"] {
        max-width: 100%;
      }
      
      table {
        font-size: 15px;
      }
      
      th, td {
        padding: 10px 12px;
      }
    }
    

    @media (max-width: 600px) {
      .input-group {
        flex-direction: column;
        align-items: stretch;
        gap: 8px;
      }
      
      .register-link {
        margin-left: 0;
        margin-top: 10px;
        text-align: center;
        white-space: normal;
      }
      
      button {
        width: 100%;
        padding: 12px;
      }
      
      table, thead, tbody, th, td, tr {
        display: block;
      }
      
      thead tr {
        position: absolute;
        top: -9999px;
        left: -9999px;
      }
      
      tr {
        margin-bottom: 15px;
        border: 1px solid var(--border-color);
        border-radius: 5px;
      }
      
      td {
        border: none;
        border-bottom: 1px solid #eee;
        position: relative;
        padding-left: 50%;
        text-align: left;
      }
      
      td:before {
        position: absolute;
        left: 10px;
        width: 45%;
        padding-right: 10px;
        content: attr(data-label);
        font-weight: bold;
        text-align: right;
      }
    }

    
    @media (max-width: 400px) {
      td {
        padding-left: 40%;
        font-size: 14px;
      }
      
      td:before {
        width: 35%;
        font-size: 14px;
      }
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
    <div class="table-container">
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
                  echo "<td data-label='Título'>" . htmlspecialchars($livro['titulo']) . "</td>";
                  echo "<td data-label='Autor'>" . htmlspecialchars($livro['autor']) . "</td>";
                  echo "<td data-label='Data'>" . date('d/m/Y', strtotime($livro['data_cadastro'])) . "</td>";
                  echo "</tr>";
              }
          } else {
              echo "<tr><td colspan='3' data-label='Info'>Nenhum livro cadastrado</td></tr>";
          }
          $conn->close();
          ?>
        </tbody>
      </table>
    </div>
  </div>

  <footer>
    Powered by DRP01-pji310-grupo-006
  </footer>

</body>
</html>