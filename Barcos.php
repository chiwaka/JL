<?php

/**
 * Representa el la estructura de las metas
 * almacenadas en la base de datos
 */
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

require_once 'Database.php';

class Barcos
{
    function __construct()
    {
    }

    /**
     * Retorna en la fila especificada de la tabla 'meta'
     *
     * @param $idMeta Identificador del registro
     * @return array Datos del registro
     */
    public static function getAll()
    {
        $consulta = "SELECT * FROM Barcos";
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


	public static function insertarbarco($nombre,$año,$motores){
		$pdo=Database::getInstance()->getDb();
		$comando="Insert into Barcos (Nombre,Año,Motores) Values (?,?,?)";
		$sentencia =$pdo->prepare($comando);
		$resultado=$sentencia->execute(array($nombre,$año,$motores));
		if($resultado){
			return $pdo->lastInsertId();	
		}else{
			return 0;
		}
	}



    /**
     * Obtiene los campos de una meta con un identificador
     * determinado
     *
     * @param $idMeta Identificador de la meta
     * @return mixed
     */
    public static function getById($id)
    {
        $consulta="SELECT * from Barcos Where _id=?";
        try {
            // Preparar sentencia
            $comando = Database::getInstance()->getDb()->prepare($consulta);
            // Ejecutar sentencia preparada
            $comando->execute(array($id));
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
	
	public static function enviarflas($usuario,$usuario2,$ddiscoteca,$hhoy){
		$pdo=Database::getInstance()->getDb();
		$comando="Insert into Flases (CodigoUsuario1,CodigoUsuario2,CodigoDiscoteca,FechaFiesta) VALUES (?,?,?,?)";
		$sentencia=$pdo->prepare($comando);
		$resultado=$sentencia->execute(array($usuario,$usuario2,$ddiscoteca,$hhoy));
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
		$where=$where." and CodigoDiscoteca=$CodigoDiscoteca and FechaFiesta='$FechaFiesta'";
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
	public static function VerMensajesGeneral($id,$discoteca,$FechaFiesta){
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
	public static function GrabarMensaje($id,$id2,$mensaje,$discoteca,$FechaFiesta){
		$pdo=Database::getInstance()->getDb();
		$comando="Insert into Mensajes (CodigoUsuario1,CodigoUsuario2,Texto,Discoteca,FechaFiesta) Values (?,?,?)";
		$sentencia =$pdo->prepare($comando);
		$resultado=$sentencia->execute(array($id,$id2,$mensaje,$discoteca,$FechaFiesta));
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