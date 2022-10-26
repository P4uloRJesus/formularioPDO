<?php
session_start();
include_once("conexao.php");
$title = "Editar";
include("include/header.php");

$id = filter_input(INPUT_GET, "id", FILTER_SANITIZE_NUMBER_INT);

//var_dump($id);

if (empty($id)) {        /* Validação para ver se o ID existe.Caso não exista, volta
                        para a pagina do listar */
  $_SESSION['msg'] = "<div alert alert-danger>Erro: Usuário não encontrado!</div>";
  header("location: index.php");
  exit();
}

$query_usuario =  "SELECT id, nome, email FROM cadastros WHERE id = $id LIMIT 1";
$result_usuario = $conn->prepare($query_usuario);
$result_usuario->execute();

if (($result_usuario) and ($result_usuario->rowCount() != 0)) {
  $row_usuario = $result_usuario->fetch(PDO::FETCH_ASSOC);
  //var_dump($row_usuario);
  extract($row_usuario);
} else {
  $_SESSION['msg'] = "<div alert alert-danger>Erro: Usuário não encontrado!</div>";
  header("Location: index.php");
  exit();
}
?>
<!-- <!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">

</head>
<body>
 -->
<div>
  <link rel="stylesheet" href="styles/global.css">
  <nav class="navbar navbar-expand-lg bg-light">
    <div class="container-fluid">
      <a class="navbar-brand" href="#">Navbar</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNavDropdown">
        <ul class="navbar-nav">
          <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="index.php">Listar</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="http://localhost/phpmyadmin/sql.php?db=celke&table=usuarios&pos=0 " target="_blank">Banco de Dados</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="formcadastro.php">formulario</a>
          </li>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-expanded="false">
              #
            </a>
            <div class="dropdown-menu">
              <a class="dropdown-item" href="#"></a>
              <a class="dropdown-item" href="#"></a>
              <a class="dropdown-item" href="#"></a>
            </div>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <h1>Editar</h1>

  <?php

  //Receber os dados do formulario
  $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);

  //Verificar se o usuário clicou no botão
  if (!empty($dados['EditUsuario'])) {
    $empty_input  = false;
    $dados = array_map('trim', $dados);
    if (in_array("", $dados)) {
      $empty_input = true;
      echo "<div alert alert-danger>Erro: Necessário preencher todos campos!</div>";
    } elseif (!filter_var($dados['email'], FILTER_VALIDATE_EMAIL)) {
      $empty_input = true;
      echo "<div alert alert-danger>Erro: Necessário preencher com e-mail valido!</div>";
    }

    if (!$empty_input) {
      $query_up_usuario = "UPDATE cadastros SET nome=:nome, email=:email WHERE id=:id";
      $edit_usuario = $conn->prepare($query_up_usuario);
      $edit_usuario->bindParam(':nome', $dados['nome'], PDO::PARAM_STR);
      $edit_usuario->bindParam(':email', $dados['email'], PDO::PARAM_STR);
      $edit_usuario->bindParam(':id', $id, PDO::PARAM_INT);
      if ($edit_usuario->execute()) {
        $_SESSION['msg']  = "<div class='alert alert-success'>Usuário editado com sucesso!</div>";
        header("Location: index.php");
      } else {
        echo "<div alert alert-danger>Usuário não editado com sucesso!</div>";
      }
    }
  }
  ?>

  <form id="edit-usuario" method="POST" action="">
    <label>Nome</label>
    <input type="text" name="nome" id="nome" placeholder="Nome Completo" value="<?php if (isset($dados['nome'])) {
                                                                                  echo $dados['nome'];
                                                                                } elseif (isset($row_usuario['nome'])) {
                                                                                  echo $row_usuario['nome'];
                                                                                } ?>"><br><br>

    <label>Nome</label>
    <input type="email" name="email" id="email" placeholder="Melhor E-mail" value="<?php if (isset($dados['email'])) {
                                                                                      echo $dados['email'];
                                                                                    } elseif (isset($row_usuario['email'])) {
                                                                                      echo $row_usuario['email'];
                                                                                    } ?>"><br><br>

    <input type="submit" value="Salvar" name="EditUsuario">
  </form>
  </body>

  </html>