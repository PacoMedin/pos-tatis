<?php

require_once "../controllers/productos.controller.php";
require_once "../models/productos.models.php";


class AjaxProductos{

    /*=====================================*
    GENERAR CODIGO A PARTIR DE ID CATEGORIA
    =======================================*/
    public $idCategoria;
    
    public function ajaxCrearCodigoProducto(){

        $item = "id_categoria";
        $valor = $this->idCategoria;
        $orden = "id";
        $respuesta = ControladorProductos::ctrMostrarProductos($item, $valor, $orden);
        echo json_encode($respuesta);
    }

  
    /*=====================================*
    EDITAR PRODUCTO
    =======================================*/
    public $idProducto;
    public $traerProductos;
    public $nombreProducto;

    public function ajaxEditarProducto()
    {
        if($this->traerProductos == "ok"){
            $item = null;
            $valor = null;
            $orden = "id";
            $respuesta = ControladorProductos::ctrMostrarProductos($item, $valor, $orden);
    
            echo json_encode($respuesta);
        }else if($this->nombreProducto != ""){

            $item = "descripcion";
            $valor = $this -> nombreProducto;
            $orden = "id";
            $respuesta = ControladorProductos::ctrMostrarProductos($item, $valor, $orden);
    
            echo json_encode($respuesta);
        }else{
            $item = "id";
            $valor = $this -> idProducto;
            $orden = "id";
            $respuesta = ControladorProductos::ctrMostrarProductos($item, $valor, $orden);
    
            echo json_encode($respuesta);
        }
      
    }

}
   /*=====================================*
    GENERAR CODIGO A PARTIR DE ID CATEGORIA
    =======================================*/
if(isset($_POST["idCategoria"])){
    $codigoProducto = new AjaxProductos();
    $codigoProducto -> idCategoria = $_POST["idCategoria"];
    $codigoProducto -> ajaxCrearCodigoProducto();
}
   /*=====================================*
    EDITAR PRODUCTO
    =======================================*/
if(isset($_POST["idProducto"])){
    $editarProducto = new AjaxProductos();
    $editarProducto -> idProducto = $_POST["idProducto"];
    $editarProducto -> ajaxEditarProducto();
}
   /*=====================================*
    TRAER PRODUCTO
    =======================================*/
if(isset($_POST["traerProductos"])){
    $traerProductos = new AjaxProductos();
    $traerProductos -> traerProductos = $_POST["traerProductos"];
    $traerProductos -> ajaxEditarProducto();
}
   /*=====================================*
    TRAER PRODUCTO
    =======================================*/
if(isset($_POST["nombreProducto"])){
    $nombreProductos = new AjaxProductos();
    $nombreProductos -> nombreProducto = $_POST["nombreProducto"];
    $nombreProductos -> ajaxEditarProducto();
}