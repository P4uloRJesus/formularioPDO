<?php
session_start();
include_once("conexao.php");
$title = "Listar";
include("include/header.php");
?>

<div>
	<!-- <link rel="stylesheet" href="styles/global.css"> -->
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
						<a class="nav-link" href="http://localhost/phpmyadmin/sql.php?server=1&db=celke&table=cadastros&pos=0 " target="_blank">Banco de Dados</a>
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


	<h1>Listar</h1>

	<?php
	//Receber o numero da pagina
	$pagina_atual = filter_input(INPUT_GET, "page", FILTER_SANITIZE_NUMBER_INT);
	$pagina = (!empty($pagina_atual)) ? $pagina_atual : 1;
	//var_dump($pagina);
	if (isset($_SESSION['msg'])) {

		echo $_SESSION['msg'];
		unset($_SESSION['msg']);
	}

	//Setar a quantidade de registros por pagina 
	$limite_resultado = 3;

	//Calcular o inicio da visualização
	$inicio = ($limite_resultado * $pagina) - $limite_resultado;


	$query_usuarios = "SELECT id, nome, email FROM cadastros LIMIT $inicio, $limite_resultado";
	$result_usuarios = $conn->prepare($query_usuarios);
	$result_usuarios->execute();
	?>

	<table style="width:100%" class="table table-hover table-striped">
		<thead>
			<tr>
				<th>ID</th>
				<th>Nome</th>
				<th>E-mail</th>
				<th>Ação</th>
			</tr>
		</thead>

		<tbody>
			<?php
			if (($result_usuarios) and ($result_usuarios->rowCount() != 0)) {
				while ($row_usuario = $result_usuarios->fetch(PDO::FETCH_ASSOC)) {
					//var_dump($row_usuario);
					extract($row_usuario);

			?>


					<tr>
						<td>
							<?= $id  ?>
						</td>
						<td>
							<div class="d-flex">
								<img src="https://avatars.dicebear.com/4.5/api/initials/<?= $nome ?>.svg?margin=5&backgroundColorLevel=800" style='width: 30px;height:30px;' class="mr-2">
								<?= $nome ?>
							</div>
						</td>
						<td><?= $email ?></td>
						<td>
							<?php
							echo "<a class='btn btn-info ml-2' href='visualizar.php?id=$id'> <i class='fas fa-eye mr-2'></i>info</a>";
							echo "<a class='btn btn-warning ml-2' href='editar.php?id=$id'><i class='fas fa-user-edit mr-2'></i>Edit</a>";
							echo "<a class='btn btn-danger ml-2' href='apagar.php?id=$id'><i class='fas fa-trash-alt mr-2'></i>Delete</a>";
							?>
						</td>
					</tr>
				<?php
				}
				?>
		</tbody>
	</table>

<?php

				//Contar a quantidade de registros no BD(Banco de Dados)
				$query_qnt_registros = "SELECT COUNT(id) AS num_result FROM cadastros";
				$result_qnt_registros = $conn->prepare($query_qnt_registros);
				$result_qnt_registros->execute();
				$row_qnt_registros = $result_qnt_registros->fetch(PDO::FETCH_ASSOC);

				//Quantidade de pagina
				$qnt_pagina = ceil($row_qnt_registros['num_result'] / $limite_resultado);

				// Maximo de link
				$maximo_link = 2;


				echo
				"<nav aria-label='...'>
					<ul class='pagination'>
				  	<li class='page-item'>
							<a class='page-link' href='index.php?page=1' > Primeira </a></li>";

				for ($pagina_anterior = $pagina - $maximo_link; $pagina_anterior <= $pagina - 1; $pagina_anterior++) {
					if ($pagina_anterior >= 1) {
						echo "<li class='page-item'><a class='page-link' href='index.php?page=$pagina_anterior'>$pagina_anterior</a></li> ";
					}
				}

				echo "<li class='page-item active'><a class='page-link' href='#'>$pagina</a></li>";

				for ($proxima_pagina = $pagina + 1; $proxima_pagina <= $pagina + $maximo_link; $proxima_pagina++) {
					if ($proxima_pagina <= $qnt_pagina) {

						echo "<a class='page-link' href='index.php?page=$proxima_pagina'>$proxima_pagina</a> ";
					}
				}

				echo
				"<li class='page-item'><a class='page-link' href='index.php?page=$qnt_pagina'> Última </a>
						</li>
					</ul>
				</nav>";
			} else {
				echo "<p style='color: red;'>Erro: Usuário não cadastrado com sucesso!</p><br>";
			}

?>

</body>

</html>