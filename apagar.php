<?php
session_start();
include_once("conexao.php");
$title = "Delete";
include("include/header.php");

$id = filter_input(INPUT_GET, "id", FILTER_SANITIZE_NUMBER_INT);
var_dump($id);

if (empty($id)) {       /* Validação para ver se o ID existe.Caso não exista, volta
                        para a pagina do listar */
  $_SESSION['msg'] = "<p style='color: red;'>Erro: Usuário não encontrado!</p>";
  header("location: index.php");
  exit();
}

$query_usuario = "SELECT id FROM cadastros WHERE id = $id LIMIT 1";
$result_usuario = $conn->prepare($query_usuario);
$result_usuario->execute();

if (($result_usuario) and ($result_usuario->rowCount() != 0)) {
  $query_del_usuario = "DELETE FROM cadastros WHERE id = $id";
  $apagar_usuario = $conn->prepare($query_del_usuario);

  if ($apagar_usuario->execute()) {
    $_SESSION['msg'] = "<div class='alert alert-success'>Usuário apagado com sucesso!</div>";
    header("location: index.php");
  } else {
    $_SESSION['msg'] = "<div='class alert alert-danger'>Usuário não apagado com sucesso!</div>";
    header("location: index.php");
  }
} else {
  $_SESSION['msg'] = "<p style='color: red;'>Erro: Usuário não encontrado!</p>";
  header("location: index.php");
}
