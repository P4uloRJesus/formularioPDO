<?php
session_start();
include_once("conexao.php");
$title = "Formulario Cadastro";
include("include/header.php");
?>

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
            <a class="nav-link" href="http://localhost/phpmyadmin/sql.php?server=1&db=celke&table=cadastros&pos=0" target="_blank">Banco de Dados</a>
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


  <h1>Formulario de Cadastro PDO</h1>
  <?php

  //Receber os dados do formulário
  $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);
  $dadosVal = [];

  //Verificar se o usário clicou no botão
  if (!empty($dados['CadUsuario'])) {
    unset($dados['CadUsuario']);
    //var_dump($dados);
    //die;

    $empty_input = false;

    $dados = array_map(function ($v) {
      $result = trim($v);    //o trim serve para tirar os espaços a+
      //var_dump($result);

      return $result;
    }, $dados);

    //$dados = ['Paulo eduardo', '454'];

    //var_dump($dados);

    if (in_array("", $dados)) {
      $empty_input = true;
      echo "<p style='color: red;'>Erro: Necessário preencher todos os campos!</p><br>";
    } elseif (!filter_var($dados['email'], FILTER_VALIDATE_EMAIL)) {
      $empty_input = true;
      echo "<p style='color: red;'>Erro: Necessário preencher e-mail válido!</p><br>";
    }

    if (!$empty_input) {


      try {

        //palavra chave para acessar a tabela.
        $query_usuario = "INSERT INTO cadastros (nome, email) VALUES (:nome, :email)";
        $cad_usuario = $conn->prepare($query_usuario);

        $cad_usuario->bindParam(':nome', $dados['nome'], PDO::PARAM_STR);
        $cad_usuario->bindParam(':email', $dados['email'], PDO::PARAM_STR);

        $cad_usuario->execute();
        //$cad_usuario->execute([$dados['nome'], $dados['email']]);

        if ($cad_usuario->rowCount()) {
          echo "<p style='color: green;'>Usuário cadastrado com sucesso!</p><br>";
          unset($dados);
        } else {
          echo "<p style='color: red;'>Erro: Usuário não cadastrado com sucesso!</p><br>";
        }
      } catch (PDOException $e) {

        echo "Erro ao cadastrar no formulário: Não foi possível inserir o registro, tente novamente";
        //var_dump($e->getMessage());
        /* die(); */
      }
      /* var_dump($cad_usuario->rowCount());
      die(); */
    }
  }
  ?>
  <form name="cad-usuario" method="POST" action="">
    <label>Nome: </label>
    <input type="text" name="nome" id="nome" placeholder="Nome Completo" value="<?php
                                                                                if (isset($dados['nome'])) {
                                                                                  echo $dados['nome'];
                                                                                }
                                                                                ?>"><br><br>

    <label>E-mail: </label>
    <input type="email" name="email" id="email" placeholder="seu melhor e-mail" value="<?php
                                                                                        if (isset($dados['email'])) {
                                                                                          echo $dados['email'];
                                                                                        }
                                                                                        ?>"><br><br>
    <input type="submit" value="cadastrar" name="CadUsuario">

  </form>
  </body>

  </html>