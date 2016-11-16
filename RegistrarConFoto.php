<?php
    # VALIDACIONES DE LOS DATOS
	
	#Correo (necesitamos hacer uso del servicio web que nos indica que esta matriculado)
		#Incluimos la clase nusoap.php
	require_once('lib/nusoap.php');
	require_once('lib/class.wsdlcache.php');
		#Creamos el objeto de tipo soapclient donde se encuentra el servicio SOAP que vamos a utilizar.
	$soapclient = new nusoap_client('http://cursodssw.hol.es/comprobarmatricula.php?wsdl',true);
		#Llamamos la funci�n que hab�amos implementado en el Web Service. Le enviamos como parametro el correo que hemos recibido mediante post
	$result = $soapclient->call('comprobar', array('x'=>$_POST[correo]));

    if((!filter_var($_POST[correo], FILTER_VALIDATE_REGEXP,array("options" => array("regexp"=>"/^[a-z]+([0-9]{3})@ikasle\.ehu\.(es|eus)$/")))) || strcmp($result,"NO")==0){
		echo "Formato de correo incorrecto. No corresponde con un correo matriculado de la UPV/EHU. Registro incorrecto, int�ntalo de nuevo.";
                echo "<p> <a href='registro.html'> Volver al formulario de registro </a>";
		exit(1);
	}
	
	#Nombre y Apellidos
	if(!filter_var($_POST[nombreyapellidos], FILTER_VALIDATE_REGEXP,array("options" => array("regexp"=>"/^[A-z]+([ ][A-z]+)+$/")))){
		echo "Nombre y Apellidos no v�lidos. Registro incorrecto, int�ntalo de nuevo.";
                echo "<p> <a href='registro.html'> Volver al formulario de registro </a>";
		exit(1);
	}
	
	#Password (necesitamos hacer uso del servicio web que nos indica si la contrase�a es segura)
		#Incluimos la clase nusoap.php
	require_once('lib/nusoap.php');
	require_once('lib/class.wsdlcache.php');
		#Creamos el objeto de tipo soapclient donde se encuentra el servicio SOAP que vamos a utilizar.
	$soapclient1 = new nusoap_client('http://eisw.hol.es/LAB-6PO/HTML4/ComprobarContrasena.php?wsdl',true);
		#Llamamos la funci�n que hab�amos implementado en el Web Service. Le enviamos como parametro el correo que hemos recibido mediante post
	$result1 = $soapclient1->call('ComprobarContrasena', array('x'=>$contra, 'y'=> $_POST[ticket]));
	if((!filter_var($_POST[password], FILTER_VALIDATE_REGEXP,array("options" => array("regexp"=>"/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,8}$/")))) || (strcmp($result1,"INVALIDA")==0) || (strcmp($result1,"USUARIO NO AUTORIZADO")==0)){
		echo " Contrase�a no v�lida. Debe contener: <br>";
                              echo "- Una letra mayuscula <br>" ;
                              echo "- Una letra minuscula <br>" ;
                              echo "- Un digito <br>" ;
                              echo "- Longitud entre 6 y 8 caracteres (ambos inclusive)<br>";
                echo " Ademas no debe ser una contrasena 'habitual'y el ticket introducido debe ser valido<br>";
                       echo "Registro incorrecto, int�ntalo de nuevo";
                echo "<p> <a href='registro.html'> Volver al formulario de registro </a>";
		exit(1);
	}
	
	#Numero de tel�fono
	if(!filter_var($_POST[numtelefono], FILTER_VALIDATE_REGEXP,array("options" => array("regexp"=>"/^[9|8|7|6]\d{8,8}$/")))){
		echo "Formato de telefono no v�lido. Registro incorrecto, int�ntalo de nuevo.";
                echo "<p> <a href='registro.html'> Volver al formulario de registro </a>";
		exit(1);
	}
	
		

	#Si la especialidad indicada en el formulario es 'otra', tomamos el valor que se haya introducido en el campo 'otraEspec'
	$laespecialidad= $_POST[Especialidad];
	if(strnatcasecmp($laespecialidad,"Otra...")==0){
		$laespecialidad= $_POST[otraEspec];
	}
	
	
	#Conexi�n con la BD
	$link = mysqli_connect("mysql.hostinger.es", "u923585965_root", "Informatica", "u923585965_quiz");
	if(!$link){
		echo "Fallo al conectar a MySQL:" . $link->connect_error;
		mysqli_close($link);
	}
	
        #Comprobamos que se ha subido un archivo
	if (is_uploaded_file($_FILES["img"]["tmp_name"])){ #Y que dicho archivo es una imagen en cualquiera de los formatos que se listan en la condic�n (gif, jpeg, ...)
		if ($_FILES["img"]["type"]=="image/jpeg" || $_FILES["img"]["type"]=="image/pjpeg" || $_FILES["img"]["type"]=="image/gif" || $_FILES["img"]["type"]=="image/bmp" || $_FILES["img"]["type"]=="image/png"){
			
                        #Obtenemos el 'valor' de la imagen
			$valorImagen=mysqli_real_escape_string($link, file_get_contents($_FILES["img"]["tmp_name"]));

	                #Insertamos la imagen en BD con el resto de datos del usuario. Almacenaremos tambi�n el tipo de imagen
			$sql="INSERT INTO usuario VALUES ('$_POST[nombreyapellidos]','$_POST[correo]','$_POST[password]','$_POST[numtelefono]','$laespecialidad','$_POST[intereses]','$valorImagen','$_FILES[img][type]')";
			if (!mysqli_query($link ,$sql)){
				die('Error al insertar tupla: ' . mysqli_error($link));
			}
			echo "El registro se ha guardado correctamente";

		}else{ # Si el archivo no es una imagen v�lida, no la insertamos. Como valor del atributo 'imagen' y 'tipo_imagen' guardaremos NULL.
			$sql="INSERT INTO usuario VALUES ('$_POST[nombreyapellidos]','$_POST[correo]','$_POST[password]','$_POST[numtelefono]','$laespecialidad','$_POST[intereses]',NULL,NULL)";
			if (!mysqli_query($link ,$sql)){
				die('Error al insertar tupla: ' . mysqli_error($link));
			}
			echo "El registro se ha guardado correctamente, pero la imagen no es valida. Valor guardado para la imagen: NULL";
		}

	}else{ #Si no se ha subido ning�n archivo (valor del atributo 'imagen' y 'tipo_imagen' --> NULL).
		$sql="INSERT INTO usuario VALUES ('$_POST[nombreyapellidos]','$_POST[correo]','$_POST[password]','$_POST[numtelefono]','$laespecialidad','$_POST[intereses]',NULL,NULL)";
		if (!mysqli_query($link ,$sql)){
			die('Error al insertar tupla: ' . mysqli_error($link));
		}
		echo "El registro se ha completado con �xito";
	}


	#Enlace para ver los usuarios
	echo "<p> <a href='VerUsuariosConFoto.php'> Ver registros </a>";
	mysqli_close($link);
?>		