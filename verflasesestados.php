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
	$tipo=$_POST["tipo"];
	$datos=Usuarios::flasesestados($id,$discoteca,$fechafiesta,$tipo);
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
	if($tipo==1){
		$titulo=$enviados;
	}elseif($tipo==2){
		$titulo=$recibidos;
	}elseif($tipo==3){
		$titulo="FLASPOPS";
	}
	echo json_encode(array("flasl"=>$flasl,"flast"=>$flast,"titulo"=>$titulo,"datos"=>$datos2));
?>	