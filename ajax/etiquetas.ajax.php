
<?php

require_once "../controllers/etiquetas.controller.php";
require_once "../models/etiquetas.models.php";

class AjaxEtiquetas{

    // /*=====================================*
    // TRAER NOMBRE DE LA ETIQUETA
    // =======================================*/
    // public $idEtiqueta;
    
    // public function ajaxGetEtiquetaProducto(){

    //     $item = "id_etiqueta";
    //     $valor = $this->idEtiqueta;
    //     $orden = "id";
    //     $respuesta = ControladorProductos::ctrMostrarProductos($item, $valor, $orden);
    //     echo json_encode($respuesta);
    // }

    /*==================================
    EDITAR ETIQUETA
    ====================================*/
    public $idEtiqueta;

    public function ajaxEditaretiqueta(){
        $item = "id";
        $valor = $this->idEtiqueta;
        $respuesta = ControladorEtiquetas::ctrMostrarEtiquetas($item, $valor);
        echo json_encode($respuesta);
    }

}
 /*==================================
    EDITAR ETIQUETA
====================================*/

if(isset($_POST["idEtiqueta"])){
    $etiqueta = new AjaxEtiquetas();
    $etiqueta -> idEtiqueta = $_POST["idEtiqueta"];
    $etiqueta -> ajaxEditarEtiqueta();
   
}