<?php
	header("Access-Control-Allow-Origin: *");
	header("Access-Control-Allow-Headers: Content-Type,Cache-Control");
	header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
	header("Cache-Control: post-check=0, pre-check=0", false);
	header("Pragma: no-cache");
	require 'Usuarios.php';
	$id=$_POST["id"];
	$usuariosporpagina=$_POST["usuariosporpagina"];
	$elemento=$_POST["elemento"];
	$tipo=$_POST["tipo"];
	$busco=$_POST["busco"];
	$sexo=$_POST["sexo"];
	$idioma=$_POST["idioma"];
	$discoteca=$_POST["discoteca"];
	$FechaFiesta=$_POST["FechaFiesta"];
	if(isset($_POST["where"])){
		$where=$_POST["where"];
	}else{
		$where="";
	}
	/*
	if($idioma==1){
		include_once("Constantes.php");
	}else{
		include_once("ConstantesIngles.php");
	}
	*/
	
	/*
	echo $id;
	echo "usuariosporpagina".$usuariosporpagina;
	echo "elemento".$elemento;
	echo "tipo".$tipo;
	
	exit;
	*/
	list($total,$datos)=Usuarios::Buscar($id,$busco,$sexo,$elemento,$usuariosporpagina,$tipo,$where,$discoteca,$FechaFiesta); 
	echo "<div class=\"fondo\" style=\"width:100%;overflow:hidden;\">";
	foreach($datos as $row){
		$nombre=$row["Nombre"];
		$dentro=$row["dentro"];
		$fecha=$row["FechaNacimiento"];
		$id=$row["_id"];
		$parati=$row["ParaTi"];
		$parami=$row["ParaMi"];
		$cadena="probando probando";
		$favoritos=$row["idFavoritos"];
		$sexo=$row["Sexo"];
		if($parati==1){
			$cadena=$leenviasteunflas;
		}
		if($parami==1){
			if($sexo==1){
				$cadena=$elteenviounflas;
			}else{
				$cadena=$ellateenviounflas;
			}
		}	
		list($año,$mes,$dia)=explode("-",$fecha);
		$edad=date("Y")-$año;
		if(date("m").date("d")<$mes.$dia){
			$edad=$edad-1;
		}
		if(file_exists("./fotosperfiles/".$id.".jpg")){
			$foto="http://www.afassvalencia.es/android/flaspop/fotosperfiles/".$id.".jpg";
		}else{
			if($row["Sexo"]==1){
				$foto="./fotosperfiles/hombre.png";
			}else{
				$foto="http://www.afassvalencia.es/android/flaspop/fotosperfiles/mujer.jpg";
			}	
		}
		if($row["Tiempo"]==1){
			
			$esta="<image src=\"imagenes/agujeroverde3pequeño.jpg\" />";
			
		}else{
			
			$esta="<image src=\"imagenes/agujeropequeño.jpg\" style=\"margin-top:2px !important;\"/>";
			
		}
		if(is_null($favoritos)==false){
			$favorito="<img id=\"favorito$id\" src=\"imagenes/favoritos2.png\" style=\"width:20px;margin-top:-1px;margin-right:6px !important\" />";
		}else{
			$favorito="";
		}	
		echo "<div style=\"display:block;float:left;background-color:white;padding:6px !important;width:99%;border:1px solid gray;border-radius:6px;-webkit-box-shadow: 1px 2px 5px 0px rgba(0,0,0,0.90);-moz-box-shadow: 1px 1px 5px 0px rgba(0,0,0,0.90);box-shadow: 1px 1px 5px 0px rgba(0,0,0,0.90);\">
				<img class=\"imagenfoto\" style=\"display:block;float:left;width:40%;border-radius:6px 0 0 6px;padding:0 !important\" src=\"$foto\" />
				<div class=\"anchodecelda\" style=\"display:block;float:left;\">
					<div class=\"flexcolumn\" style=\"justify-content:space-between;width:100%;height:100%;padding:0 ! important\">
						<div class=\"flexrow w100\" style=\"justify-content: flex-end;\">
							$favorito 
							$esta
						</div>
						<div class=\"flexcolumn\"> 
							<p style=\"color:rgb(100,100,100);font-family:Arial;font-style:bold;font-size:24px;\">$nombre</p>
							<p style=\"font-size:14px;color:rgb(100,100,100);margin-top:4px;font-style:italic;\">$edad $añosliteral</p>
						</div>
						<span id=\"cadena$id\" style=\"margin:0;padding:0;font-style:bold;color:#D4AF37;font-size:16px;\">$cadena</span>
					</div>
				</div>
			</div>";
		
		echo "<hr class=\"uno\" />
			<hr class=\"dos\" />";
		//echo "<hr style=\"margin-top:14px;margin-bottom:0px;border:1px solid rgb(50,50,50);\" />";	
		//echo "<hr style=\"margin-top:0px;margin-bottom:0px;border:1px solid rgb(100,100,100);\" />";	
		
	}
	
	if($elemento+$usuariosporpagina<$total){
		echo "<div class=\"buscarmas fondo\" onclick=\"vermas('$tipo');\" style=\"margin-top:40px;margin-bottom:40px;\">";
		echo "<p class=\"colorletra\" style=\"width:100%;text-align:center;font-size:20px;font-style:italic;\">$vermas</p>";
		echo "</div>";
	}
	echo "</div>";
?>	