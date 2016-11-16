<?php
//incluimos la clase nusoap.php
require_once('lib/nusoap.php');
require_once('lib/class.wsdlcache.php');
//creamos el objeto de tipo soap_server
$ns="http://eisw.hol.es/LAB-6PO/HTML4/samples";
$server = new soap_server;
$server->configureWSDL('ComprobarContrasena',$ns);
$server->wsdl->schemaTargetNamespace=$ns;
//registramos la función que vamos a implementar
//se podría registrar mas de una función
$server->register('ComprobarContrasena', array('x'=>'xsd:string','y'=>'xsd:string'), array('z'=>'xsd:string'), $ns);
//implementamos la función
function ComprobarContrasena ($x, $y){
       
    $file1 = fopen("tickets.txt", "r");
        //$NumTicket = "$y";
	while(!feof($file1)) {
           $linea1 = trim(fgets($file1));
		   if(strcmp($linea1,$y)==0){
				$file2 = fopen("toppasswords.txt", "r");
				while(!feof($file2)) {
                       $linea2 = trim(fgets($file2));
					   if(strcmp($linea2, $x)==0){
							
							return "INVALIDA";
						}    
                }
				
	            return "VALIDA";  
            }         
    }
	
    return "USUARIO NO AUTORIZADO";
}

//llamamos al método service de la clase nusoap
$rawPostData = file_get_contents("php://input");
$server->service($rawPostData);
?>