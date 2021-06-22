<?php
class ControladorEtiquetas
{

    /*========================================
    CREAR ETIQUETAS
    ==========================================*/

    static public function ctrCrearEtiqueta()
    {

        if (isset($_POST["nuevaEtiqueta"])) {

            if (preg_match('/^[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ ]+$/', $_POST["nuevaEtiqueta"])) {

                $tabla = "etiquetas";
                $datos = $_POST["nuevaEtiqueta"];
        
                $respuesta = ModeloEtiquetas::mdlIngresarEtiqueta($tabla, $datos);
                
                if ($respuesta == "ok") {
                    echo '<script>
                    swal({
                        type: "success",
                        title: "¡La etiqueta ha sido guardada correctamente!",
                        showConfirmButton: true,
                        confirmButtonText: "Cerrar",
                        closeOnConfirm: false
                    }).then((result) => {
                        if(result.value){
                            window.location = "etiquetas";
                        }
                    });
                    </script>';
                }
            } else {
                echo '<script>
                swal({
                    type: "error",
                    title: "¡La etiqueta no puede ir vacía o llevar caracteres especiales!",
                    showConfirmButton: true,
                    confirmButtonText: "Cerrar",
                    closeOnConfirm: false
                }).then((result) => {
                    if(result.value){
                        window.location = "etiquetas";
                    }
                });
                </script>';
            }
        }
    }
        /*========================================
        MOSTRAR ETIQUETAS
        ==========================================*/

    static public function ctrMostrarEtiquetas($item, $valor){
        $tabla = "etiquetas";
        $respuesta = ModeloEtiquetas::mdlMostrarEtiquetas($tabla, $item, $valor);
        return $respuesta;
    }

        /*========================================
        MOSTRAR ETIQUETAS BUSQUEDA POR NOMBRE
        ==========================================*/

        static public function ctrMostrarEtiquetasNombre($item, $valor){
            $tabla = "etiquetas";
            $respuesta = ModeloEtiquetas::mdlMostrarEtiquetasNombre($tabla, $item, $valor);
            return $respuesta;
        }

     /*========================================
        EDITAR ETIQUETAS
    ==========================================*/

    
    static public function ctrEditarEtiquetas()
    {

        if (isset($_POST["editarEtiquetas"])) {

            if (preg_match('/^[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ ]+$/', $_POST["editarEtiquetas"])) {

                $tabla = "etiquetas";

                $datos = array("etiqueta"=> $_POST["editarEtiquetas"], "id"=>$_POST["idEtiqueta"]);

                $respuesta = ModeloEtiquetas::mdlEditarEtiquetas($tabla, $datos);

                if ($respuesta == "ok") {
                    echo '<script>
                    swal({
                        type: "success",
                        title: "¡La etiqueta ha sido cambiada correctamente!",
                        showConfirmButton: true,
                        confirmButtonText: "Cerrar",
                        closeOnConfirm: false
                    }).then((result) => {
                        if(result.value){
                            window.location = "etiquetas";
                        }
                    });
                    </script>';
                }
            } else {
                echo '<script>
                swal({
                    type: "error",
                    title: "¡La etiqueta no puede ir vacía o llevar caracteres especiales!",
                    showConfirmButton: true,
                    confirmButtonText: "Cerrar",
                    closeOnConfirm: false
                }).then((result) => {
                    if(result.value){
                        window.location = "etiquetas";
                    }
                });
                </script>';
            }
        }
    }

      /*========================================
        BORRAR ETIQUETAS
    ==========================================*/
    static public function ctrBorrarEtiquetas ()
    {
        if(isset($_GET["idEtiqueta"])){
            $tabla = "etiquetas";
            $datos = $_GET["idEtiqueta"];
            $respuesta = ModeloEtiquetas::mdlBorrarEtiquetas($tabla, $datos);
            if($respuesta == "ok"){
                echo'<script>

                    swal({
                        type: "success",
                        title: "La etiqueta ha sido borrada correctamente",
                        showConfirmButton: true,
                        confirmButtonText: "Cerrar"
                    }).then(function(result){
                         if (result.value) {

                          window.location = "etiquetas";

                        }
                    });

                    </script>';
            }

        }
    }

}
