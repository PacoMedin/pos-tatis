<?php

class ControladorProductos
{

  /*=================================
    MOSTRAR PRODUCTOS
    ================================= */
  static public function ctrMostrarProductos($item, $valor, $orden)
  {

    $tabla = "productos";

    $respuesta = ModeloProductos::mdlMostrarProductos($tabla, $item, $valor, $orden);

    return $respuesta;
  }


  /*=================================
    CREAR PRODUCTOS
  ================================= */
  static public function ctrCrearProducto()
  {
    if (isset($_POST["nuevaDescripcion"])) {

      if (
        //preg_match('/^[0-9.]+$/', $_POST["nuevoCodigo"]) &&
        preg_match('/^[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ\/ ]+$/', $_POST["nuevaDescripcion"]) &&
        preg_match('/^[0-9.]+$/', $_POST["nuevoStock"]) &&
        preg_match('/^[0-9.]+$/', $_POST["nuevoPrecioCompra"]) &&
        preg_match('/^[0-9.]+$/', $_POST["nuevoPrecioVenta"])
      ) {
        /*=============================================
                    VALIDAR IMAGEN
        =============================================*/
        $ruta = "views/img/productos/default/anonymous.png";

        if (isset($_FILES["nuevaImagen"]["tmp_name"])) {
          //List permite crear un nuevo array con los indices que se le asignen
          list($ancho, $alto) = getimagesize($_FILES["nuevaImagen"]["tmp_name"]);
          // var_dump(getimagesize($_FILES["nuevaImagen"]["tmp_name"]));

          $nuevoAncho = 500;
          $nuevoAlto = 500;

          /*================================================================
          CREAMOS EL DIRECTORIO DONDE VAMOS A GUARDAR LA FOTO DEL PRODUCTO  
          ===============================================================*/
          $directorio = "views/img/productos/" . $_POST["nuevoCodigo"];
          mkdir($directorio, 0755);

          /*================================================================
                    DE ACUERDO AL TIPO DE IMAGEN APLICAMOS LAS FUNCIONES POR DEFECTO DE PHP
          ===============================================================*/

          if ($_FILES["nuevaImagen"]["type"] == "image/jpeg") {

            // Guardamos la imagen en el directorio
            $aleatorio = mt_rand(100, 999);

            $ruta = "views/img/productos/" . $_POST["nuevoCodigo"] . "/" . $aleatorio . ".jpg";

            $origen = imagecreatefromjpeg($_FILES["nuevaImagen"]["tmp_name"]);

            $destino = imagecreatetruecolor($nuevoAncho, $nuevoAlto);

            imagecopyresized($destino, $origen, 0, 0, 0, 0, $nuevoAncho, $nuevoAlto, $ancho, $alto);
            imagejpeg($destino, $ruta);
          }


          if ($_FILES["nuevaImagen"]["type"] == "image/png") {

            // Guardamos la imagen en el directorio
            $aleatorio = mt_rand(100, 999);

            $ruta = "views/img/productos/" . $_POST["nuevoCodigo"] . "/" . $aleatorio . ".png";

            $origen = imagecreatefrompng($_FILES["nuevaImagen"]["tmp_name"]);

            $destino = imagecreatetruecolor($nuevoAncho, $nuevoAlto);

            imagecopyresized($destino, $origen, 0, 0, 0, 0, $nuevoAncho, $nuevoAlto, $ancho, $alto);
            imagepng($destino, $ruta);
          }
        }

        $tabla = "productos";
      
        $datos = array(
          "id_categoria" => $_POST["nuevaCategoria"],
          "codigo" => $_POST["nuevoCodigo"],
          "descripcion" => $_POST["nuevaDescripcion"],
          "unidad" => $_POST["nuevaUnidad"],
          "stock" => $_POST["nuevoStock"],
          "precio_compra" => $_POST["nuevoPrecioCompra"],
          "precio_venta" => $_POST["nuevoPrecioVenta"],
          "imagen" => $ruta
        );

        $respuesta = ModeloProductos::mdlIngresarProductos($tabla, $datos);

        if ($respuesta == "ok") {
          echo '<script>
            swal({
                type: "success",
                title: "¡El producto ha sido guardado correctamente!",
                showConfirmButton: true,
                confirmButtonText: "Cerrar",
                closeOnConfirm: false
            }).then((result) => {
                if(result.value){
                    window.location = "productos";
                }
            });
            </script>';
        }
      } else {
        echo '<script>
        swal({
            type: "error",
            title: "¡El producto no puede ir con los campos vacíos o llevar caracteres especiales!",
            showConfirmButton: true,
            confirmButtonText: "Cerrar",
            closeOnConfirm: false
        }).then((result) => {
            if(result.value){
                window.location = "productos";
            }
        });
        </script>';
      }
    }
  }
  /*=================================
    EDITAR PRODUCTOS
    ================================= */
  static public function ctrEditarProducto()
  {
    if (isset($_POST["editarDescripcion"])) {

      if (
        preg_match('/^[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ\/ ]+$/', $_POST["editarDescripcion"]) &&
        preg_match('/^[0-9.]+$/', $_POST["editarStock"]) &&
        preg_match('/^[0-9.]+$/', $_POST["editarPrecioCompra"]) &&
        preg_match('/^[0-9.]+$/', $_POST["editarPrecioVenta"])
      ) {
        /*=============================================
                      VALIDAR IMAGEN
          =============================================*/
        $ruta = $_POST["imagenActual"];

        if (isset($_FILES["editarImagen"]["tmp_name"]) && !empty($_FILES["editarImagen"]["tmp_name"])) {
          //List permite crear un nuevo array con los indices que se le asignen
          list($ancho, $alto) = getimagesize($_FILES["editarImagen"]["tmp_name"]);
          // var_dump(getimagesize($_FILES["nuevaImagen"]["tmp_name"]));

          $nuevoAncho = 500;
          $nuevoAlto = 500;

          /*================================================================
            CREAMOS EL DIRECTORIO DONDE VAMOS A GUARDAR LA FOTO DEL PRODUCTO  
            ===============================================================*/
          $directorio = "views/img/productos/" . $_POST["editarCodigo"];
          /*================================================================
            PRIMERO PREGUNTAMOS SI EXISTE OTRA IMAGEN EN LA BD  
            ===============================================================*/
          if (!empty($_POST["imagenActual"]) && $_POST["imagenActual"] != "views/img/productos/default/anonymous.png") {
            unlink($_POST["imagenActual"]);
          } else {
            mkdir($directorio, 0755);
          }


          /*================================================================
              DE ACUERDO AL TIPO DE IMAGEN APLICAMOS LAS FUNCIONES POR DEFECTO DE PHP
            ===============================================================*/

          if ($_FILES["editarImagen"]["type"] == "image/jpeg") {

            // Guardamos la imagen en el directorio
            $aleatorio = mt_rand(100, 999);

            $ruta = "views/img/productos/" . $_POST["editarCodigo"] . "/" . $aleatorio . ".jpg";

            $origen = imagecreatefromjpeg($_FILES["editarImagen"]["tmp_name"]);

            $destino = imagecreatetruecolor($nuevoAncho, $nuevoAlto);

            imagecopyresized($destino, $origen, 0, 0, 0, 0, $nuevoAncho, $nuevoAlto, $ancho, $alto);
            imagejpeg($destino, $ruta);
          }


          if ($_FILES["editarImagen"]["type"] == "image/png") {

            // Guardamos la imagen en el directorio
            $aleatorio = mt_rand(100, 999);

            $ruta = "views/img/productos/" . $_POST["editarCodigo"] . "/" . $aleatorio . ".png";

            $origen = imagecreatefrompng($_FILES["editarImagen"]["tmp_name"]);

            $destino = imagecreatetruecolor($nuevoAncho, $nuevoAlto);

            imagecopyresized($destino, $origen, 0, 0, 0, 0, $nuevoAncho, $nuevoAlto, $ancho, $alto);
            imagepng($destino, $ruta);
          }
        }

        $tabla = "productos";

        $datos = array(
          "id" => $_POST["editarId"],
          "id_categoria" => $_POST["editarCategoria"],
          "codigo" => $_POST["editarCodigo"],
          "descripcion" => $_POST["editarDescripcion"],
          "unidad" => $_POST["editarUnidad"],
          "stock" => $_POST["editarStock"],
          "precio_compra" => $_POST["editarPrecioCompra"],
          "precio_venta" => $_POST["editarPrecioVenta"],
          "imagen" => $ruta
        );

        $respuesta = ModeloProductos::mdlEditarProductos($tabla, $datos);

        if ($respuesta == "ok") {
          echo '<script>
              swal({
                  type: "success",
                  title: "¡El producto ha sido guardado correctamente!",
                  showConfirmButton: true,
                  confirmButtonText: "Cerrar",
                  closeOnConfirm: false
              }).then((result) => {
                  if(result.value){
                      window.location = "productos";
                  }
              });
              </script>';
        }
      } else {
        echo '<script>
          swal({
              type: "error",
              title: "¡El producto no puede ir con los campos vacíos o llevar caracteres especiales!",
              showConfirmButton: true,
              confirmButtonText: "Cerrar",
              closeOnConfirm: false
          }).then((result) => {
              if(result.value){
                  window.location = "productos";
              }
          });
          </script>';
      }
    }
  }
  /*=================================
    BORRAR PRODUCTOS
    ================================= */
    static public function ctrEliminarProducto()
    {

      if(isset($_GET["idProducto"])){
        $tabla = "productos";
        $datos = $_GET["idProducto"];

        if($_GET["imagen"] != "" && $_GET["imagen"] != "views/img/productos/default/anonymous.png"){
          unlink($_GET["imagen"]);
          rmdir("views/img/productos/".$_GET["codigo"]);

        }

        $respuesta = ModeloProductos::mdlEliminarProducto($tabla, $datos);

        if ($respuesta == "ok") {
          echo '<script>
              swal({
                  type: "success",
                  title: "¡El producto ha sido borrado correctamente!",
                  showConfirmButton: true,
                  confirmButtonText: "Cerrar",
                  closeOnConfirm: false
              }).then((result) => {
                  if(result.value){
                      window.location = "productos";
                  }
              });
              </script>';
        }


      }
    }
  /*=============================================
	MOSTRAR SUMA VENTAS
	=============================================*/

	static public function ctrMostrarSumaVentas(){

		$tabla = "productos";

		$respuesta = ModeloProductos::mdlMostrarSumaVentas($tabla);

		return $respuesta;

	}
}
