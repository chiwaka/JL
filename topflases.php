<?php
	header("Access-Control-Allow-Origin: *");
	header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
	header("Cache-Control: post-check=0, pre-check=0", false);
	header("Pragma: no-cache");
	require 'Usuarios.php';
	$sexo=$_POST["sexo"];
	$busco=$_POST["busco"];
	$discoteca=$_POST["discoteca"];
	$FechaFiesta=$_POST["FechaFiesta"];
	$datos=Usuarios::TopFlases($sexo,$busco,$discoteca,$FechaFiesta);
	$resultado=array();
	foreach($datos as $row){
		$parcial=array();
		$id=$row["CodigoUsuario2"];
		if(file_exists("./fotosperfiles/".$id.".jpg")){
			$foto=$id.".jpg";
		}else{
			if($row["Sexo"]==1){
				$foto="hombre.png";
			}else{
				$foto="mujer.jpg";
			}	
		}
		$parcial["id"]=$id;
		$parcial["foto"]=$foto;
		array_push($resultado,$parcial);
	}
	echo json_encode(array("resultado"=>$resultado));
	/*
	$cadena="";
	foreach($datos as $row){
		$idd=$row["CodigoUsuario2"];
		if(file_exists("./fotosperfiles/".$idd.".jpg")){
			$foto="http://www.afassvalencia.es/android/flaspop/fotosperfiles/".$idd.".jpg";
		}else{
			if($row["Sexo"]==1){
				$foto="imagenes/hombre.png";
			}else{
				$foto="imagenes/mujer.jpg";
			}	
		}
		if($cadena==""){
			$cadena=$foto;
		}else{
			$cadena=$cadena.";".$foto;
		}
	}
	echo $cadena;
	*/
	exit;
	
	
	
	$longitud=count($datos)*106;
	echo "<ul id=\"top\" style=\"position:relative;width:".$longitud."px\">";
	$x=74;
	$y=1;
	foreach($datos as $row){
		$idd=$row["CodigoUsuario2"];
		if(file_exists("./fotosperfiles/".$idd.".jpg")){
			$foto="http://www.afassvalencia.es/android/flaspop/fotosperfiles/".$idd.".jpg";
		}else{
			if($row["Sexo"]==1){
				$foto="./fotosperfiles/hombre.png";
			}else{
				$foto="./fotosperfiles/mujer.jpg";
			}	
		}					
		echo "<li><figure class='post_image'><img src=\"$foto\" style=\"width:100%;\" onclick=\"detalle($idd)\"/></figure></li>";
		echo "<p class=\"badge\" style=\"position:absolute;font-family:Arial;font-size:12px;width:25px;background-color:maroon;top:2px;left:$x"."px;\">$y</p>";
		$x=$x+106;
		$y=$y+1;
	}
	echo "</ul>";
	echo "<p>$sexo</p>";
	echo "<p>$busco</p>";
	echo "<p>$FechaFiesta</p>";
?>					