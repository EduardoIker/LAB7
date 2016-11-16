<?php
session_start();
if(!isset($_SESSION["correo"])){
     header("Location: login.php");
     exit();
}
if(isset($_POST[pregunta])){
	#Conexion con la BD
	$link = mysqli_connect("mysql.hostinger.es", "u923585965_root", "Informatica", "u923585965_quiz");
	if(!$link){
		echo "Fallo al conectar a MySQL:" . $link->connect_error;
		mysqli_close($link);
		exit(1);
	}

	
	# VALIDACIONES DE LOS DATOS 
	#Pregunta
    if(strcmp($_POST[pregunta],"")==0){
		echo "Introduce una pregunta";
		exit(1);
	}
	
	#Respuesta
    if(strcmp($_POST[respuesta],"")==0){
		echo "Introduce una respuesta";
		exit(1);
	}
	
	#Complejidad
    if(((strcmp($_POST[complejidad],"1")!=0) && (strcmp($_POST[complejidad],"2")!=0) && (strcmp($_POST[complejidad],"3")!=0) && (strcmp($_POST[complejidad],"4")!=0) && (strcmp($_POST[complejidad],"5")!=0)) || (strcmp($_POST[complejidad],"")==0)){
		echo "Introduce un valor de complejidad v�lido (entre 1 y 5)";
		exit(1);
	}

    #Tema
    if(strcmp($_POST[tema],"")==0){
		echo "Introduce un tema";
		exit(1);
	}
	
	#Insertamos la pregunta si todo ha ido bien...
	
		#En la BD
	$sql="INSERT INTO pregunta VALUES (NULL,'$_SESSION[correo]','$_POST[pregunta]','$_POST[respuesta]','$_POST[complejidad]')";
	if (!mysqli_query($link ,$sql)){
		die('Error al insertar tupla: ' . mysqli_error($link));
	}
        $sql="select MAX(ID_CONEXION) as ID_CON from CONEXIONES where CORREO='$_SESSION[correo]'";
	if (!($result=mysqli_query($link ,$sql))){
		die('Error en la consulta: ' . mysqli_error($link));
	}
        $resultado = mysqli_fetch_array($result);
        $id_conex=$resultado['ID_CON'];
        date_default_timezone_set('Europe/Madrid');
        $date=date("Y-m-d H:i:s");
        $ip_maquina =$_SERVER['REMOTE_ADDR'];
        $accion = "Insertar pregunta";
		$sql2="INSERT INTO ACCIONES VALUES (NULL,'$id_conex','$_SESSION[correo]','$accion', '$date', '$ip_maquina')";
		if (!mysqli_query($link ,$sql2)){
			die('Error al insertar tupla: ' . mysqli_error($link));
		}
	
	
	#Mensaje para indicar que todo ha ido bien
	echo "<font color='green'>Pregunta guardada correctamente</font>";
}
?>