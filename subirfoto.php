<?php
	header("Access-Control-Allow-Origin: *");
	header("Access-Control-Allow-Headers: Content-Type,Cache-Control");
	header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
	header("Cache-Control: post-check=0, pre-check=0", false);
	header("Pragma: no-cache");
	$id=$_POST["id"];
	$cadenafoto=$_POST["cadenafoto"];
	$angulo=$_POST["angle"];
	$escala=$_POST["scale"];
	$x=$_POST["x"];
	$y=$_POST["y"];
	$ancho=$_POST["w"];
	$alto=$_POST["h"];
	if($angulo==90){
		$angulo=270;
	}else if($angulo==270){
		$angulo=90;
	}	
	$Base64Img = base64_decode($cadenafoto);
	$img_r = imagecreatefromstring($Base64Img);
	$dst_r = ImageCreateTrueColor( 300, 300 );
	$img_r=imagerotate($img_r,$angulo,0);
	$anchoescalado=imagesx($img_r)*$escala;
	$img_r=imagescale($img_r,$anchoescalado);
	imagecopyresampled($dst_r,$img_r,0,0,$x,$y,300,300,$ancho,$alto);
	//imagejpeg($dst_r, "../fotosperfil/".$id.".jpg", 90);
	imagejpeg($dst_r, "fotosperfiles/$id.jpg", 90);
?>
