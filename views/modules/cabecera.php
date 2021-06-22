<header class="main-header">
<!-- ========================
            LOGO
=============================  -->
<a href="inicio" class="logo">
  <!-- Logo-mini-->
  <span class="logo-mini">
      <img src="views/img/plantilla/icono-blanco.png" class="img-responsive" style="padding:10px">
  </span>
  <!-- Logo regular -->
  <span class="logo-lg">
  <i class="fa fa-wrench"></i>
  Hecel<strong>chakan</strong>
   
      <!-- <img src="views/img/plantilla/logo-blanco-lineal.png" class="img-responsive" style="padding: 10px 0px;"> -->
  </span>
</a>
<!-- ========================
           NAV BAR
=============================  -->
<nav class="navbar navbar-static-top" role="navigation">
    <!-- Botón de navegación -->
<a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
    <span class="sr-only">Toggle navigation</span>
</a>
<!-- perfil de usuario -->

<div class="navbar-custom-menu">
    <ul class="nav navbar-nav">
        <li class="dropdown user user-menu">
            <a href="#" class="dropdow-toggle" data-toggle="dropdown">

            <?php 

                if($_SESSION["foto"] != ""){

                    echo '<img src="'.$_SESSION["foto"].'" class="user-image">';

                }else{
                    echo '<img src="views/img/usuarios/default/anonymous.png" class="user-image">';
                }
            ?>
               
                <span class="hidden-xs"> <?php echo $_SESSION["nombre"];?></span>
            </a>
            <!-- Dropdown-toggle -->
<ul class="dropdown-menu">
    <li class="user-body">
        <div class="pull-right">
            <a href="salir" class="btn btn-default btn-flat">Salir</a>
        </div>
    </li>
</ul>
        </li>
    </ul>
</div>


</nav>
  
</header>