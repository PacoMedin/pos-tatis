<?php

if($_SESSION["perfil"] == "Vendedor"){

  echo '<script>

    window.location = "inicio";

  </script>';

  return;

}

?>

<div class="content-wrapper">

  <section class="content-header">

    <h1>

      Administrar etiquetas

    </h1>

    <ol class="breadcrumb">

      <li><a href="inicio"><i class="fa fa-dashboard"></i> Inicio</a></li>

      <li class="active">Administrar etiquetas</li>

    </ol>

  </section>

  <section class="content">

    <div class="box">

      <div class="box-header with-border">

        <button class="btn btn-primary" data-toggle="modal" data-target="#modalAgregarEtiqueta">

          Agregar etiqueta

        </button>

      </div>

      <div class="box-body">

        <table class="table table-bordered table-striped dt-responsive tablas">

          <thead>

            <tr>

              <th style="width:10px">#</th>
              <th>Etiqueta</th>
              <th>Acciones</th>

            </tr>

          </thead>

          <tbody>
            <?php

            $item = null;
            $valor = null;

            $etiquetas = ControladorEtiquetas::ctrMostrarEtiquetas($item, $valor);

            // var_dump($Etiquetas);

            foreach ($etiquetas as $key => $value) {
              echo
                '<tr>
              <td>' . ($key + 1) . '</td>
              <td class="text-uppercase">' . $value["etiqueta"] . '</td>

              <td>

                <div class="btn-group">';

                if ($value["id"] != 1){
                  echo '<button class="btn btn-warning btnEditarEtiqueta" data-toggle="modal" data-target="#modalEditarEtiqueta" idEtiqueta="' . $value["id"] . '"><i class="fa fa-pencil"></i></button>';
                }

                  
              if ($_SESSION["perfil"] == "Administrador" && $value["id"] != 1) {
                echo '<button class="btn btn-danger btnEliminarEtiqueta" idEtiqueta="' . $value["id"] . '"><i class="fa fa-times"></i></button>';
              }

              echo '</div>

              </td>

            </tr>';
            }

            ?>


          </tbody>

        </table>

      </div>

    </div>

  </section>

</div>

<!--=====================================
MODAL AGREGAR ETIQUETA
======================================-->

<div id="modalAgregarEtiqueta" class="modal fade" role="dialog">

  <div class="modal-dialog">

    <div class="modal-content">

      <form role="form" method="POST">

        <!--=====================================
        CABEZA DEL MODAL
        ======================================-->

        <div class="modal-header" style="background:#ff851b; color:white">

          <button type="button" class="close" data-dismiss="modal">&times;</button>

          <h4 class="modal-title">Agregar etiqueta</h4>

        </div>

        <!--=====================================
        CUERPO DEL MODAL
        ======================================-->

        <div class="modal-body">

          <div class="box-body">

            <!-- ENTRADA PARA LA ETIQUETA -->

            <div class="form-group">

              <div class="input-group">

                <span class="input-group-addon"><i class="fa fa-tags"></i></span>

                <input type="text" class="form-control input-lg" name="nuevaEtiqueta" placeholder="Ingresar etiqueta" required>

              </div>

            </div>

          </div>

        </div>

        <!--=====================================
        PIE DEL MODAL
        ======================================-->

        <div class="modal-footer">

          <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Salir</button>

          <button type="submit" class="btn btn-primary">Guardar etiqueta</button>

        </div>
        <?php
        $crearEtiqueta = new ControladorEtiquetas();
        $crearEtiqueta -> ctrCrearEtiqueta();
        ?>


      </form>


    </div>

  </div>

</div>

<!--=====================================
MODAL EDITAR ETIQUETA
======================================-->

<div id="modalEditarEtiqueta" class="modal fade" role="dialog">

  <div class="modal-dialog">

    <div class="modal-content">

      <form role="form" method="post">

        <!--=====================================
        CABEZA DEL MODAL
        ======================================-->

        <div class="modal-header" style="background:#3c8dbc; color:white">

          <button type="button" class="close" data-dismiss="modal">&times;</button>

          <h4 class="modal-title">Editar Etiqueta</h4>

        </div>

        <!--=====================================
        CUERPO DEL MODAL
        ======================================-->

        <div class="modal-body">

          <div class="box-body">

            <!-- ENTRADA PARA LA ETIQUETA -->

            <div class="form-group">

              <div class="input-group">

                <span class="input-group-addon"><i class="fa fa-th"></i></span>

                <input type="text" class="form-control input-lg" name="editarEtiquetas" id="editarEtiquetas" required>
                <input type="hidden" name="idEtiqueta" id="idEtiqueta" required>


              </div>

            </div>

          </div>

        </div>

        <!--=====================================
        PIE DEL MODAL
        ======================================-->

        <div class="modal-footer">

          <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Salir</button>

          <button type="submit" class="btn btn-primary">Guardar cambios</button>

        </div>
        <?php
         $editarEtiqueta = new ControladorEtiquetas();
         $editarEtiqueta->ctrEditarEtiquetas();
        ?>


      </form>



    </div>

  </div>

</div>

<?php
   $borrarEtiqueta = new ControladorEtiquetas();
   $borrarEtiqueta->ctrBorrarEtiquetas();
?>