<?php
header("Access-Control-Allow-Origin: *");
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

/**
 * Obtiene todas las metas de la base de datos
 */

require 'Barcos.php';

if($_SERVER['REQUEST_METHOD'] == "GET"){

    // Manejar petición GET
   $id=$_GET["id"]; 
   $metas = Barcos::getById($id);

    if ($metas) {

        $datos["estado"] = 1;
        $datos["metas"] = $metas;

        print json_encode($datos);
    } else {
        print json_encode(array(
            "estado" => 2,
            "mensaje" => "Ha ocurrido un error"
        ));
    }
}
?>