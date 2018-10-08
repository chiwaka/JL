<?php
	header("Access-Control-Allow-Origin: *");
	header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
	header("Cache-Control: post-check=0, pre-check=0", false);
	header("Pragma: no-cache");
	require 'Usuarios.php';
	$idioma=$_POST["idioma"];
	if($idioma==1){
		include_once("Constantes.php");
	}else{
		include_once("ConstantesIngles.php");
	}
	$id=$_POST["id"];
	$idioma=$_POST["idioma"];
	$discoteca=$_POST["discoteca"];
	$fechafiesta=$_POST["fechafiesta"];
	//$retorno=Usuarios::getById($id,$discoteca,$fechafiesta);  
	list($retorno,$enviados,$recibidos,$flaspops)=Usuarios::getById($id,$discoteca,$fechafiesta); 
	$nombre=$retorno["Nombre"];
	$frase=$retorno["Frase"];
	$sexo=$retorno["Sexo"];
	if($sexo==1){
		$sexoliteral=$hombre;
	}else{
		$sexoliteral=$mujer;
	}
	$fechanacimiento=$retorno["FechaNacimiento"];
	$horoscopo=$retorno["horoscopo"];
	$busco=$retorno["Busco"];
	if($busco==1){
		$buscoliteral=$hombres;
	}else if($busco==2){
		$buscoliteral=$mujeres;
	}else{
		$buscoliteral=$hombresymujeres;
	}
	$datos=array("id"=>$id,"nombre"=>$nombre,"frase"=>$frase,"sexoliteral"=>$sexoliteral,"sexo"=>$sexo,"fechanacimiento"=>$fechanacimiento,"horoscopo"=>$horoscopo,"busco"=>$busco,"buscoliteral"=>$buscoliteral,
			   "enviados"=>$enviados,"recibidos"=>$recibidos,"flaspops"=>$flaspops);
	$datos2=array();
	$x=0;
	foreach($soy as $valor){
		$datos2["soy$x"]=$retorno["soy$x"];
		$x=$x+1;
	}
	$datos["soy"]=$datos2;
	$datos3=array();
	$x=0;
	foreach($megusta as $valor){
		$datos3["megusta$x"]=$retorno["megusta$x"];
		$x=$x+1;
	}
	$datos["megusta"]=$datos3;
	if(file_exists("./fotosperfiles/".$id.".jpg")){
			$foto=$id.".jpg";
		}else{
			if($row["Sexo"]==1){
				$foto="hombre.png";
			}else{
				$foto="mujer.jpg";
			}	
		}
	$datos["foto"]=$foto;
	switch($horoscopo){
		case "Aries":
			$datos["horoscopo"]=$aries;
			$fotohoroscopo="aries.png";
			break;
		case "Tauro":
			$datos["horoscopo"]=$tauro;
			$fotohoroscopo="tauro.png";
			break;
		case "Geminis":
			$datos["horoscopo"]=$geminis;
			$fotohoroscopo="geminis.png";
			break;
		case "Cancer":
			$datos["horoscopo"]=$cancer;
			$fotohoroscopo="cancer.png";
			break;
		case "Leo":
			$datos["horoscopo"]=$leo;
			$fotohoroscopo="leo.png";
			break;
		case "Virgo":
			$datos["horoscopo"]=$virgo;
			$fotohoroscopo="virgo.png";
			break;
		case "Libra":
			$datos["horoscopo"]=$libra;
			$fotohoroscopo="libra.png";
			break;
		case "Escorpio":
			$datos["horoscopo"]=$escorpio;
			$fotohoroscopo="escorpio.png";
			break;
		case "Sagitario":
			$datos["horoscopo"]=$sagitario;
			$fotohoroscopo="sagitario.png";
			break;
		case "Capricornio":
			$datos["horoscopo"]=$capricornio;
			$fotohoroscopo="capricornio.png";
			break;
		case "Acuario":
			$datos["horoscopo"]=$acuario;
			$fotohoroscopo="acuario.png";
			break;
		case "Piscis":
			$datos["horoscopo"]=$piscis;
			$fotohoroscopo="piscis.png";
			break;			
	}
	$datos["fotohoroscopo"]=$fotohoroscopo;
	$datos["edad"]=CalculaEdad($fechanacimiento)." $añosliteral";
	echo json_encode($datos);
	exit;
	function CalculaEdad( $fecha ) {
		list($Y,$m,$d) = explode("-",$fecha);
		return( date("md") < $m.$d ? date("Y")-$Y-1 : date("Y")-$Y );
	}
	$id2=$_POST["id2"];
	$id=$_POST["id"];
	$discoteca=$_POST["discoteca"];
	$FechaFiesta=$_POST["FechaFiesta"];
	$retorno=Usuarios::getByIdPlus($id2,$id,$discoteca,$FechaFiesta);  
	$fecha=$retorno["FechaNacimiento"];
	list($año,$mes,$dia)=explode("-",$fecha);
		$edad=date("Y")-$año;
		if(date("m").date("d")<$mes.$dia){
			$edad=$edad-1;
		}
	if(file_exists("./fotosperfiles/".$id2.".jpg")){
		$foto="./fotosperfiles/".$id2.".jpg";
	}else{
		if($retorno["sexo"]==1){
			$foto="./fotosperfiles/hombre.png";
		}else{
			$foto="./fotosperfiles/mujer.jpg";
		}
	}
	list($enviados,$recibidos,$pops)=Usuarios::flasesenviadosyrecibidos($id2,$discoteca,$FechaFiesta);	
	$nombre=$retorno["Nombre"];
	$sexo=$retorno["Sexo"];
	$parati=$retorno["ParaTi"];
	$parami=$retorno["ParaMi"];
	echo "<script>detalleflas.id=$id2;</script>";
	if($parati==1){
		if($parami==1){
			$estado="E11";
			$mensaje="FLASPOP";
			$boton=$mensajes;
		}else {
			$estado="E10";
			echo "<script>detalleflas.flas=$leenviasteunflas;</script>";
			$mensaje=$leenviasteunflas;
			$boton="";
		}
	}else{
		if($parami==1){
			$estado="E01";
			if($retorno==1){
				$mensaje=$elteenviounflas;
			}else{
				$mensaje=$ellateenviounflas;
			}
			$boton=$enviarpop;
		}else{
			$estado="E00";
			$mensaje="";
			$boton=$enviarflas;
		}
	}
	$cadenasoy="<p class=\"dtitulo\">SOY</p><ul>";
	$x=0;
	foreach($soysin as $valor){
		if($retorno[$valor]==1){
			if($retorno["Sexo"]==1){
				$cadenasoy=$cadenasoy."<li>".strtoupper($soy[$x])."</li>";
			}elseif ($retorno["Sexo"]==2){	
				$cadenasoy=$cadenasoy."<li>".strtoupper($soya[$x])."</li>";
			}
		}
		$x=$x+1;
	}
	$cadenasoy=$cadenasoy."</ul>";
	
	$cadenaaficiones="<p class=\"dtitulo\">ME GUSTA</p><ul>";
	$x=0;
	foreach($aficionessin as $valor){
		if($retorno[$valor]==1){
			$cadenaaficiones=$cadenaaficiones."<li>".strtoupper($aficiones[$x])."</li>";
		}
		$x=$x+1;
	}
	$cadenaaficiones=$cadenaaficiones."</ul>";
	$esfavorito=$retorno["esfavorito"];
	if($esfavorito){
		$mostrarfavorito="block";
		$mostrarmasfavorito="none";
	}else{
		$mostrarfavorito="none";
		$mostrarmasfavorito="block";
	}
	
	echo "<table style=\"width:100%;\">
			<tr style=\"width:100%;\">
				<td style=\"width:100%;text-align:center;ppadding-top:6px;\">
					<img id=\"fotodetalle\" class=\"ssombraimagen iimg-circle\" style=\"width:100%;margin-top:8px;\" src=$foto />
					<img id=\"masfavoritos\" src=\"masfavoritos.png\"  onclick=\"añadirfavoritos($id2,'$nombre');\" style=\"display:$mostrarmasfavorito;width:50px;margin-top:-58px;position:absolute;right:0px;margin-right:8px;\"/>
					<img id=\"favoritosdetalle\" src=\"favoritos2.png\"  onclick=\"quitarfavoritos($id2,'$nombre');\" style=\"display:$mostrarfavorito;position:absolute;top:18px;right:0px;margin-right:8px;\"/>
					<div id=\"nombreyedad\" style=\"padding-top:10px;\">
						<p style=\"font-size:28px;\">$nombre</p>
						<p style=\"font-size:16px;\">$edad años</p>";
	if($mensaje!="FLASPOP"){
		echo "<p id=\"mensajedetalle\" style=\"font-style:bold;color:#D4AF37;font-size:18px;margin-top:6px;\">$mensaje</p>";
	}else{
		echo "<img src=\"flaspop2.png\"/>";
	}
	echo "				</div>
					
				</td>
			</tr>
		</table>
		<hr style=\"margin-top:10px;margin-bottom:10px;border-color:#B54949;border-color:rgb(215,215,215);\" />
	<div style=\"background:rgb(245,245,245);padding-top:10px;padding-bottom:10px;\">
		<div class=\"container\">
			<table style=\"width:100%;\">
				<tr style=\"width:100%\">
					<td style=\"width:33.33%;text-align:center;\">
						<div class=\"circle-text\">
							<div><span id=\"detallesenviados\" style=\"font-size:34px;\">$enviados</span></div>
						</div>
					</td>
					<td style=\"width:33.33%\">
						<div class=\"circle-text\">
							<div><span id=\"detallesrecibidos\" style=\"font-size:34px;\">$recibidos</span></div>
						</div>
					</td>
					<td style=\"width:33.33%\">
						<div class=\"circle-text\">
							<div><span id=\"detallespops\" style=\"font-size:34px;\">$pops</span></div>
						</div>
					</td>
				</tr>
			</table>
			<table style=\"width:100%;\">			
				<tr>
					<td style=\"width:33.33%;text-align:center;\">
						<p style=\"font-size:14px;\">ENVIADOS</p>
					</td>
					<td style=\"width:33.33%;text-align:center;\">
						<p style=\"font-size:14px;\">RECIBIDOS</p>
					</td>
					<td style=\"width:33.33%;text-align:center;\">
						<p style=\"font-size:14px;\">FLASPOP</p>
					</td>
				</tr>
			</table>
		</div>	
	</div>			
	<hr style=\"margin-top:10px;margin-bottom:10px;border-color:#B54949;border-color:rgb(215,215,215);\" />
	<div class=\"contenedor\">
		<div>
			$cadenasoy
		</div>	
		<hr style=\"margin-top:10px;margin-bottom:10px;border-color:#B54949;border-color:rgb(215,215,215);\" />
		<div>
			$cadenaaficiones
		</div>
		<hr style=\"margin-top:10px;margin-bottom:10px;border-color:#B54949;border-color:rgb(215,215,215);\" />
	</div>";
	echo "<div style=\"width:80%;margin:auto;\">";
	if($estado=="E00"){
		echo "<button type \"button\" class=\"btn\" onclick=\"enviarflas($id2);\">$boton</button>";
	}else if($estado=="E01"){
		echo "<button type \"button\" class=\"btn\" onclick=\"enviarpop($id2);\">$boton</button>";
	}else if($estado=="E10"){
		//NADA
	}else if($estado=="E11"){
		echo "<button type \"button\" class=\"btn\" onclick=\"mensajes($id2);\">$boton</button>";
	}
	echo "</div>";
	return;
		
		
		
		
		
		
		
		
		
		
		
		
		
	$esfavorito=$retorno["esfavorito"];
	//$cadenasoy="Soy";
	$cadenasoy="<p>SOY</p><ul>";
	//$cadenaaficiones="Me gusta";
	$cadenaaficiones="<p>ME GUSTA</p><ul>";
	$x=0;
	foreach($soysin as $valor){
		if($retorno[$valor]==1){
			if($retorno["Sexo"]==1){
				//$cadenasoy=$cadenasoy." ".strtolower($soy[$x]).",";
				$cadenasoy=$cadenasoy."<li>".$soy[$x]."</li>";
			}elseif ($retorno["Sexo"]==2){	
				//$cadenasoy=$cadenasoy." ".strtolower($soya[$x]).",";
				$cadenasoy=$cadenasoy."<li>".$soya[$x]."</li>";
			}
		}
		$x=$x+1;
	}
	//$cadenasoy=trim($cadenasoy, ',');
	//$cadenasoy=$cadenasoy.".";
	$cadenasoy=$cadenasoy."</ul>";
	$cadenasoy=strtoupper($cadenasoy);
	$x=0;
	foreach($aficionessin as $valor){
		if($retorno[$valor]==1){
			//$cadenaaficiones=$cadenaaficiones." ".$articulos[$x]." ".strtolower($aficiones[$x]).",";
			$cadenaaficiones=$cadenaaficiones."<li>".strtolower($aficiones[$x])."</li>";
		}
		$x=$x+1;
	}
	//$cadenaaficiones=trim($cadenaaficiones, ',');
	//$cadenaaficiones=$cadenaaficiones.".";
	$cadenaaficiones=strtoupper($cadenaaficiones);
	if(file_exists("./fotosperfiles/".$id2.".jpg")){
		$tienefoto=true;
	}else{
		$tienefoto=false;
	}
	$parati=$retorno["ParaTi"];
	$parami=$retorno["ParaMi"];
	if($parati==1){
		if($parami==1){
			$estado="E11";
			$mensaje="FLASPOP";
		}else {
			$estado="E10";
			$mensaje=$leenviasteunflas;
			$boton="";
		}
	}else{
		if($parami==1){
			$estado="E01";
			if($retorno==1){
				$mensaje=$elteenviounflas;
			}else{
				$mensaje=$ellateenviounflas;
			}
			$boton=$enviarpop;
		}else{
			$estado="E00";
			$mensaje="";
			$boton=$enviarflas;
		}
	}
	list($enviados,$recibidos,$pops)=Usuarios::flasesenviadosyrecibidos($id2);
	
	$datos=array("id"=>$id2,"Nombre"=>$retorno["Nombre"],"Edad"=>$edad,"Soy"=>$cadenasoy,"Aficiones"=>$cadenaaficiones,"TieneFoto"=>$tienefoto,"ParaTi"=>$parati,ParaMi=>$parami,"Enviados"=>$enviados,"Recibidos"=>$recibidos,"Pops"=>$pops,"esfavorito"=>$esfavorito,"estado"=>$estado,"mensaje"=>$mensaje,"boton"=>$boton);
	echo json_encode($datos);
?>