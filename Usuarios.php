<?php

/**
 * Representa el la estructura de las metas
 * almacenadas en la base de datos
 */
require_once 'Database.php';

class Usuarios
{
    function __construct()
    {
	date_default_timezone_set('Europe/Madrid');
    }

    /**
     * Retorna en la fila especificada de la tabla 'meta'
     *
     * @param $idMeta Identificador del registro
     * @return array Datos del registro
     */
    public static function getAll()
    {
        $consulta = "SELECT * FROM Usuarios";
        try {
            // Preparar sentencia
            $comando = Database::getInstance()->getDb()->prepare($consulta);
            // Ejecutar sentencia preparada
            $comando->execute();

            return $comando->fetchAll(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            return false;
        }
    }

    /**
     * Obtiene los campos de una meta con un identificador
     * determinado
     *
     * @param $idMeta Identificador de la meta
     * @return mixed
     */
   public static function guardartoken($id,$token){
	$consulta="Update Usuarios set token='$token' where _id=$id";
	try {
            // Preparar sentencia
            $comando = Database::getInstance()->getDb()->prepare($consulta);
            // Ejecutar sentencia preparada
            $comando->execute();
	    return 1;	
        } catch (PDOException $e) {
            // Aquí puedes clasificar el error dependiendo de la excepción
            // para presentarlo en la respuesta Json
            return -1;
        }
   }
    
    public static function getById($id,$discoteca,$fechafiesta)
    {
        $consulta="SELECT * from Usuarios Where _id=?";
        try {
            // Preparar sentencia
            $comando = Database::getInstance()->getDb()->prepare($consulta);
            // Ejecutar sentencia preparada
            $comando->execute(array($id));
            // Capturar primera fila del resultado
            $row = $comando->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            // Aquí puedes clasificar el error dependiendo de la excepción
            // para presentarlo en la respuesta Json
            return -1;
        }
	$consulta="Select Count(*) as flasesenviados from Flases where CodigoUsuario1=$id and Codigodiscoteca=$discoteca and FechaFiesta='$fechafiesta'";
	$comando = Database::getInstance()->getDb()->prepare($consulta);
	$comando->execute();
	$enviados=$comando->fetchColumn();
	
	$consulta="Select Count(*) as flasesrecibidos from Flases where CodigoUsuario2=$id and Codigodiscoteca=$discoteca and FechaFiesta='$fechafiesta'";
	$comando = Database::getInstance()->getDb()->prepare($consulta);
	$comando->execute();
	$recibidos=$comando->fetchColumn();
	
	$consulta="SELECT Count(*) from (Select * from Flases where CodigoUsuario1=$id and CodigoDiscoteca=$discoteca and FechaFiesta='$fechafiesta') as Consulta1 
			LEFT JOIN (Select * from Flases Where CodigoUsuario2=$id and CodigoDiscoteca=$discoteca and FechaFiesta='$fechafiesta') as Consulta2 
			on Consulta1.CodigoUsuario2=Consulta2.CodigoUsuario1 
			where Consulta1.CodigoUsuario1=Consulta2.CodigoUsuario2";
	$comando = Database::getInstance()->getDb()->prepare($consulta);
	$comando->execute();
	$flaspops=$comando->fetchColumn();
	return array($row,$enviados,$recibidos,$flaspops);
    }
public static function getByIdPlus($id2,$id,$discoteca,$FechaFiesta)
    {
	$consulta="Select Usuarios.*,if(Flases.CodigoUsuario1=$id,1,0) as ParaTi,if(Flases2.CodigoUsuario2=$id,1,0) as ParaMi, if(favoritos.CodigoUsuario1=337,1,0) as esfavorito from Usuarios 
			left join favoritos on favoritos.CodigoUsuario1=$id and favoritos.CodigoUsuario2=$id2
			left join Flases on Flases.CodigoUsuario1=$id and Flases.CodigoUsuario2=$id2 and Flases.CodigoDiscoteca=$discoteca and Flases.FechaFiesta='$FechaFiesta' 
			left join Flases as Flases2 on Flases2.CodigoUsuario1=$id2 and Flases2.CodigoUsuario2=$id and Flases2.CodigoDiscoteca=$discoteca and Flases2.FechaFiesta='$FechaFiesta' where Usuarios._id=$id2";
	/*
	$consulta="Select Usuarios.*,if(Flases.CodigoUsuario1=$id,1,0) as ParaTi,if(Flases2.CodigoUsuario1=$id2,1,0) as ParaMi,if(favoritos.CodigoUsuario1=$id,1,0) as esfavorito from Usuarios 
		       left join Flases on Flases.CodigoUsuario1=$id and Flases.CodigoUsuario2=$id2 and Flases.CodigoDiscoteca=$discoteca and Flases.FechaFiesta='$FechaFiesta'
		       left join Flases as Flases2 on Flases2.CodigoUsuario2=$id and Flases2.CodigoUsuario1=$id2 and Flases2.CodigoDiscoteca=$discoteca and Flases2.FechaFiesta='$FechaFiesta'
		       left join favoritos on favoritos.CodigoUsuario1=$id and favoritos.CodigoUsuario2=$id2 
                       where Usuarios._id=$id2";
	*/	       
        try {
            // Preparar sentencia
            $comando = Database::getInstance()->getDb()->prepare($consulta);
            // Ejecutar sentencia preparada
           // $comando->execute(array($id));
	    $comando->execute();
            // Capturar primera fila del resultado
            $row = $comando->fetch(PDO::FETCH_ASSOC);
            return $row;

        } catch (PDOException $e) {
            // Aquí puedes clasificar el error dependiendo de la excepción
            // para presentarlo en la respuesta Json
            return -1;
        }
    }











    /**
     * Actualiza un registro de la bases de datos basado
     * en los nuevos valores relacionados con un identificador
     *
     * @param $idMeta      identificador
     * @param $titulo      nuevo titulo
     * @param $descripcion nueva descripcion
     * @param $fechaLim    nueva fecha limite de cumplimiento
     * @param $categoria   nueva categoria
     * @param $prioridad   nueva prioridad
     */
public static function update($id,$nombre,$sexo,$fecha,$busco,$idioma,$variablesliteral,$variables){
        // Creando consulta UPDATE
	$consulta="UPDATE Usuarios Set Nombre=?,Sexo=?,FechaNacimiento=?,Busco=?,Idioma=?";
	foreach($variablesliteral as $valor){
		$consulta=$consulta.",$valor=?";
	}
	$consulta=$consulta." Where _id=?";
        // Preparar la sentencia
        $cmd = Database::getInstance()->getDb()->prepare($consulta);
        // Relacionar y ejecutar la sentencia
	$valores=array_merge(array($nombre,$sexo,$fecha,$busco,$idioma),$variables,array($id));
        $cmd->execute($valores);
        return $cmd;
    }        
    

    /**
     * Insertar una nueva meta
     *
     * @param $titulo      titulo del nuevo registro
     * @param $descripcion descripción del nuevo registro
     * @param $fechaLim    fecha limite del nuevo registro
     * @param $categoria   categoria del nuevo registro
     * @param $prioridad   prioridad del nuevo registro
     * @return PDOStatement
     */
   public static function insertcargar($nombre,$sexo,$fechanacimiento,$busco)
    {
        // Sentencia INSERT
        $comando = "Insert into Usuarios (Nombre,Sexo,FechaNacimiento,Busco) Values ('$nombre',$sexo,'$fechanacimiento',$busco)";
        // Preparar la sentencia
        $pdo=Database::getInstance()->getDb();
	$sentencia =$pdo->prepare($comando);
	$resultado=$sentencia->execute();
	if($resultado){
		return $pdo->lastInsertId();	
		//return 1;
	}else{
		return 0;
	}
    }
    public static function grabarnoexiste(){
	$comando="Insert into Usuarios(nombre) values ('')";
	$pdo=Database::getInstance()->getDb();
	$sentencia =$pdo->prepare($comando);
	$resultado=$sentencia->execute();
	if(!$resultado){
		return 0;
	}
	return $pdo->lastInsertId();
    }
   public static function grabarusuario($id,$nombre,$frase,$sexo,$fechanacimiento,$busco,$datasoy,$datamegusta){
	$horoscopo=Usuarios::horoscopo($fechanacimiento);
	$comando="Update Usuarios Set Nombre='$nombre',Frase='$frase',Sexo=$sexo,FechaNacimiento='$fechanacimiento',horoscopo='$horoscopo',Busco=$busco,".$datasoy.",".$datamegusta." where _id=$id";
	/*
	if($id==0){
		$comando="Insert into Usuarios(Nombre,FechaNacimiento) Values ('$nombre','$fechanacimiento')";
	}else{
		$comando="Update Usuarios Set Nombre='$nombre',FechaNacimiento='$fechanacimiento' where _id=$id";
	}
	*/
	$pdo=Database::getInstance()->getDb();
	$sentencia =$pdo->prepare($comando);
	$resultado=$sentencia->execute();
	// SI NO HA TENIDO ÉXITO DEVUELVE 0
	if(!$resultado){
		return 0;
	}
	// SI HA TENIDO ÉXITO DEVUELVE EL LASTID SI ES UNA INSERCION O EL ID SI ES UN UPDATE
	if($id==0){
		return $pdo->lastInsertId();
	}else{
		return $id;
	}
   }
   public static function horoscopo($fechanacimiento){
	$fechanacimiento=str_replace("-","/",$fechanacimiento);
	$partes=explode("/",$fechanacimiento);
	list($año,$mes,$dia)=$partes;
	//$fecha=DateTime::createFromFormat('Y-m-d', $fechanacimiento);
	//$fecha=$fechanacimiento;
	//$dia=date("j",$fecha->getTimestamp());
	//$mes=date("n",$fecha->getTimestamp());
	switch($mes){
		case 1 :
			if($dia<20){
				$horoscopo="Capricornio";
			}else{
				$horoscopo= "Acuario";
			}				
			break;
		case 2 :
			if($dia<19){
				$horoscopo= "Acuario";
			}else{
				$horoscopo= "Piscis";
			}				
			break;	
		case 3 :
			if($dia<21){
				$horoscopo= "Piscis";
			}else{
				$horoscopo= "Aries";
			}						
			break;	
		case 4 :
			if($dia<20){
				$horoscopo= "Aries";
			}else{
				$horoscopo= "Tauro";
			}				
			break;	
		case 5 :
			if($dia<21){
				$horoscopo= "Tauro";
			}else{
				$horoscopo= "Geminis";
			}				
			break;
		case 6 :
			if($dia<21){
				$horoscopo= "Geminis";
			}else{
				$horoscopo= "Cancer";
			}				
			break;
		case 7 :
			if($dia<23){
				$horoscopo= "Cancer";
			}else{
				$horoscopo= "Leo";
			}				
			break;
		case 8 :
			if($dia<23){
				$horoscopo= "Leo";
			}else{
				$horoscopo= "Virgo";
			}				
			break;
		case 9 :
			if($dia<23){
				$horoscopo= "Virgo";
			}else{
				$horoscopo= "Libra";
			}				
			break;
		case 10 :
			if($dia<23){
				$horoscopo= "Libra";
			}else{
				$horoscopo= "Escorpio";
			}				
			break;
		case 11 :
			if($dia<22){
				$horoscopo= "Escorpio";
			}else{
				$horoscopo= "Sagitario";
			}				
			break;
		case 12 :
			if($dia<22){
				$horoscopo= "Sagitario";
			}else{
				$horoscopo= "Capricornio";
			}				
			break;		
	}
	return $horoscopo;
   }
   public static function insert()
    {
        // Sentencia INSERT
        $comando = "Insert into Usuarios (Nombre) Values ('')";
        // Preparar la sentencia
        $pdo=Database::getInstance()->getDb();
	$sentencia =$pdo->prepare($comando);
	$resultado=$sentencia->execute();
	if($resultado){
		return $pdo->lastInsertId();	
		//return 1;
	}else{
		return 0;
	}
    }

    /**
     * Eliminar el registro con el identificador especificado
     *
     * @param $idMeta identificador de la meta
     * @return bool Respuesta de la eliminación
     */
    public static function delete($idMeta)
    {
        // Sentencia DELETE
        $comando = "DELETE FROM meta WHERE idMeta=?";

        // Preparar la sentencia
        $sentencia = Database::getInstance()->getDb()->prepare($comando);

        return $sentencia->execute(array($idMeta));
    }
	public static function bbuscar($id,$genero,$elemento,$usuariosporpagina){
		$pdo=Database::getInstance()->getDb();
		$buscarcontar=$pdo->query("SELECT COUNT(*)  FROM Usuarios WHERE Sexo=".$genero);
		foreach($buscarcontar as $row){
			$totalderegistros=$row[0];
		}
		
		//$sql="Select Usuarios._id,Usuarios.Nombre,Usuarios.FechaNacimiento,if(Flases.CodigoUsuario1 =".$id.",1,0) as ParaTi,if(Flases2.CodigoUsuario2=".$id.",1,0) as ParaMi from Usuarios left join Flases on Flases.CodigoUsuario1=".$id." and Flases.CodigoUsuario2=Usuarios._id left join Flases as Flases2 on Flases2.CodigoUsuario2=".$id." and Flases2.CodigoUsuario1=Usuarios._id";
		//$sql=$sql." Where Sexo=".$genero." Limit ".$elemento.",".$usuariosporpagina;
		$sql="Select Usuarios._id,Usuarios.Nombre,Usuarios.FechaNacimiento,if(Flases.CodigoUsuario1 =$id,1,0) as ParaTi,if(Flases2.CodigoUsuario2=$id,1,0) as ParaMi from Usuarios left join Flases on Flases.CodigoUsuario1=$id and Flases.CodigoUsuario2=Usuarios._id left join Flases as Flases2 on Flases2.CodigoUsuario2=$id and Flases2.CodigoUsuario1=Usuarios._id";
		$sql=$sql." Where Sexo=$genero Limit $elemento,$usuariosporpagina";
		//$datos=$pdo->query("Select _Id,Nombre, FechaNacimiento from Usuarios Where Sexo=".$genero." Limit $elemento,$usuariosporpagina");		
		$datos=$pdo->query($sql);
		return array($totalderegistros,$datos);
	}
	public static function buscargeneral($id,$discoteca,$fechafiesta,$where,$usuariosporpagina,$elemento){
		$pdo=Database::getInstance()->getDb();
		$sql="Select Count(*) as total from Usuarios where $where";
		$datos=$pdo->query("$sql");
		$totalderegistros= $datos->fetch()["total"];
		$sql="SELECT Usuarios.*,TIMESTAMPDIFF(YEAR,FechaNacimiento,CURDATE()) AS edad,if(Flases.CodigoUsuario1=$id,1,0) as ParaTi, if(Flases2.CodigoUsuario2=$id,1,0) as ParaMi, 
			if(favoritos.CodigoUsuario1=$id,1,0) as esfavorito FROM Usuarios left join Flases on Flases.CodigoUsuario1=$id and Flases.CodigoUsuario2=Usuarios._id and Flases.CodigoDiscoteca=$discoteca and Flases.FechaFiesta='$fechafiesta' 
			left join Flases as Flases2 on Flases2.CodigoUsuario1=Usuarios._id and Flases2.CodigoUsuario2=$id and Flases2.CodigoDiscoteca=$discoteca and Flases2.FechaFiesta='$fechafiesta' 
			left join favoritos on favoritos.CodigoUsuario1=$id and favoritos.CodigoUsuario2=Usuarios._id
			where $where order by edad Limit $elemento,$usuariosporpagina";
		$datos=$pdo->query($sql);
		return array($totalderegistros,$datos->fetchAll(PDO::FETCH_ASSOC));
	}
	public static function favoritos($id,$discoteca,$fechafiesta){
		$pdo=Database::getInstance()->getDb();
		$sql="SELECT Usuarios.*,TIMESTAMPDIFF(YEAR,FechaNacimiento,CURDATE()) AS edad,if(Flases.CodigoUsuario1=$id,1,0) as ParaTi, if(Flases2.CodigoUsuario2=$id,1,0) as ParaMi, 
			if(favoritos.CodigoUsuario1=$id,1,0) as esfavorito FROM Usuarios left join Flases on Flases.CodigoUsuario1=$id and Flases.CodigoUsuario2=Usuarios._id and Flases.CodigoDiscoteca=$discoteca and Flases.FechaFiesta='$fechafiesta' 
			left join Flases as Flases2 on Flases2.CodigoUsuario1=Usuarios._id and Flases2.CodigoUsuario2=$id and Flases2.CodigoDiscoteca=$discoteca and Flases2.FechaFiesta='$fechafiesta' 
			left join favoritos on favoritos.CodigoUsuario1=$id and favoritos.CodigoUsuario2=Usuarios._id
			where favoritos.CodigoUsuario1=$id order by favoritos.id desc";
		$datos=$pdo->query($sql);
		return $datos->fetchAll(PDO::FETCH_ASSOC);
	}
	 public static function flasesestados($id,$discoteca,$fechafiesta,$tipo) {
		$pdo=Database::getInstance()->getDb();
		if($tipo==1){
			$sql="SELECT Usuarios.*,TIMESTAMPDIFF(YEAR,FechaNacimiento,CURDATE()) AS edad,if(Flases.CodigoUsuario1=$id,1,0) as ParaTi, if(Flases2.CodigoUsuario2=$id,1,0) as ParaMi, 
				if(favoritos.CodigoUsuario1=$id,1,0) as esfavorito FROM Usuarios left join Flases on Flases.CodigoUsuario1=$id and Flases.CodigoUsuario2=Usuarios._id and Flases.CodigoDiscoteca=$discoteca and Flases.FechaFiesta='$fechafiesta' 
				left join Flases as Flases2 on Flases2.CodigoUsuario1=Usuarios._id and Flases2.CodigoUsuario2=$id and Flases2.CodigoDiscoteca=$discoteca and Flases2.FechaFiesta='$fechafiesta' 
				left join favoritos on favoritos.CodigoUsuario1=$id and favoritos.CodigoUsuario2=Usuarios._id
				where Flases.CodigoUsuario1=$id order by edad";
		}elseif($tipo==2){
			$sql="SELECT Usuarios.*,TIMESTAMPDIFF(YEAR,FechaNacimiento,CURDATE()) AS edad,if(Flases.CodigoUsuario1=$id,1,0) as ParaTi, if(Flases2.CodigoUsuario2=$id,1,0) as ParaMi, 
				if(favoritos.CodigoUsuario1=$id,1,0) as esfavorito FROM Usuarios left join Flases on Flases.CodigoUsuario1=$id and Flases.CodigoUsuario2=Usuarios._id and Flases.CodigoDiscoteca=$discoteca and Flases.FechaFiesta='$fechafiesta' 
				left join Flases as Flases2 on Flases2.CodigoUsuario1=Usuarios._id and Flases2.CodigoUsuario2=$id and Flases2.CodigoDiscoteca=$discoteca and Flases2.FechaFiesta='$fechafiesta' 
				left join favoritos on favoritos.CodigoUsuario1=$id and favoritos.CodigoUsuario2=Usuarios._id
				where Flases2.CodigoUsuario2=$id order by edad";
		}elseif($tipo==3){
			$sql="Select Usuarios.*,TIMESTAMPDIFF(YEAR,FechaNacimiento,CURDATE()) AS edad,1 as ParaTi,1 as ParaMi,if(favoritos.CodigoUsuario1=$id,1,0) as esfavorito from Usuarios 
				left join favoritos on favoritos.CodigoUsuario1=$id and favoritos.CodigoUsuario2=Usuarios._id left join
				(Select Consulta2.CodigoUsuario1 as persona from (Select Flases.* from Flases where CodigoUsuario1=$id and CodigoDiscoteca=$discoteca and FechaFiesta='$fechafiesta') as Consulta1 
				LEFT JOIN (Select * from Flases Where CodigoUsuario2=$id and CodigoDiscoteca=$discoteca and FechaFiesta='$fechafiesta') as Consulta2 
				on Consulta1.CodigoUsuario2=Consulta2.CodigoUsuario1 where Consulta2.CodigoUsuario2=$id)as consulta on Usuarios._id=consulta.persona where consulta.persona is not null";
		}		
		$datos=$pdo->query($sql);
		return $datos->fetchAll(PDO::FETCH_ASSOC);
		/*
		Select Usuarios.* from Usuarios left join
(Select Consulta2.CodigoUsuario1 as persona from (Select Flases.* from Flases where CodigoUsuario1=337 and CodigoDiscoteca=1 and FechaFiesta='28092018') as Consulta1 LEFT JOIN (Select * from Flases Where CodigoUsuario2=337 and CodigoDiscoteca=1 and FechaFiesta='28092018') as Consulta2 on Consulta1.CodigoUsuario2=Consulta2.CodigoUsuario1 where Consulta2.CodigoUsuario2=337)as resultado on Usuarios._id=resultado.persona where Usuarios._id=resultado.persona

Select Usuarios.* from Usuarios left join
(Select Consulta2.CodigoUsuario1 as persona from (Select Flases.* from Flases where CodigoUsuario1=337 and CodigoDiscoteca=1 and FechaFiesta='28092018') as Consulta1 LEFT JOIN (Select * from Flases Where CodigoUsuario2=337 and CodigoDiscoteca=1 and FechaFiesta='28092018') as Consulta2 on Consulta1.CodigoUsuario2=Consulta2.CodigoUsuario1 where Consulta2.CodigoUsuario2=337)as consulta on Usuarios._id=consulta.persona where consulta.persona is not null
		*/
	}
	public static function buscar($id,$busco,$sexo,$elemento,$usuariosporpagina,$tipo,$where2="",$discoteca,$FechaFiesta){
		$otrowhere="";
		if($busco==1){
			$otrowhere=" and Sexo=1";
			$where=" Where Sexo=1";
		}elseif($busco==2){
			$otrowhere=" and Sexo=2";
			$where=" Where Sexo=2";
		}elseif($busco==3){
			$otrowhere=" and (Sexo=1 or Sexo=2)";
			$where=" Where (Sexo=1 or Sexo=2)";
		}
		$otrowhere=$otrowhere." and (Busco=$sexo or Busco=3)";
		$where=$where." and (Busco=$sexo or Busco=3)";
		if($sexo==0 | $busco==0){
			$where ="";
		}
		$where=$where." and CodigoDiscoteca=$discoteca and FechaFiesta='$FechaFiesta'";
		$pdo=Database::getInstance()->getDb();
		if($tipo=="general" | $tipo=="filtrar"){
			if($where2!=""){
				$where="$where $where2";
			}
			$buscarcontar=$pdo->query("SELECT COUNT(*)  FROM Fiestas left join Usuarios on Fiestas.CodigoUsuario=Usuarios._id where CodigoDiscoteca=$discoteca and FechaFiesta='$FechaFiesta' $otrowhere $where2 and dentro=true and TIMESTAMPDIFF(MINUTE , FechaControl, NOW() )<600 ");
		}elseif($tipo=="enviados"){
			$buscarcontar=$pdo->query("SELECT COUNT(*)  FROM Flases Where CodigoUsuario1=$id and CodigoDiscoteca=$discoteca and FechaFiesta='$FechaFiesta'");
		}elseif($tipo=="recibidos"){
			$buscarcontar=$pdo->query("SELECT COUNT(*)  FROM Flases WHERE CodigoUsuario2=$id and CodigoDiscoteca=$discoteca and FechaFiesta='$FechaFiesta'");
		}elseif($tipo=="pops"){
			$sql="
			SELECT Count(*) from (Select * from Flases where CodigoUsuario1=$id and CodigoDiscoteca=$discoteca and FechaFiesta='$FechaFiesta') as Consulta1 
			LEFT JOIN (Select * from Flases Where CodigoUsuario2=$id and CodigoDiscoteca=$discoteca and FechaFiesta='$FechaFiesta') as Consulta2 
			on Consulta1.CodigoUsuario2=Consulta2.CodigoUsuario1 
			where Consulta1.CodigoUsuario1=Consulta2.CodigoUsuario2
			";
			$buscarcontar=$pdo->query($sql);
		}elseif($tipo=="favoritos"){
			$buscarcontar=$pdo->query("SELECT COUNT(*)  FROM favoritos where CodigoUsuario1=$id");
		}
		foreach($buscarcontar as $row){
			$totalderegistros=$row[0];
		}
		if($tipo=="general" | $tipo =="filtrar"){
			$sql="
			Select Fiestas.*,Usuarios.*,
			if(Flases.CodigoUsuario1 =$id,1,0) as ParaTi,
			if(Flases2.CodigoUsuario2=$id,1,0) as ParaMi,
			favoritos.CodigoUsuario1 as idFavoritos,
			if(TIMESTAMPDIFF(MINUTE , Fiestas.FechaControl, NOW())<600 and Fiestas.Dentro=1,true,false) as Tiempo
			from Fiestas left join Usuarios on Fiestas.CodigoUsuario=Usuarios._id 
			left join Flases on Usuarios._id=Flases.CodigoUsuario2 and Flases.CodigoUsuario1=$id and Flases.CodigoDiscoteca=$discoteca and Flases.FechaFiesta='$FechaFiesta'
			left join Flases as Flases2 on Usuarios._id=Flases2.CodigoUsuario1 and Flases2.CodigoUsuario2=$id and Flases2.CodigoDiscoteca=$discoteca and Flases2.FechaFiesta='$FechaFiesta'
			left join favoritos on Usuarios._id=favoritos.CodigoUsuario2 and favoritos.CodigoUsuario1=$id
			where Fiestas.CodigoDiscoteca=$discoteca and Fiestas.FechaFiesta='$FechaFiesta' $otrowhere $where2 and dentro=true and TIMESTAMPDIFF(MINUTE , FechaControl, NOW() )<600  order by Fiestas.FechaEntrada Desc Limit $elemento,$usuariosporpagina
			";
		}elseif($tipo=="enviados"){
			$sql="
			Select Fiestas.*,Usuarios.*,
			if(Flases.CodigoUsuario1 =$id,1,0) as ParaTi,
			if(Flases2.CodigoUsuario2=$id,1,0) as ParaMi,
			favoritos.CodigoUsuario1 as idFavoritos,
			if(TIMESTAMPDIFF(MINUTE , Fiestas.FechaControl, NOW())<600 and Fiestas.Dentro=1,true,false) as Tiempo
			from Fiestas left join Usuarios on Fiestas.CodigoUsuario=Usuarios._id 
			left join Flases on Usuarios._id=Flases.CodigoUsuario2 and Flases.CodigoUsuario1=$id and Flases.CodigoDiscoteca=$discoteca and Flases.FechaFiesta='$FechaFiesta'
			left join Flases as Flases2 on Usuarios._id=Flases2.CodigoUsuario1 and Flases2.CodigoUsuario2=$id and Flases2.CodigoDiscoteca=$discoteca and Flases2.FechaFiesta='$FechaFiesta'
			left join favoritos on Usuarios._id=favoritos.CodigoUsuario2 and favoritos.CodigoUsuario1=$id
			where Fiestas.CodigoDiscoteca=$discoteca and Fiestas.FechaFiesta='$FechaFiesta' and Flases.CodigoUsuario1=$id order by Flases.marca desc Limit $elemento,$usuariosporpagina
			";
		}elseif($tipo=="recibidos"){
			$sql="
			Select Fiestas.*,Usuarios.*,
			if(Flases.CodigoUsuario1 =$id,1,0) as ParaTi,
			if(Flases2.CodigoUsuario2=$id,1,0) as ParaMi,
			favoritos.CodigoUsuario1 as idFavoritos
			from Fiestas left join Usuarios on Fiestas.CodigoUsuario=Usuarios._id 
			left join Flases on Usuarios._id=Flases.CodigoUsuario2 and Flases.CodigoUsuario1=$id and Flases.CodigoDiscoteca=$discoteca and Flases.FechaFiesta='$FechaFiesta'
			left join Flases as Flases2 on Usuarios._id=Flases2.CodigoUsuario1 and Flases2.CodigoUsuario2=$id and Flases2.CodigoDiscoteca=$discoteca and Flases2.FechaFiesta='$FechaFiesta'
			left join favoritos on Usuarios._id=favoritos.CodigoUsuario2 and favoritos.CodigoUsuario1=$id
			where Fiestas.CodigoDiscoteca=$discoteca and Fiestas.FechaFiesta='$FechaFiesta' and Flases2.CodigoUsuario2=$id order by Flases.marca desc Limit $elemento,$usuariosporpagina
			";
		}elseif($tipo=="pops"){
			$sql="
			Select Fiestas.*,Usuarios.*,
			if(Flases.CodigoUsuario1 =$id,1,0) as ParaTi,
			if(Flases2.CodigoUsuario2=$id,1,0) as ParaMi,
			favoritos.CodigoUsuario1 as idFavoritos
			from Fiestas left join Usuarios on Fiestas.CodigoUsuario=Usuarios._id 
			left join Flases on Usuarios._id=Flases.CodigoUsuario2 and Flases.CodigoUsuario1=$id and Flases.CodigoDiscoteca=$discoteca and Flases.FechaFiesta='$FechaFiesta'
			left join Flases as Flases2 on Usuarios._id=Flases2.CodigoUsuario1 and Flases2.CodigoUsuario2=$id and Flases2.CodigoDiscoteca=$discoteca and Flases2.FechaFiesta='$FechaFiesta'
			left join favoritos on Usuarios._id=favoritos.CodigoUsuario2 and favoritos.CodigoUsuario1=$id
			where Fiestas.CodigoDiscoteca=$discoteca and Fiestas.FechaFiesta='$FechaFiesta' and Flases.CodigoUsuario1=$id and Flases2.CodigoUsuario2=$id order by Flases.marca desc Limit $elemento,$usuariosporpagina
			";
		}elseif($tipo=="favoritos"){
			$sql="
			Select Usuarios.*,
			if(Flases.CodigoUsuario1 =$id,1,0) as ParaTi,
			if(Flases2.CodigoUsuario2=$id,1,0) as ParaMi,
			favoritos.CodigoUsuario1 as idFavoritos,
			if(TIMESTAMPDIFF(MINUTE , Fiestas.FechaControl, NOW())<600 and Fiestas.Dentro=1,true,false) as Tiempo
			from favoritos left join Usuarios on favoritos.CodigoUsuario2=Usuarios._id
			left join Flases on Usuarios._id=Flases.CodigoUsuario2 and Flases.CodigoUsuario1=$id and Flases.CodigoDiscoteca=$discoteca and Flases.FechaFiesta='$FechaFiesta'
			left join Flases as Flases2 on Usuarios._id=Flases2.CodigoUsuario1 and Flases2.CodigoUsuario2=$id and Flases2.CodigoDiscoteca=$discoteca and Flases2.FechaFiesta='$FechaFiesta'
			left join Fiestas on (favoritos.CodigoUsuario2=Fiestas.CodigoUsuario and Fiestas.FechaFiesta='$FechaFiesta')
			where favoritos.CodigoUsuario1=$id order by Usuarios.Nombre Limit $elemento,$usuariosporpagina
			";
		}
		$datos=$pdo->query($sql);
		return array($totalderegistros,$datos);
	}

	public static function grabarfiesta($usuario,$discoteca,$fecha){
		$pdo=Database::getInstance()->getDb();
		$comando="SELECT id  FROM Fiestas WHERE CodigoUsuario=? and CodigoDiscoteca=? and FechaFiesta=?";
		$sentencia =$pdo->prepare($comando);
		$sentencia->execute(array($usuario,$discoteca,$fecha));
		$datos=$sentencia->fetchAll(PDO::FETCH_ASSOC);
		foreach($datos as $row){
			$devolviendo=$row["id"];
		}
		if($devolviendo<1){
			$comando="Insert into Fiestas (CodigoUsuario,CodigoDiscoteca,FechaFiesta) Values (?,?,?)";
			$sentencia =$pdo->prepare($comando);
			$resultado=$sentencia->execute(array($usuario,$discoteca,$fecha));
			if($resultado){
				return $pdo->lastInsertId();	
				//return 1;
			}else{
				return 0;
			}
		}else{
			return $devolviendo;
		}
	}
	public static function enviarflas($id,$id2,$discoteca,$fechafiesta){
		$pdo=Database::getInstance()->getDb();
		$comando="Insert into Flases (CodigoUsuario1,CodigoUsuario2,CodigoDiscoteca,FechaFiesta) VALUES (?,?,?,?)";
		$sentencia=$pdo->prepare($comando);
		$resultado=$sentencia->execute(array($id,$id2,$discoteca,$fechafiesta));
		return $resultado;
	}
	public static function flasesenviadosyrecibidos($id,$discoteca,$FechaFiesta){
		$pdo=Database::getInstance()->getDb();
		$comando="Select Count(id) as enviados from Flases where CodigoUsuario1=$id and CodigoDiscoteca=$discoteca and FechaFiesta='$FechaFiesta'";
		$resultado=$pdo->query($comando);
		foreach($resultado as $row){
			$enviados=$row[0];
		}
		$comando="Select Count(id) as recibidos from Flases where CodigoUsuario2=$id and CodigoDiscoteca=$discoteca and FechaFiesta='$FechaFiesta'";
		$resultado=$pdo->query($comando);
		foreach($resultado as $row){
			$recibidos=$row[0];
		}
		$comando="Select Count(Flases.id) as pops from Flases left join Flases as Flases2 on Flases2.CodigoUsuario1=Flases.CodigoUsuario2 where Flases.CodigoUsuario1=$id and Flases2.CodigoUsuario2=$id and Flases.CodigoDiscoteca=$discoteca and Flases.FechaFiesta='$FechaFiesta' and Flases2.CodigoDiscoteca=$discoteca and Flases2.FechaFiesta='$FechaFiesta'";
		$resultado=$pdo->query($comando);
		foreach($resultado as $row){
			$pops=$row[0];
		}
		return array($enviados,$recibidos,$pops);
	}
	public static function ComprobarRecibidos($id){
		$pdo=Database::getInstance()->getDb();
		//$comando="Select Count(id) as recibidos from Flases where CodigoUsuario2=$id";
		$comando="Select CodigoUsuario1 from Flases where CodigoUsuario2=$id";
		$resultado=$pdo->query($comando);
		return $resultado;
	}
	public static function MensajesSala($CodigoDiscoteca,$Fecha){
		$pdo=Database::getInstance()->getDb();
		$comando="Select * from MensajesSala Where CodigoDiscoteca=$CodigoDiscoteca and Fecha='$Fecha' Order by marca Desc Limit 0,1";
		$resultado=$pdo->query($comando);
		foreach($resultado as $row){
			$mensaje=$row["Mensaje"];
			$tipo=$row["TipoMensaje"];
		}
		return array($mensaje,$tipo);
	}
	public static function TopFlases($sexo,$busco,$CodigoDiscoteca,$FechaFiesta){
		if($busco==3){
			$where=" where (Sexo=1 or Sexo=2)";
		}else{
			$where=" where Sexo=$busco";
		}
		$where=$where." and (Busco=$sexo or Busco=3)";
		//$where=$where." and CodigoDiscoteca=$CodigoDiscoteca and FechaFiesta='$FechaFiesta'";
		// VERSION FINAL SUSTITUIR POR EL ANTERIOR
		$where=$where." and CodigoDiscoteca=$CodigoDiscoteca";
		$consulta = "Select CodigoUsuario2,Count(CodigoUsuario2) as Total from Flases left join Usuarios on Usuarios._id=Flases.CodigoUsuario2 $where group by CodigoUsuario2 order by Total desc Limit 0,20";
		// EN EL CASO DE VALORES CERO
		If($sexo==0 | $busco==0){
			$consulta = "Select CodigoUsuario2,Count(CodigoUsuario2) as Total from Flases left join Usuarios on Usuarios._id=Flases.CodigoUsuario2 group by CodigoUsuario2 order by Total desc Limit 0,20";
		}
		//$conulta="Select CodigoUsuario2,Count(CodigoUsuario2) as Total  from Flases  left join Usuarios on Usuarios._id=Flases.CodigoUsuario2 where Sexo=2 and Busco=1  group by CodigoUsuario2 order by Total desc Limit 0,20";
		try {
			$comando = Database::getInstance()->getDb()->prepare($consulta);
			$comando->execute();
			return $comando->fetchAll(PDO::FETCH_ASSOC);
		} catch (PDOException $e) {
			return false;
		}
	}
	public static function AñadirFavoritos($id,$id2){
		$pdo=Database::getInstance()->getDb();
		$comando="Insert into favoritos (CodigoUsuario1,CodigoUsuario2) Values ($id,$id2)";
		$resultado=$pdo->query($comando);
		return ($resultado ? 1:0);
	}
	public static function QuitarFavoritos($id,$id2){
		$pdo=Database::getInstance()->getDb();
		$comando="Delete from favoritos where CodigoUsuario1=$id and CodigoUsuario2=$id2";
		$resultado=$pdo->query($comando);
		return ($resultado ? 1:0);
	}
	public static function ObtenerMensajes($id,$id2,$discoteca,$fechafiesta){
		$pdo = Database::getInstance()->getDb();
		$consulta="Select * from Mensajes where ((CodigoUsuario1=$id and CodigoUsuario2=$id2) or (CodigoUsuario1=$id2 and CodigoUsuario2=$id)) and
		               Discoteca=$discoteca and FechaFiesta='$fechafiesta' order by CodigoMensaje";
		$resultado=$pdo->query($consulta);
		$datos=$resultado->fetchAll(PDO::FETCH_ASSOC);
		return $datos;
	}
	public static function VerMensajes($id,$id2,$discoteca,$FechaFiesta){
		 // Preparar sentencia
		$pdo = Database::getInstance()->getDb();
		$consulta="Select if(TIMESTAMPDIFF(MINUTE , Fiestas.FechaControl, NOW())<600 and Fiestas.Dentro=1,true,false) as Tiempo
				 from Fiestas Where CodigoUsuario=$id2 and CodigoDiscoteca=$discoteca and FechaFiesta='$FechaFiesta'";
		$resultado=$pdo->query($consulta);
		$datos=$resultado->fetchAll(PDO::FETCH_ASSOC);
		foreach($datos as $row){
			$dentro=$row["Tiempo"];
		}
		$consulta="Select * from Mensajes where (CodigoUsuario1=$id and CodigoUsuario2=$id2) or (CodigoUsuario1=$id2 and CodigoUsuario2=$id) 
			         and 	Discoteca=$discoteca and FechaFiesta='$FechaFiesta' 
				 order by Fecha"; 
		$comando = Database::getInstance()->getDb()->prepare($consulta);
		$comando->execute();
		$datos=$comando->fetchAll(PDO::FETCH_ASSOC);
		return array($datos,$dentro);
	}
	public static function VerMensajesGeneral($id,$discoteca,$fechafiesta){
		$pdo=Database::getInstance()->getDb();
		$consulta="Select Consulta.*,Usuarios.Nombre,if(favoritos.CodigoUsuario1=337,1,0) as esfavorito from (Select if(CodigoUsuario1=$id,CodigoUsuario2,CodigoUsuario1) as Persona,Mensajes.* from 
				Mensajes Where (CodigoUsuario2=$id or CodigoUsuario1=$id) and Discoteca=$discoteca and FechaFiesta='$fechafiesta' order by Fecha desc) as Consulta 
				left join Usuarios on Consulta.Persona=Usuarios._id left join favoritos  on favoritos.CodigoUsuario1=$id and favoritos.CodigoUsuario2=Persona group by Persona order by Fecha desc";
		$comando = Database::getInstance()->getDb()->prepare($consulta);
		$comando->execute();
		return $comando->fetchAll(PDO::FETCH_ASSOC);
	}
	public static function VVerMensajesGeneral($id,$discoteca,$FechaFiesta){
		$pdo=Database::getInstance()->getDb();
		//$consulta="Select Consulta.*,Usuarios.Nombre from (Select if(CodigoUsuario1=$id,CodigoUsuario2,CodigoUsuario1) as Persona,Mensajes.* from Mensajes Where CodigoUsuario2=$id or CodigoUsuario1=$id order by Fecha desc) as Consulta left join Usuarios on Consulta.Persona=Usuarios._id group by Persona order by Fecha desc";
		$consulta="
		Select Consulta.*,Usuarios.Nombre,if(TIMESTAMPDIFF(MINUTE , Fiestas.FechaControl, NOW())<600 and Fiestas.Dentro=1,true,false) as Tiempo from (Select if(CodigoUsuario1=$id,CodigoUsuario2,CodigoUsuario1) as Persona,Mensajes.* from Mensajes 
		Where (CodigoUsuario2=$id or CodigoUsuario1=$id) and Discoteca=$discoteca and FechaFiesta='$FechaFiesta' order by Fecha desc) as Consulta left join Usuarios on Consulta.Persona=Usuarios._id 
		left join Fiestas on Fiestas.CodigoUsuario=Consulta.Persona
		group by Persona order by Fecha desc";
		$comando = Database::getInstance()->getDb()->prepare($consulta);
		$comando->execute();
		return $comando->fetchAll(PDO::FETCH_ASSOC);
	}
	public static function GrabarMensaje($id,$id2,$mensaje,$hora,$discoteca,$fechafiesta){
		$pdo=Database::getInstance()->getDb();
		//$comando="Insert into Mensajes (CodigoUsuario1,CodigoUsuario2,Texto,hora,Discoteca,FechaFiesta) Values (?,?,?,?,?,?)";
		$comando="Insert into Mensajes(CodigoUsuario1,CodigoUsuario2,Texto,hora,Discoteca,FechaFiesta) Values ($id,$id2,'$mensaje','$hora',$discoteca,'$fechafiesta')";
		$sentencia =$pdo->prepare($comando);
		$resultado=$sentencia->execute();
		return $resultado;
	}
	public static function GrabarDentro($id,$discoteca,$FechaFiesta,$dentro){
		$pdo=Database::getInstance()->getDb();
		$comando="Update Fiestas Set Dentro=$dentro Where CodigoUsuario=$id and CodigoDiscoteca=$discoteca and FechaFiesta='$FechaFiesta'";
		$resultado=$pdo->query($comando);
	}
	public static function Datos($discoteca,$FechaFiesta){
		$consulta="Select Count(*) as Total,Usuarios.Sexo, TIMESTAMPDIFF(YEAR, Usuarios.FechaNacimiento, NOW()) as Edad from Fiestas left join Usuarios on Fiestas.CodigoUsuario=Usuarios._id 
			       where CodigoDiscoteca=$discoteca and FechaFiesta='$FechaFiesta' 
			       and Dentro=true
			       and TIMESTAMPDIFF(MINUTE , FechaControl, NOW() )<600 
                               group by Edad,Usuarios.Sexo order by Edad";
		$comando = Database::getInstance()->getDb()->prepare($consulta);
		$comando->execute();
		return $comando->fetchAll(PDO::FETCH_ASSOC);
	}
	public static function VerMensajesSala($id,$discoteca,$FechaFiesta){
		$consulta="Select * from MensajesSala where CodigoDiscoteca=$discoteca and FechaFiesta='$FechaFiesta'";
		$comando = Database::getInstance()->getDb()->prepare($consulta);
		$comando->execute();
		return $comando->fetchAll(PDO::FETCH_ASSOC);
	}
	/*
	public static function SiEsFavorito($id,$ild2){
		$pdo=Database::getInstance()->getDb();
		$comando="Select id from favoritos where CodigoUsuario1=$id and CodigoUsuario2=$id2";
		$resultado=$pdo->query($comando);
		return ($resultado.rowCount()>0 ? 1:0);
	}
	*/
}
?>