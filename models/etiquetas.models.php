<?php

require_once  "conexion.php";

class ModeloEtiquetas
{

    /*========================================
    CREAR ETIQUETA
    ==========================================*/
    static public function mdlIngresarEtiqueta($tabla, $datos){

        $stmt = Conexion::conectar()->prepare("INSERT INTO $tabla(etiqueta) VALUES (:etiqueta)");
        $stmt->bindParam(":etiqueta", $datos, PDO::PARAM_STR);

        if ($stmt->execute()) {

            return "ok";

        } else {

            return "error";
        }

        $stmt = null;
    }

    /*========================================
    MOSTRAR ETIQUETA
    ==========================================*/
    static public function mdlMostrarEtiquetas($tabla, $item, $valor){
       /**Si el item viene diferente a nulo es por que se seleccionara un item en especifico */
        if($item != null){
            
            $stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla WHERE $item = :$item");
            $stmt -> bindParam(":".$item, $valor, PDO::PARAM_STR);
            $stmt -> execute();
            return $stmt -> fetch();
        }else{
            $stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla");
            $stmt -> execute();
            return $stmt-> fetchAll();
        }
        $stmt = null;
    }

     /*========================================
    MOSTRAR ETIQUETA BUSQUEDA POR NOMBRE
    ==========================================*/
    static public function mdlMostrarEtiquetasNombre($tabla, $item, $valor){
        /**Si el item viene diferente a nulo es por que se seleccionara un item en especifico */
         if($item != null){
             
             $stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla WHERE $item = :$item");
             $stmt -> bindParam(":".$item, $valor, PDO::PARAM_STR);
             $stmt -> execute();
             return $stmt -> fetch();
         }else{
             $stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla");
             $stmt -> execute();
             return $stmt-> fetchAll();
         }
         $stmt = null;
     }

    
    /*========================================
    EDITAR ETIQUETA
    ==========================================*/
    static public function mdlEditarEtiquetas($tabla, $datos){

        $stmt = Conexion::conectar()->prepare("UPDATE $tabla SET etiqueta = :etiqueta WHERE id = :id");
        $stmt->bindParam(":etiqueta", $datos["etiqueta"], PDO::PARAM_STR);
        $stmt->bindParam(":id", $datos["id"], PDO::PARAM_INT);

        if ($stmt->execute()) {

            return "ok";

        } else {

            return "error";
        }

        $stmt = null;

    }

    
    /*========================================
    BORRAR ETIQUETA
    ==========================================*/

    public function mdlBorrarEtiquetas ($tabla, $datos)
    {
        $stmt = Conexion::conectar()->prepare("DELETE FROM $tabla WHERE id = :id");
        $stmt -> bindParam(":id", $datos, PDO::PARAM_INT);

        if ($stmt->execute()) {

            return "ok";

        } else {

            return "error";
        }

        $stmt = null;
    }
}