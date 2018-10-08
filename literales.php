<?php
	header("Access-Control-Allow-Origin: *");
	header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
	header("Cache-Control: post-check=0, pre-check=0", false);
	header("Pragma: no-cache");
	$idioma=$_POST["idioma"];
	$fechafiesta=$_POST["FechaFiesta"];
	if($idioma==1){
		include_once("Constantes.php");
	}else{
		include_once("ConstantesIngles.php");
	}
	if (file_exists("carteles/$fechafiesta.jpg")) {
		$cartel=1;
	}else{
		$cartel=0;
	}
	$datos=array("cartel"=>$cartel,"labelentrar"=>$labelentrar,"labelenviados"=>$labelenviados,"labelrecibidos"=>$labelrecibidos,"labelseleccionaelorigendelaimagen"=>$labelseleccionaelorigendelaimagen,
			   "labelunafrase"=>$labelunafrase,"soy"=>$soy,"megusta"=>$megusta,"añosliteral"=>$añosliteral,"aries"=>$aries,"tauro"=>$tauro,"geminis"=>$geminis,"cancer"=>$cancer,"leo"=>$leo,
			   "virgo"=>$virgo,"libra"=>$libra,"escorpio"=>$escorpio,"sagitario"=>$sagitario,"capricornio"=>$capricornio,"acuario"=>$acuario,"piscis"=>$piscis);
	echo json_encode($datos);
?>