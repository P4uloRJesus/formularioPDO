<?php
session_start();
include_once("conexao.php");
$title = "Visualizar";
include("include/header.php");

$id = filter_input(INPUT_GET, "id", FILTER_SANITIZE_NUMBER_INT);

//var_dump($id);

if (empty($id)) {       /* Validação para ver se o ID existe.Caso não exista, volta
                        para a pagina do listar */
  $_SESSION['msg'] = "<p style='color: red;'>Erro: Usuário não encontrado!</p>";
  header("location: index.php");
  exit();
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8">
</head>

<body>
  <h1>Visualizar</h1>

  <?php

  $query_usuario =  "SELECT id, nome, email FROM cadastros WHERE id = $id LIMIT 1";
  $result_usuario = $conn->prepare($query_usuario);
  $result_usuario->execute();

  if (($result_usuario) and ($result_usuario->rowCount() != 0)) {
    $row_usuario = $result_usuario->fetch(PDO::FETCH_ASSOC);
    //var_dump($row_usuario);
    extract($row_usuario);


    //echo "ID: " . $row_usuario['id'] . "<br>";
    echo "ID: $id <br>";
    echo "Nome: $nome <br>";
    echo "E-mail: $email <br>";
  } else {
    $_SESSION['msg'] = "<p style='color: red;'>Erro: Usuário não encontrado!</p>";
    header("location: index.php");
    exit();
  }
  ?>

</body>

</html>