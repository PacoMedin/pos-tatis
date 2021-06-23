<?php

require_once "../../controllers/ventas.controller.php";
require_once "../../models/ventas.models.php";
require_once "../../controllers/clientes.controller.php";
require_once "../../models/clientes.models.php";
require_once "../../controllers/usuarios.controller.php";
require_once "../../models/usuarios.models.php";
require_once "../../models/etiquetas.models.php";

$reporte = new controladorVentas();
$reporte -> ctrDescargarReporte();