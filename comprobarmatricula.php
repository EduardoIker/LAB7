<?php
	//Incluimos la clase nusoap.php
	require_once('lib/nusoap.php');
	require_once('lib/class.wsdlcache.php');
	//Creamos el objeto de tipo soapclient donde se encuentra el servicio SOAP que vamos a utilizar.
        $soapclient = new nusoap_client( 'http://cursodssw.hol.es/comprobarmatricula.php?wsdl',true);
	//$soapclient = new nusoap_client('http://sw14.hol.es/ServiciosWeb/comprobarmatricula.php',false);
	//Llamamos la funci�n que hab�amos implementado en el Web Service e imprimimos lo que nos devuelve
	//Le enviamos como parametro el correo que hemos recibido como parametro en la URL
	$correo=$_GET['var'];
	$result = $soapclient->call('comprobar', array('x'=>$correo));
	echo $result;
?>	