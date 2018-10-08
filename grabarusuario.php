<?php
	header("Access-Control-Allow-Origin: *");
	header("Access-Control-Allow-Headers: Content-Type,Cache-Control");
	header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
	header("Cache-Control: post-check=0, pre-check=0", false);
	header("Pragma: no-cache");
	require 'Usuarios.php';
		
	//$sin=array("Extrovertido", "Payaso", "Simpatico", "Soñador","Inquieto","Cine","Motos","Campo","Playa","Futbol");
	//$variables=array($Guapo, $Payaso, $Simpatico, $Soñador,$Inquieto,$Cine,$Motos,$Campo,$Playa,$Futbol);
	
	
	
	$variablesliteral=array();
	$variables=array();
	$id=$_POST["id"];
	$idioma=$_POST["idioma"];
	$nombre=$_POST["nombre"];
	$frase=$_POST["frase"];
	$sexo=$_POST["sexo"];
	$fecha=$_POST["fecha"];
	$busco=$_POST["busco"];
	$idioma=$_POST["idioma"];
	$datasoy=$_POST["datasoy"];
	$datamegusta=$_POST["datamegusta"];
	//$idiomaanterior=$_POST["idiomaanterior"];
	if($idioma==1){
		include_once("Constantes.php");
	}else{
		include_once("ConstantesIngles.php");
	}
	/*
				$sin=array_merge($soysin,$aficionessin);
				$x=0;
				foreach($sin as $valor){
					$variablesliteral[$x]=$valor;
					$variables[$x]=$_POST[$valor];
					$x=$x+1;
				}
				$resultado = Usuarios::update($id,$nombre,$sexo,$fecha,$busco,$idioma,$variablesliteral,$variables);
	*/
	//$resultado=Usuarios::grabarusuario($id,$nombre,$fecha,$idioma);
	$resultado=Usuarios::grabarusuario($id,$nombre,$frase,$sexo,$fecha,$busco,$datasoy,$datamegusta);
	if($resultado==0){
		$frase=$nosehapodidograbarelperfil;
	}else{
		$frase=$elperfilhasidograbadocorrectamente;
	}
	$frase=$elperfilhasidograbadocorrectamente;
	$datos=array("resultado"=>$resultado,"frase"=>$frase);
	echo json_encode($datos);
	
	//if($resultado){$bien=1;}else{$bien=0;}
	//$frase=$elperfilhasidograbadocorrectamente;
	/*
	if($idioma==$idiomaanterior){
		$frase=$elperfilhasidograbadocorrectamente;
	}else{
		$frase=$realizandoelcambiodeidioma;
	}
	*/
	//$datos=array("resultado"=>$bien,"frase"=>$frase);
	//echo json_encode($datos);
	//if($resultado){echo 1;}else{echo 0;}
?>