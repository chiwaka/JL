<?php
	header("Access-Control-Allow-Origin: *");
	header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
	header("Cache-Control: post-check=0, pre-check=0", false);
	header("Pragma: no-cache");
	require 'Usuarios.php';
	$id=$_POST["id"];
	$id2=$_POST["id2"];
	$discoteca=$_POST["discoteca"];
	$fechafiesta=$_POST["fechafiesta"];
	$retorno=Usuarios::enviarflas($id,$id2,$discoteca,$fechafiesta);  
	echo $retorno;	
?>	