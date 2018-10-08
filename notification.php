<?php 
header("Access-Control-Allow-Origin: *");
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
$headers = [ 
   "'Authorization:key=AIzaSyA13sds5VrXfJona6e9Rm8gLUaXLGyypJg",
   "Content-Type: application/json" 
 ]; 
  
  /*
 if($token->platform === 'ios') { 
     $data = [ 
         'to' => $token->device_token, 
         'notification' => [ 
             'body' 	=> $notification_request->message, 
             'title'	=> $notification_request->title, 
         ], 
         "data" => [// aditional data for iOS 
             "extra-key" => "extra-value", 
         ], 
         'notId' => $not->id,//unique id for each notification 
     ]; 
 } elseif ($token->platform === 'android') { 
 */
$mitoken=$_POST["token"];
     $data = [ 
         'to' => '$mitoken',
         'data' => ['body'	=> "Este es el cuerpo",'title'=> "Título"] 
     ]; 
 /*
 } 
 */
 $ch = curl_init(); 
 curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' ); 
 curl_setopt( $ch,CURLOPT_POST, true ); 
 curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers ); 
 curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true ); 
 curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false ); 
 curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $data ) ); 
 curl_setopt($ch, CURLOPT_FAILONERROR, TRUE); 
 $result = curl_exec($ch); 
 curl_close( $ch ); 
 echo $result;
 ?>
