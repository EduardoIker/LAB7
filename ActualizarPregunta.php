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
		echo "Introduce un valor de complejidad válido (entre 1 y 5)";
		exit(1);
	}

    #Codigo
    if(!filter_var($_POST[codigo], FILTER_VALIDATE_REGEXP,array("options" => array("regexp"=>"/^\d+$/")))){
		echo "Introduce un numero de codigo valido";
		exit(1);
	}
	
	#Actualizamos la pregunta si todo ha ido bien...
	$sql="UPDATE pregunta SET TXT_PREG='$_POST[pregunta]', TXT_RESP='$_POST[respuesta]', COMPLEJIDAD='$_POST[complejidad]' WHERE NUM_PREG='$_POST[codigo]'";
	if (!mysqli_query($link ,$sql)){
		die('Error al actualizar tupla: ' . mysqli_error($link));
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
        $accion = "Actulizar pregunta";
		$sql2="INSERT INTO ACCIONES VALUES (NULL,'$id_conex','$_SESSION[correo]','$accion', '$date', '$ip_maquina')";
		if (!mysqli_query($link ,$sql2)){
			die('Error al insertar tupla: ' . mysqli_error($link));
		}
	
	#Mensaje para indicar que todo ha ido bien
	echo "<font color='green'>Pregunta actualizada correctamente</font>";
}
?>