<?php
session_start();
if(!isset($_SESSION["correo"])){
     header("Location: login.php");
     exit();
}
?>
<!DOCTYPE html>
<html>
  <head>
    <meta name="tipo_contenido" content="text/html;" http-equiv="content-type" charset="utf-8">
	<title>Preguntas</title>
    <link rel='stylesheet' type='text/css' href='estilos/style.css' />
	<link rel='stylesheet' 
		   type='text/css' 
		   media='only screen and (min-width: 530px) and (min-device-width: 481px)'
		   href='estilos/wide.css' />
	<link rel='stylesheet' 
		   type='text/css' 
		   media='only screen and (max-width: 480px)'
		   href='estilos/smartphone.css' />
  </head>
  <body>
  <div id='page-wrap'>
	<header class='main' id='h1'>
			<?php
				$usuario=$_SESSION[correo];
				echo "<span class='right'>Hola, <font color='red'>".$usuario."</font></span>";
			?>
      		<span class="right"><a href="logout.php">Logout</a></span>
		<h2>Quiz: el juego de las preguntas</h2>
    </header>
	<nav class='main' id='n1' role='navigation'>
		<span><a href='itzAlumno.php'>Inicio</a></spam>
		<span><a href='GestionPreguntas.php'> Insertar Pregunta</a></spam>
	
	</nav>
    <section class="main" id="s1">
    
	<div>
	Aqui podras insertar nuevas preguntas
	</div>
    </section>
	<footer class='main' id='f1'>
		<p><a href="http://es.wikipedia.org/wiki/Quiz" target="_blank">Que es un Quiz?</a></p>
		<a href='https://github.com'>Link GITHUB</a>
	</footer>
</div>
</body>
</html>	