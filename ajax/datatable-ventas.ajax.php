<?php


require_once "../controllers/productos.controller.php";
require_once "../models/productos.models.php";

require_once "../controllers/etiquetas.controller.php";
require_once "../models/etiquetas.models.php";

class TablaProductosVentas
{

    /*================================
    MOSTRAR LA TABLA DE PRODUCTOS
    ==================================*/

    public function mostrarTablaProductosVentas()
    {
        $item = null;
        $valor = null;
        $orden = "id";

        $productos = ControladorProductos::ctrMostrarProductos($item, $valor, $orden);

        if (count($productos) == 0) {

            echo '{"data": []}';

            return;
        }
     

        $datosJson = '{
            "data": [';

        for ($i = 0; $i < count($productos); $i++) {
            /*================================
             TRAEMOS LA IMAGEN
            ==================================*/

            $imagen  = "<img src='" . $productos[$i]["imagen"] . "' width='40px'>";

            /*================================
            TRAEMOS LA ETIQUETA
            ==================================*/
            $item2 = "id";
            $valor2 = $productos[$i]["id_etiqueta"];
            $etiquetas = ControladorEtiquetas::ctrMostrarEtiquetas($item2, $valor2);


            /*================================
            STOCK
            ==================================*/
            if ($productos[$i]["stock"] <= 10) {

                if ($productos[$i]["unidad_medida"] == "pz") {
                    $stock = "<button class='btn btn-danger  btn-block'>" . $productos[$i]["stock"] .$productos[$i]["unidad_medida"]. "</button>";
                } else {
                    $stock = "<button class='btn btn-danger btn-block'>" . number_format($productos[$i]["stock"], 2).$productos[$i]["unidad_medida"]. "</button>";
                }

            } else if ($productos[$i]["stock"] > 10 && $productos[$i]["stock"] <= 15) {

                if ($productos[$i]["unidad_medida"] == "pz") {
                    $stock = "<button class='btn btn-warning btn-block'>" . $productos[$i]["stock"].$productos[$i]["unidad_medida"] . "</button>";
                } else {
                    $stock = "<button class='btn btn-warning btn-block'>" . number_format($productos[$i]["stock"], 2).$productos[$i]["unidad_medida"] . "</button>";
                }

            } else {

                if ($productos[$i]["unidad_medida"] == "pz") {

                    $stock = "<button class='btn btn-success btn-block'>".$productos[$i]["stock"].$productos[$i]["unidad_medida"]. "</button>";
                } else {
                    $stock = "<button class='btn btn-success btn-block'>" . number_format($productos[$i]["stock"], 2).$productos[$i]["unidad_medida"] . "</button>";
                }
            }


            /*================================
            TRAEMOS LAS ACCIONES
            ==================================*/
            $botones = "<div class='btn-group'><button class='btn btn-primary agregarProducto recuperarBoton' idEtiqueta='".$productos[$i]["id_etiqueta"]."' idProducto='" . $productos[$i]["id"] . "'>Agregar</button></div>";


            $datosJson .= '[
                    "' . ($i + 1) . '",
                    "' . $productos[$i]["codigo"] . '",
                    "' . $productos[$i]["descripcion"] . '",
                    "' . $etiquetas["etiqueta"] . '",
                    "' . $stock . '",
                    "' . $botones . '",
                    "' . $imagen . '"
                ],';
        }
        $datosJson = substr($datosJson, 0, -1);

        $datosJson .= ']
          }';

        echo $datosJson;
        //var_dump($productos);
        // return;
    }
}

/*================================
ACTIVAR LA TABLA DE PRODUCTOS
==================================*/
$activarProductos = new TablaProductosVentas();
$activarProductos->mostrarTablaProductosVentas();
