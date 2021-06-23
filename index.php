<?php
  
require_once "controllers/plantilla.controller.php";
require_once "controllers/usuarios.controller.php";
require_once "controllers/categorias.controller.php";
require_once "controllers/productos.controller.php";
require_once "controllers/clientes.controller.php";
require_once "controllers/ventas.controller.php";

require_once "models/usuarios.models.php";
require_once "models/categorias.models.php";
require_once "models/productos.models.php";
require_once "models/clientes.models.php";
require_once "models/ventas.models.php";
require_once "extensions/vendor/autoload.php";





$plantilla = new ControladorPlantilla();
$plantilla -> ctrPlantilla();