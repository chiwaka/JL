<?php
	header("Access-Control-Allow-Origin: *");
	header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
	header("Cache-Control: post-check=0, pre-check=0", false);
	header("Pragma: no-cache");
	require 'Usuarios.php';
	$id=$_POST["id"];
	$discoteca=$_POST["discoteca"];
	$FechaFiesta=$_POST["FechaFiesta"];
	$datos=Usuarios::VerMensajesGeneral($id,$discoteca,$FechaFiesta);
	$datos2=array();
	foreach($datos as $row){
		$id=$row["Persona"];
		if(file_exists("./fotosperfiles/".$id.".jpg")){
			$row["foto"]=$id.".jpg";
		}else{
			if($row["Sexo"]==1){
				$row["foto"]="hombre.png";
			}else{
				$row["foto"]="mujer.jpg";
			}	
		}
		$row["ParaTi"]=0;
		$row["ParaMi"]=0;
		$row["_id"]=$row["Persona"];
		array_push($datos2, $row);
	}
	echo json_encode($datos2);
	exit;
	foreach($datos as $row){
		$identificador=$row["Persona"];
		$nombre=$row["Nombre"];
		$mensaje=$row["Texto"];
		if(file_exists("./fotosperfiles/".$identificador.".jpg")){
			$foto="./fotosperfiles/".$identificador.".jpg";
		}else{
			if($row["Sexo"]==1){
				$foto="./fotosperfiles/hombre.png";
			}else{
				$foto="./fotosperfiles/mujer.jpg";
			}	
		}
		if($row["Tiempo"]==1){
			$esta="<image src=\"agujeroverde3pequeño.jpg\" />";
		}else{
			$esta="<image src=\"agujeropequeño.jpg\" />";
		}
		if(is_null($favoritos)==false){
			$favorito="<img id=\"favorito$id\" src=\"favoritos2.png\" style=\"width:20px;margin-top:-4px;\" />";
		}else{
			$favorito="";
		}	
		echo "
			<div style=\"display:table;width:100%;padding:0 10px 0px 10px;margin:0;border:0px solid red;margin-top:10px;\" onclick=\"mensajes($identificador);\">
				<div style=\"display:table-row;float:left;width:100%;margin:0;padding:4px;border-radius:6px;background:white;-webkit-box-shadow: 1px 1px 5px 0px rgba(0,0,0,0.90);-moz-box-shadow: 1px 1px 5px 0px rgba(0,0,0,0.90);box-shadow: 1px 1px 5px 0px rgba(0,0,0,0.90);\">
					<div style=\"display:table-cell;width:40%;margin:0;\">
						<img style=\"width:100%;border-radius:6px 0 0 6px;\" src=\"$foto\" />
					</div>
					<div style=\"display:table-cell;width:60%;background:white;border-radius:0px 6px 6px 0;height:100%;margin:0;vertical-align:middle;padding:0px;\">
						<table style=\"width:100%;height:100%;border:0px solid red;\"><tr style=\"border:0;\"><td style=\"text-align:right;vertical-align:top;padding:0 0 0 6px;\">
							<p style=\"color:rgb(100,100,100);font-family:Arial;font-style:bold;font-size:24px;float:left;\">$nombre</p> $favorito $esta
						</td></tr>
						<tr style=\"border:0;\"><td style=\"text-align:left;height:100%;vertical-align:top;padding:0 6px 6px 6px;\">
							<p style=\"font-size:14px;color:rgb(100,100,100);margin-top:4px;font-family:Arial;\">$mensaje</p>
						</td></tr>
						</table>
					</div>
				</div>
			</div>";	
		echo "
			<hr class=\"uno\" />
			<hr class=\"dos\" />
			";
	}	
?>