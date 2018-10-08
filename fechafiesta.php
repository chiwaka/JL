<?php	
header("Access-Control-Allow-Origin: *");
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
ini_set('date.timezone','Europe/Madrid'); 
$fechafiesta=date(dmY);
$hora=date(H);
if($hora<10){
	$fecha = mktime(0, 0, 0, date("m")  , date("d")-1, date("Y"));
}else{
	$fecha=mktime(0, 0, 0, date("m")  , date("d"), date("Y"));
}
$fechafiesta=date('dmY',$fecha);
echo $fechafiesta;
?>