<?php
session_start();
include_once("conexao.php");

?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8">

  <title>Cadastro</title>
</head>

<body>
  <h1>Formulario de Cadastro PDO</h1>
  <a href="../formulario-cadastro">Home</a><br><br>
  <?php
  $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);
  $dadosVal = [];

  if (!empty($dados['CadUsuario'])) {
    unset($dados['CadUsuario']);
    var_dump($dados);
    //die;

    $empty_input = false;

    $dados = array_map(function($v)
    {
      $result = trim($v);    //o trim serve para tirar os espaços a+
      var_dump($result);
      
      return $result;

    }, $dados);

    //$dados = ['Paulo eduardo', '454'];

    //var_dump($dados);
    
    if (in_array("", $dados)) {
      $empty_input = true;
      echo "<p style='color: red;'>Erro: Necessário preencher todos os campos!</p><br>";    
    }elseif (!filter_var($dados['email'], FILTER_VALIDATE_EMAIL)){
      $empty_input = true;
      echo "<p style='color: red;'>Erro: Necessário preencher e-mail válido!</p><br>";
    }
    
    if (!$empty_input) {
    

      try{

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

      }catch(PDOException $e){

        echo "Erro ao cadastrar no formulário: Não foi possível inserir o registro, tente novamente" ;
        var_dump($e->getMessage());
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
    if (isset($dados['nome'])){
      echo $dados['nome'];
    }
    ?>"><br><br>

    <label>E-mail: </label>
    <input type="email" name="email" id="email" placeholder="seu melhor e-mail"value="<?php 
    if (isset($dados['email'])){
      echo $dados['email'];
    }
    ?>"><br><br>
    <input type="submit" value="cadastrar" name="CadUsuario">

  </form>
</body>

</html>