    <?php
	        session_start();
                if(!isset($_SESSION["correo"])){
                     header("Location: login.php");
                     exit();
                }
		#ConexiÃ³n con la BD
		$link = mysqli_connect("mysql.hostinger.es", "u923585965_root", "Informatica", "u923585965_quiz");
		if(!$link){
		echo 'Fallo al concectar a MySQL:' . $link->connect_error; 
			mysqli_close($link);
		}
        $sql="select MAX(ID_CONEXION) as ID_CON from CONEXIONES where CORREO='$_SESSION[correo]'";
		if (!($result=mysqli_query($link ,$sql))){
			die('Error en la consulta: ' . mysqli_error($link));
		}
        $resultado = mysqli_fetch_array($result);
        date_default_timezone_set('Europe/Madrid');
        $date=date("Y-m-d H:i:s");
        $ip_maquina =$_SERVER['REMOTE_ADDR'];
        $accion = "Ver preguntas";
		$id_conex=$resultado['ID_CON'];
	    $sql2="INSERT INTO ACCIONES VALUES (NULL,'$id_conex','$_SESSION[correo]','$accion', '$date', '$ip_maquina')";
		if (!mysqli_query($link ,$sql2)){
			die('Error al insertar tupla: ' . mysqli_error($link));
		}

	
		#Consulta de SQL: Obtener todas las preguntas de la BD.
		$preguntas = mysqli_query($link, "select CORREO, TXT_PREG,COMPLEJIDAD from pregunta" );
	
		#Creamos la tabla <html> donde queremos que se visualicen las preguntas.
		echo '<table border=1> <tr> <th> Autor </th> <th> Pregunta </th>  <th> Compeljidad </th> </tr>';
	
	
		#Insertamos los datos de cada pregunta (obtenidos tras la consulta) en la tabla creada anteriormente.
		while ($row = mysqli_fetch_array( $preguntas )) {
				echo '<tr><td>' . $row['CORREO'] . '</td> <td>' . $row['TXT_PREG'] . '</td> <td>' . $row['COMPLEJIDAD'] . '</td></tr>';
		}
		echo '</table>';
		$preguntas->close();
 
		#Cierre de la conexiÃ³n con la BD.
		mysqli_close($link);

    ?>