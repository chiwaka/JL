<?php
	header("Access-Control-Allow-Origin: *");
	header("Access-Control-Allow-Headers: Content-Type,Cache-Control");
	header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
	header("Cache-Control: post-check=0, pre-check=0", false);
	header("Pragma: no-cache");
	require 'Usuarios.php';
	$id=$_POST["id"];
	$idioma=$_POST["idioma"];
	if($idioma==1){
		include_once("Constantes.php");
	}else{
		include_once("ConstantesIngles.php");
	}
	$discoteca=$_POST["discoteca"];
	$fechafiesta=$_POST["fechafiesta"];
	//$usuariosporpagina=$_POST["usuariosporpagina"];
	//$elemento=$_POST["elemento"];
	$datos=Usuarios::favoritos($id,$discoteca,$fechafiesta);
	$datos2=array();
	foreach($datos as $row){
		$id=$row["_id"];
		if(file_exists("./fotosperfiles/".$id.".jpg")){
			$row["foto"]=$id.".jpg";
		}else{
			if($row["Sexo"]==1){
				$row["foto"]="hombre.png";
			}else{
				$row["foto"]="mujer.jpg";
			}	
		}
		$row["esta"]="imagenes/agujeroverde3pequeño.jpg";
		array_push($datos2, $row);
	}
	echo json_encode(array("flasl"=>$flasl,"flast"=>$flast,"datos"=>$datos2));
?>	