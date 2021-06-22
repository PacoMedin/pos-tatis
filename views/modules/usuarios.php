<?php

if($_SESSION["perfil"] == "Especial" || $_SESSION["perfil"] == "Vendedor"){

  echo '<script>

    window.location = "inicio";

  </script>';

  return;

}

?>
 <div class="content-wrapper">
   <!-- Content Header (Page header) -->
   <section class="content-header">
     <h1>
       Administrar usuarios
     </h1>
     <ol class="breadcrumb">
       <li><a href="inicio"><i class="fa fa-home"></i> Inicio </a></li>
       <li class="active">Administrar usuarios</li>
     </ol>
   </section>

   <!-- Main content -->
   <section class="content">

     <!-- Default box -->
     <div class="box">
       <div class="box-header with-border">

         <button class="btn btn-primary" data-toggle="modal" data-target="#modalAgregarUsuario">
           Agregar
         </button>

       </div>

       <div class="box-body">

         <table class="table table-bordered table-striped dt-responsive tablas" width="100%">

           <thead>
             <tr>
               <th style="width: 10px;">#</th>
               <th>Nombre</th>
               <th>Usuario</th>
               <th>Foto</th>
               <th>Perfil</th>
               <th>Estado</th>
               <th>Último login</th>
               <th>Acciones</th>
             </tr>

           </thead>

           <tbody>
             <?php 
              $item = null;
              $valor = null;
              $usuarios = ControladorUsuarios::ctrMostrarUsuario($item, $valor);
              // var_dump($usuarios);
              foreach($usuarios as $key => $value){
               // var_dump($value["id"]);
                
               echo 
               '<tr>
               <td>'.($key+1).'</td>
               <td>'.$value["nombre"].'</td>
               <td>'.$value["usuario"].'</td>';

               if($value["foto"] != ""){

               echo'<td><img src="'.$value["foto"].'" class="img-thumbnail" width="40px"></td>';

               }else{
                echo'<td><img src="views/img/usuarios/default/anonymous.png" class="img-thumbnail" width="40px"></td>';
               }
               
               echo'<td>'.$value["perfil"].'</td>';

               if($value["estado"] != 0){

                echo ' <td><button class="btn btn-success btn-xs btnActivar" idUsuario="'.$value["id"].'" estadoUsuario="0">Activado</button></td>';

               }else{
                 echo ' <td><button class="btn btn-danger btn-xs btnActivar" idUsuario="'.$value["id"].'" estadoUsuario="1">Desactivado</button></td>';
               }
              

               echo
               '<td>'.$value["ultimo_login"].'</td>
               
               <td>
                 <div class="btn-group">  
                   <button class="btn btn-warning btnEditarUsuario" idUsuario="'.$value["id"].'" data-toggle="modal" 
                   data-target="#modalEditarUsuario"><i class="fa fa-pencil"></i></button>

                   <button class="btn btn-danger btnEliminarUsuario" usuario="'.$value["usuario"].'" idUsuario="'.$value["id"].'" fotoUsuario="'.$value["foto"].'"><i class="fa fa-times"></i></button>
                 
                   </div>
               </td>
               </tr>';

              }

            ?>

             
           </tbody>

         </table>

       </div>
       <!-- /.box-body -->

     </div>
     <!-- /.box -->
   </section>
   <!-- /.content -->
 </div>
 <!-- /.content-wrapper -->


 <!--==============================
    MODAL AGREGAR USUARIO
================================== -->


 <div id="modalAgregarUsuario" class="modal fade" role="dialog">
   <div class="modal-dialog">
     <!-- Modal content-->
     <div class="modal-content">

       <form role="form" method="POST" enctype="multipart/form-data">
         <!--==============================
               CABEZA DEL MODAL
        ================================== -->
         <div class="modal-header" style="background:#ff851b ; color:white;">
           <button type="button" class="close" data-dismiss="modal">&times;</button>
           <h4 class="modal-title">Agregar usuario</h4>
         </div>
         <!--==============================
               CUERPO DEL MODAL
        ================================== -->

         <div class="modal-body">
           <div class="box-body">

             <!-- Entrada para el nombre -->
             <div class="form-group">
               <div class="input-group">

                 <span class="input-group-addon"><i class="fa fa-user"></i></span>
                 <input class="form-control input-lg" type="text" name="nuevoNombre" placeholder="Ingresar nombre" required>
               </div>
             </div>
             <!-- Entrada para el usuario -->
             <div class="form-group">
               <div class="input-group">

                 <span class="input-group-addon"><i class="fa fa-key"></i></span>
                 <input class="form-control input-lg" type="text" name="nuevoUsuario" id="nuevoUsuario" placeholder="Ingresar usuario" required>
               </div>
             </div>
             <!-- Entrada para la contraseña -->
             <div class="form-group">
               <div class="input-group">

                 <span class="input-group-addon"><i class="fa fa-lock"></i></span>
                 <input class="form-control input-lg" type="password" name="nuevoPassword" placeholder="Ingresar contraseña" required>
               </div>
             </div>
             <!-- Entrada  para seleccionar perfil -->
             <div class="form-group">
               <div class="input-group">

                 <span class="input-group-addon"><i class="fa fa-users"></i></span>
                 <select class="form-control input-lg" name="nuevoPerfil">
                   <option value="">Seleccionar perfil</option>
                   <option value="Administrador">Administrador</option>
                   <option value="Especial">Especial</option>
                   <option value="Vendedor">Vendedor</option>
                 </select>
               </div>
             </div>
             <!-- Entrada  para subir foto -->
             <div class="form-group">
               <div class="panel">SUBIR FOTO </div>
               <input type="file" class="nuevaFoto" name="nuevaFoto" >
               <p class="help-block">Peso máximo de la foto 2 MB</p>
               <img src="views/img/usuarios/default/anonymous.png" class="img-thumbnail previsualizar" width="100px">
             </div>

           </div>
         </div>

         <!--==============================
               PIE DEL MODAL
        ================================== -->

         <div class="modal-footer">
           <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Salir</button>
           <button type="submit" class="btn btn-primary">Guardar usuario</button>
         </div>

     </div>
     <?php
          $crearUsuario = new ControladorUsuarios();
          $crearUsuario->ctrCrearUsuario();
     ?>
     </form>

   </div>
 </div>


 <!--==============================
    MODAL EDITAR USUARIO
================================== -->
<div id="modalEditarUsuario" class="modal fade" role="dialog">
   <div class="modal-dialog">
     <!-- Modal content-->
     <div class="modal-content">

       <form role="form" method="POST" enctype="multipart/form-data">
         <!--==============================
               CABEZA DEL MODAL
        ================================== -->
         <div class="modal-header" style="background:#ff851b ; color:white;">
           <button type="button" class="close" data-dismiss="modal">&times;</button>
           <h4 class="modal-title">Editar usuario</h4>
         </div>
         <!--==============================
               CUERPO DEL MODAL
        ================================== -->

         <div class="modal-body">
           <div class="box-body">

             <!-- Entrada para el nombre -->
             <div class="form-group">
               <div class="input-group">

                 <span class="input-group-addon"><i class="fa fa-user"></i></span>
                 <input class="form-control input-lg" type="text" id="editarNombre" name="editarNombre"  value="" required>
               </div>
             </div>
             <!-- Entrada para el usuario -->
             <div class="form-group">
               <div class="input-group">

                 <span class="input-group-addon"><i class="fa fa-key"></i></span>
                 <input class="form-control input-lg" type="text" name="editarUsuario" id="editarUsuario" value="" readonly>
               </div>
             </div>
             <!-- Entrada para la contraseña -->
             <div class="form-group">
               <div class="input-group">

                 <span class="input-group-addon"><i class="fa fa-lock"></i></span>
                 <input class="form-control input-lg" type="password" name="editarPassword" placeholder="Escriba la nueva contraseña">
                 <input type="hidden" id="passwordActual" name="passwordActual">
                </div>
             </div>
             <!-- Entrada  para seleccionar perfil -->
             <div class="form-group">
               <div class="input-group">

                 <span class="input-group-addon"><i class="fa fa-users"></i></span>
                 <select class="form-control input-lg" name="editarPerfil">
                   <option value="" id="editarPerfil"></option>
                   <option value="Administrador">Administrador</option>
                   <option value="Especial">Especial</option>
                   <option value="Vendedor">Vendedor</option>
                 </select>
               </div>
             </div>
             <!-- Entrada  para subir foto -->
             <div class="form-group">
               <div class="panel">SUBIR FOTO </div>
               <input type="file" class="nuevaFoto" name="editarFoto" lang="es">
               <p class="help-block">Peso máximo de la foto 2 MB</p>
               <img src="views/img/usuarios/default/anonymous.png" class="img-thumbnail previsualizar" width="100px">
               <input type="hidden" name="fotoActual" id="fotoActual">
              </div>

           </div>
         </div>

         <!--==============================
               PIE DEL MODAL
        ================================== -->

         <div class="modal-footer">
           <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Salir</button>
           <button type="submit" class="btn btn-primary">Modificar usuario</button>
         </div>

     </div>
       <?php
           $editarUsuario = new ControladorUsuarios();
           $editarUsuario->ctrEditarUsuario();
       ?>
     </form>

   </div>
 </div>
 <?php 
        $borrarUsuario = new ControladorUsuarios();
        $borrarUsuario -> ctrBorrarUsuario();
 ?>