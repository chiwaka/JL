<?php
	header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
	header("Cache-Control: post-check=0, pre-check=0", false);
	header("Pragma: no-cache");
	require 'Usuarios.php';
	$id2=$_POST["id2"];
	$id=$_POST["id"];
	$discoteca=$_POST["discoteca"];
	$FechaFiesta=$_POST["FechaFiesta"];
	$datosnombre=Usuarios::getById($id2);
	$nombre=strtoupper($datosnombre["Nombre"]);
	list($datos,$dentro)=Usuarios::VerMensajes($id,$id2,$discoteca,$FechaFiesta);
	$texto="";
	foreach($datos as $row){
		$mensaje=$row["Texto"];
		$hora= date ( "H:i",$row["Fecha"]);
		if($row["CodigoUsuario1"]==$id){
			$foto="./fotosperfiles/$id.jpg";
			$clase="yo";
		}else{
			$foto="./fotosperfiles/$id2.jpg";
			$clase="otro";
		}
		$texto=$texto."<div class=\"$clase img-rounded\" style=\"max-width:75%;\"><p>$mensaje</p><p class=\"hora\">$hora";
		if($row["leido"]){
			$texto=$texto."<img style=\"width:10px;margin-top:-10px;margin-left:2px;\" src=\"checkazul.png\" />";
		}	
		$texto=$texto."</p></div>";
	}
	$texto=$texto. "<div id=\"escritura\" style=\"position:fixed;bottom:0;left:0;width:100%;height:60px;background:white;\">
				 <table style=\"width:100%;height:100%;\">
					<tr>
						<td style=\"vertical-align:middle;padding-left:10px;padding-right:10px;\">
							<input type=\"text\" id=\"edicionmensaje\" class=\"form-control\" style=\"width:100%;text-align:left;margin-right:8px;font-size:16px;\" value=\"\" />
						</td>
						<td>
							<button type=\"button\" class=\"btn btn-primary\" onclick=\"grabarmensaje($id2);\">Enviar</button>
						</td>
					</tr>
				</table>	
		</div> ";
	if(file_exists("./fotosperfiles/".$id2.".jpg")){
		$foto="./fotosperfiles/".$id2.".jpg";
	}else{
		if($row["Sexo"]==1){
			$foto="./fotosperfiles/hombre.png";
		}else{
			$foto="./fotosperfiles/mujer.jpg";
		}	
	}		
	$bar="<table>
			<tr>
				<td style=\"vertical-align:middle;width:50px;\">
					<img id=\"imagenenbar\" class=\"img-circle\" style=\"height:50px;margin-left:10px;margin-right:8px;\" src=\"$foto\" />
				</td>
				<td style=\vertical-align:middle;\">
					<p style=\"color:white;font-size:20px;\">$nombre</p>
				</td>";
	if($row["Tiempo"]==1){
		$esta="<image src=\"agujeroverde3pequeño.jpg\" />";
	}else{
		$esta="<image src=\"agujeropequeño.jpg\" />";
	}
	$bar=$bar."<td style=\"position:relative;width:14px;border:0px solid red;padding:0 10px 0 0;\">
				$esta
			</td>
			</tr>
		</table>";	
	$datos=array(bar=>$bar,texto=>$texto);
	echo json_encode($datos);	
?>