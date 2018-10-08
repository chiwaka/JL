<?php
	header("Access-Control-Allow-Origin: *");
	header("Access-Control-Allow-Headers: Content-Type,Cache-Control");
	header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
	header("Cache-Control: post-check=0, pre-check=0", false);
	header("Pragma: no-cache");
	require 'Usuarios.php';
	$id=$_POST["id"];
	$id2=$_POST["id2"];
	//$nombre=$_POST["nombre"];
	$idioma=$_POST["idioma"];
	if($idioma==1){
		include_once("Constantes.php");
	}else{
		include_once("ConstantesIngles.php");
	}
	$resultado=Usuarios::AñadirFavoritos($id,$id2);
	$frase=$nombre." ".$ponerenfavoritos;
	$datos=array("resultado"=>$resultado,"frase"=>$frase,id2=>$id2);
	echo json_encode($datos);
?>