<?php

require_once "../controllers/productos.controller.php";
require_once "../models/productos.models.php";

require_once "../controllers/categorias.controller.php";
require_once "../models/categorias.models.php";
require_once "../controllers/etiquetas.controller.php";
require_once "../models/etiquetas.models.php";

class TablaProductos
{

    /*================================
    MOSTRAR LA TABLA DE PRODUCTOS
    ==================================*/

    public function mostrarTablaProductos()
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
            TRAEMOS LA CATEGORÍA
            ==================================*/

            $item = "id";
            $valor = $productos[$i]["id_categoria"];
            $categorias = ControladorCategorias::ctrMostrarCategorias($item, $valor);


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
                    $stock = "<button class='btn btn-danger  btn-block'>" . $productos[$i]["stock"] . "</button>";
                } else {
                    $stock = "<button class='btn btn-danger btn-block'>" . number_format($productos[$i]["stock"], 2) . "</button>";
                }

            } else if ($productos[$i]["stock"] > 10 && $productos[$i]["stock"] <= 15) {

                if ($productos[$i]["unidad_medida"] == "pz") {
                    $stock = "<button class='btn btn-warning btn-block'>" . $productos[$i]["stock"] . "</button>";
                } else {
                    $stock = "<button class='btn btn-warning btn-block'>" . number_format($productos[$i]["stock"], 2) . "</button>";
                }

            } else {

                if ($productos[$i]["unidad_medida"] == "pz") {

                    $stock = "<button class='btn btn-success btn-block'>".$productos[$i]["stock"]. "</button>";
                } else {
                    $stock = "<button class='btn btn-success btn-block'>" . number_format($productos[$i]["stock"], 2) . "</button>";
                }
            }


            /*================================
            TRAEMOS LAS ACCIONES
            ==================================*/

            if (isset($_GET["perfilOculto"]) && $_GET["perfilOculto"] == "Especial") {

                $botones =  "<div class='btn-group'><button class='btn btn-warning btnEditarProducto' idProducto='" . $productos[$i]["id"] . "' data-toggle='modal' data-target='#modalEditarProducto'><i class='fa fa-pencil'></i></button></div>";
            } else {

                $botones =  "<div class='btn-group'><button class='btn btn-warning btnEditarProducto' idProducto='" . $productos[$i]["id"] . "' data-toggle='modal' data-target='#modalEditarProducto'><i class='fa fa-pencil'></i></button><button class='btn btn-danger btnEliminarProducto' idProducto='" . $productos[$i]["id"] . "' codigo='" . $productos[$i]["codigo"] . "' imagen='" . $productos[$i]["imagen"] . "'><i class='fa fa-times'></i></button></div>";
            }
            number_format((float)$productos[$i]["unidad_medida"], 2, ".", " ");

            /*================================
            FORMATEAR STOCK
            ==================================*/

            $datosJson .= '[
                    "' . ($i + 1) . '",
                    "' . $imagen . '",
                    "' . $productos[$i]["codigo"] . '",
                    "' . $productos[$i]["descripcion"] . '",
                    "' . $categorias["categoria"] . '",
                    "' . $etiquetas["etiqueta"] . '",
                    "' . $productos[$i]["unidad_medida"] . '",
                    "' . $stock . '",
                    "' . $productos[$i]["precio_compra"] . '",
                    "' . $productos[$i]["precio_venta"] . '",
                    "' . $productos[$i]["fecha"] . '",
                    "' . $botones . '"
                ],';
        }
        $datosJson = substr($datosJson, 0, -1);

        $datosJson .= ']
          }';

        echo $datosJson;
        // var_dump($productos);
        // return;
    }
}

/*================================
ACTIVAR LA TABLA DE PRODUCTOS
==================================*/
$activarProductos = new TablaProductos();
$activarProductos->mostrarTablaProductos();
