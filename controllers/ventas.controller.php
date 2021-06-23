<?php

use Mike42\Escpos\Printer;
use Mike42\Escpos\EscposImage;
use Mike42\Escpos\PrintConnectors\FilePrintConnector;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;

class ControladorVentas
{

	/*=============================================
	MOSTRAR VENTAS
	=============================================*/

	static public function ctrMostrarVentas($item, $valor)
	{

		$tabla = "ventas";

		$respuesta = ModeloVentas::mdlMostrarVentas($tabla, $item, $valor);

		return $respuesta;
	}

	/*=============================================
	CREAR VENTA
	=============================================*/

	static public function ctrCrearVenta()
	{

		if (isset($_POST["nuevaVenta"])) {

			/*=============================================
			ACTUALIZAR LAS COMPRAS DEL CLIENTE Y REDUCIR EL STOCK Y AUMENTAR LAS VENTAS DE LOS PRODUCTOS
			=============================================*/
			if ($_POST["listaProductos"] == "") {

				echo '<script>

			swal({
				  type: "error",
				  title: "No hay productos en la lista",
				  showConfirmButton: true,
				  confirmButtonText: "Cerrar"
				  }).then(function(result){
							if (result.value) {

							window.location = "ventas";

							}
						})

			</script>';

				return;
			}
			if ($_POST["listaMoneda"] == "") {

				echo '<script>

			swal({
				  type: "error",
				  title: "El pago no se efectuo",
				  showConfirmButton: true,
				  confirmButtonText: "Cerrar"
				  }).then(function(result){
							if (result.value) {

							window.location = "ventas";

							}
						})

			</script>';

				return;
			}



			$listaProductos = json_decode($_POST["listaProductos"], true);
			$listaMoneda = json_decode($_POST["listaMoneda"], true);

			//var_dump($listaProductos);
			//var_dump($listaMoneda);
			$totalProductosComprados = array();

			foreach ($listaProductos as $key => $value) {

				array_push($totalProductosComprados, $value["cantidad"]);

				$tablaProductos = "productos";

				$item = "id";
				$valor = $value["id"];
				$orden = "id";

				$traerProducto = ModeloProductos::mdlMostrarProductos($tablaProductos, $item, $valor, $orden);

				$item1a = "ventas";
				$valor1a = $value["cantidad"] + $traerProducto["ventas"];

				$nuevasVentas = ModeloProductos::mdlActualizarProducto($tablaProductos, $item1a, $valor1a, $valor);

				$item1b = "stock";
				$valor1b = $value["stock"];

				$nuevoStock = ModeloProductos::mdlActualizarProducto($tablaProductos, $item1b, $valor1b, $valor);
			}

			$tablaClientes = "clientes";

			$item = "id";
			$valor = $_POST["seleccionarCliente"];

			$traerCliente = ModeloClientes::mdlMostrarClientes($tablaClientes, $item, $valor);

			$item1a = "compras";
			$valor1a = array_sum($totalProductosComprados) + $traerCliente["compras"];

			$comprasCliente = ModeloClientes::mdlActualizarCliente($tablaClientes, $item1a, $valor1a, $valor);

			$item1b = "ultima_compra";

			date_default_timezone_set('America/Mexico_City');

			$fecha = date('Y-m-d');
			$hora = date('H:i:s');
			$valor1b = $fecha . ' ' . $hora;

			$fechaCliente = ModeloClientes::mdlActualizarCliente($tablaClientes, $item1b, $valor1b, $valor);

			/*=============================================
			GUARDAR LA COMPRA
			=============================================*/
			$efectivo = "";
			$cambio = "";
			foreach ($listaMoneda as $key => $value) {
				$efectivo = $value["efectivo"];
				$cambio = $value["cambio"];
			}
			$tabla = "ventas";

			$datos = array(
				"id_vendedor" => $_POST["idVendedor"],
				"id_cliente" => $_POST["seleccionarCliente"],
				"codigo" => $_POST["nuevaVenta"],
				"productos" => $_POST["listaProductos"],
				"descuento" => $_POST["nuevoPrecioDescuento"],
				"neto" => $_POST["nuevoPrecioNeto"],
				"total" => $_POST["totalVenta"],
				"metodo_pago" => $_POST["listaMetodoPago"],
				"efectivo" => $efectivo,
				"cambio" => $cambio
			);

			$respuesta = ModeloVentas::mdlIngresarVenta($tabla, $datos);

			if ($respuesta == "ok") {

				$impresora = "XP-58";

				$conector = new WindowsPrintConnector($impresora);

				$printer = new Printer($conector);

				$printer->setFont(Printer::FONT_A);
				$printer -> setJustification(Printer::JUSTIFY_CENTER);

				$printer -> text("Hecelchakan"."\n");//Nombre de la empresa

				//$printer -> feed(1); //Alimentamos el papel 1 vez*/
				//$printer -> text("NIT: 71.759.963-9"."\n");//Nit de la empresa

				$printer -> text("Dirección: Coahuila, No. 52"."\n");//Dirección de la empresa
			    $printer -> text("CP: 59300, ");//CP de la empresa
				$printer -> text("LA PIEDAD MICH."."\n");//Ciudad de la empresa
				//$printer -> text("Teléfono: 352-525-4332"."\n");//Teléfono de la empresa

				//$printer -> text("FACTURA N.".$_POST["nuevaVenta"]."\n");//Número de factura
				$printer -> feed(1); //Alimentamos el papel 1 vez*/
				$printer -> text("Cliente: ".$traerCliente["nombre"]."\n");//Nombre del cliente
				$tablaVendedor = "usuarios";
				$item = "id";
				$valor = $_POST["idVendedor"];
				$traerVendedor = ModeloUsuarios::mdlMostrarUsuarios($tablaVendedor, $item, $valor);
				$printer -> text("Vendedor: ".$traerVendedor["nombre"]."\n");//Nombre del vendedor
				$printer -> feed(1); //Alimentamos el papel 1 vez*/
				$printer -> text(date("Y-m-d H:i:s")."\n");//Fecha de la factura
				$printer -> feed(1); //Alimentamos el papel 1 vez*/



				  $printer -> text("Cantidad   Descripción   Importe"."\n");
				  $printer -> text("================================\n");

				 //$numeroArticulos = 0;

				 $nuevaCantidad = 0;
			
			
				 foreach ($listaProductos as $key => $value) {
				
			
				 	//$numeroArticulos += $value["cantidad"];

				 	if ($value["cantidad"] < 1) {

				 		if ($value["unidad_medida"] == "m") {

				 			$nuevaCantidad = $value["cantidad"] * 100;
				 			$value["cantidad"] = $nuevaCantidad;
				 			$value["unidad_medida"] = "cm";

				 		} elseif ($value["unidad_medida"] == "kg") {

				 			$nuevaCantidad = $value["cantidad"] * 1000;
				 			$value["cantidad"] = $nuevaCantidad;
				 			$value["unidad_medida"] = "g";

				 		}
					 }
				 
			

                    $printer->setJustification(Printer::JUSTIFY_LEFT);
					$printer->text(" " .$value["cantidad"] . " " . $value["unidad_medida"]. "   ".$value["descripcion"]); //Cantidad
					$printer->feed(1);
				 	// $printer->setJustification(Printer::JUSTIFY_CENTER);
					 // $printer->text($value["descripcion"] . "  "); //Nombre del producto
					$printer->setJustification(Printer::JUSTIFY_RIGHT);
				 	$printer->text("$ " . number_format($value["total"], 2) . "\n");//Importe
				 }
				
			
				$printer->text("================================" . "\n");
				$printer->feed(); //Alimentamos el papel 1 vez*/		


				$printer->setJustification(Printer::JUSTIFY_CENTER);
				$printer->text("NETO: "); //ahora va el neto
				$printer->setJustification(Printer::JUSTIFY_RIGHT);
				$printer->text("$ " . number_format($_POST["nuevoPrecioNeto"], 2) . "\n"); //ahora va el neto


				$printer->setJustification(Printer::JUSTIFY_CENTER);
				$printer->text("DESCUENTO: "); //ahora va el descuento
				$printer->setJustification(Printer::JUSTIFY_RIGHT);
				$printer->text("$ " . number_format($_POST["nuevoPrecioDescuento"], 2) . "\n"); //ahora va el descuento



				$printer->text("--------------\n");
				$printer->setJustification(Printer::JUSTIFY_CENTER);
				$printer->text("TOTAL: "); //ahora va el total
				$printer->setJustification(Printer::JUSTIFY_RIGHT);
				$printer->text("$ " . number_format($_POST["totalVenta"], 2) . "\n"); //ahora va el total
				$printer->text("--------------\n");

				$printer->setJustification(Printer::JUSTIFY_CENTER);
				$printer->text("EFECTIVO: "); //ahora va el efectivo
				$printer->setJustification(Printer::JUSTIFY_RIGHT);
				$printer->text("$ " . number_format($efectivo, 2) . "\n"); //ahora va el efectivo

				$printer->setJustification(Printer::JUSTIFY_CENTER);
				$printer->text("CAMBIO: "); //ahora va el cambio
				$printer->setJustification(Printer::JUSTIFY_RIGHT);
				$printer->text("$ " . number_format($cambio, 2));
			
				$printer->feed(2); //Alimentamos el papel 1 vez*/					
				$printer->text("¡Gracias por su compra!"); //Podemos poner también un pie de página
				$printer->feed(3); //Alimentamos el papel 3 veces*/
				$printer->cut(); //Cortamos el papel, si la impresora tiene la opción
				$printer->pulse(); //Por medio de la impresora mandamos un pulso, es útil cuando hay cajón moneder
				$printer->close();

				echo '<script>

				localStorage.removeItem("rango");

				swal({
					  type: "success",
					  title: "La venta ha sido guardada correctamente",
					  showConfirmButton: true,
					  confirmButtonText: "Cerrar"
					  }).then((result) => {
								

								window.location = "crear-venta";

								
							})

				</script>';
			}
		}
	}

	/*=============================================
	EDITAR VENTA
	=============================================*/

	static public function ctrEditarVenta()
	{

		if (isset($_POST["editarVenta"])) {

			/*=============================================
			FORMATEAR TABLA DE PRODUCTOS Y LA DE CLIENTES
			=============================================*/
			$tabla = "ventas";

			$item = "codigo";
			$valor = $_POST["editarVenta"];

			$traerVenta = ModeloVentas::mdlMostrarVentas($tabla, $item, $valor);


			/*=============================================
			REVISAR SI VIENE PRODUCTOS EDITADOS
			=============================================*/

			if ($_POST["listaProductos"] == "") {

				$listaProductos = $traerVenta["productos"];
				$cambioProducto = false;
			} else {

				$listaProductos = $_POST["listaProductos"];
				$cambioProducto = true;
			}


			if ($cambioProducto) {

				$productos =  json_decode($traerVenta["productos"], true);

				$totalProductosComprados = array();

				foreach ($productos as $key => $value) {

					array_push($totalProductosComprados, $value["cantidad"]);

					$tablaProductos = "productos";

					$item = "id";
					$valor = $value["id"];
					$orden = "id";

					$traerProducto = ModeloProductos::mdlMostrarProductos($tablaProductos, $item, $valor, $orden);

					$item1a = "ventas";
					$valor1a = $traerProducto["ventas"] - $value["cantidad"];

					$nuevasVentas = ModeloProductos::mdlActualizarProducto($tablaProductos, $item1a, $valor1a, $valor);

					$item1b = "stock";
					$valor1b = $value["cantidad"] + $traerProducto["stock"];

					$nuevoStock = ModeloProductos::mdlActualizarProducto($tablaProductos, $item1b, $valor1b, $valor);
				}

				$tablaClientes = "clientes";

				$itemCliente = "id";
				$valorCliente = $_POST["seleccionarCliente"];

				$traerCliente = ModeloClientes::mdlMostrarClientes($tablaClientes, $itemCliente, $valorCliente);

				$item1a = "compras";
				$valor1a = $traerCliente["compras"] - array_sum($totalProductosComprados);

				$comprasCliente = ModeloClientes::mdlActualizarCliente($tablaClientes, $item1a, $valor1a, $valorCliente);

				/*=============================================
				ACTUALIZAR LAS COMPRAS DEL CLIENTE Y REDUCIR EL STOCK Y AUMENTAR LAS VENTAS DE LOS PRODUCTOS
				=============================================*/

				$listaProductos_2 = json_decode($listaProductos, true);

				$totalProductosComprados_2 = array();

				foreach ($listaProductos_2 as $key => $value) {

					array_push($totalProductosComprados_2, $value["cantidad"]);

					$tablaProductos_2 = "productos";

					$item_2 = "id";
					$valor_2 = $value["id"];
					$orden = "id";


					$traerProducto_2 = ModeloProductos::mdlMostrarProductos($tablaProductos_2, $item_2, $valor_2, $orden);

					$item1a_2 = "ventas";
					$valor1a_2 = $value["cantidad"] + $traerProducto_2["ventas"];

					$nuevasVentas_2 = ModeloProductos::mdlActualizarProducto($tablaProductos_2, $item1a_2, $valor1a_2, $valor_2);

					$item1b_2 = "stock";
					$valor1b_2 = $traerProducto_2["stock"] - $value["cantidad"];

					$nuevoStock_2 = ModeloProductos::mdlActualizarProducto($tablaProductos_2, $item1b_2, $valor1b_2, $valor_2);
				}

				$tablaClientes_2 = "clientes";

				$item_2 = "id";
				$valor_2 = $_POST["seleccionarCliente"];

				$traerCliente_2 = ModeloClientes::mdlMostrarClientes($tablaClientes_2, $item_2, $valor_2);

				$item1a_2 = "compras";
				$valor1a_2 = array_sum($totalProductosComprados_2) + $traerCliente_2["compras"];

				$comprasCliente_2 = ModeloClientes::mdlActualizarCliente($tablaClientes_2, $item1a_2, $valor1a_2, $valor_2);

				$item1b_2 = "ultima_compra";

				date_default_timezone_set('America/Mexico_City');

				$fecha = date('Y-m-d');
				$hora = date('H:i:s');
				$valor1b_2 = $fecha . ' ' . $hora;

				$fechaCliente_2 = ModeloClientes::mdlActualizarCliente($tablaClientes_2, $item1b_2, $valor1b_2, $valor_2);
			}



			/*=============================================
			GUARDAR CAMBIOS DE LA COMPRA
			=============================================*/
			if ($_POST["totalVenta"] == "0") {
				echo '<script>

				swal({
					  type: "error",
					  title: "No hay productos en la lista",
					  showConfirmButton: true,
					  confirmButtonText: "Cerrar"
					  }).then(function(result){
								if (result.value) {
	
								window.location = "ventas";
	
								}
							})
	
				</script>';

				return;
			}

			$listaMoneda = json_decode($_POST["listaMoneda"], true);
			$efectivo = "";
			$cambio = "";
			foreach ($listaMoneda as $key => $value) {
				$efectivo = $value["efectivo"];
				$cambio = $value["cambio"];
			}

			$datos = array(
				"id_vendedor" => $_POST["idVendedor"],
				"id_cliente" => $_POST["seleccionarCliente"],
				"codigo" => $_POST["editarVenta"],
				"productos" => $listaProductos,
				"descuento" => $_POST["nuevoPrecioDescuento"],
				"neto" => $_POST["nuevoPrecioNeto"],
				"total" => $_POST["totalVenta"],
				"metodo_pago" => $_POST["listaMetodoPago"],
				"efectivo" => $efectivo,
				"cambio" => $cambio
			);


			$respuesta = ModeloVentas::mdlEditarVenta($tabla, $datos);

			if ($respuesta == "ok") {

				echo '<script>

				localStorage.removeItem("rango");

				swal({
					  type: "success",
					  title: "La venta ha sido editada correctamente",
					  showConfirmButton: true,
					  confirmButtonText: "Cerrar"
					  }).then((result) => {
								if (result.value) {

								window.location = "ventas";

								}
							})

				</script>';
			}
		}
	}


	/*=============================================
	ELIMINAR VENTA
	=============================================*/

	static public function ctrEliminarVenta()
	{

		if (isset($_GET["idVenta"])) {

			$tabla = "ventas";

			$item = "id";
			$valor = $_GET["idVenta"];

			$traerVenta = ModeloVentas::mdlMostrarVentas($tabla, $item, $valor);

			/*=============================================
			ACTUALIZAR FECHA ÚLTIMA COMPRA
			=============================================*/

			$tablaClientes = "clientes";

			$itemVentas = null;
			$valorVentas = null;

			$traerVentas = ModeloVentas::mdlMostrarVentas($tabla, $itemVentas, $valorVentas);

			$guardarFechas = array();

			foreach ($traerVentas as $key => $value) {

				if ($value["id_cliente"] == $traerVenta["id_cliente"]) {

					array_push($guardarFechas, $value["fecha"]);
				}
			}

			if (count($guardarFechas) > 1) {

				if ($traerVenta["fecha"] > $guardarFechas[count($guardarFechas) - 2]) {

					$item = "ultima_compra";
					$valor = $guardarFechas[count($guardarFechas) - 2];
					$valorIdCliente = $traerVenta["id_cliente"];

					$comprasCliente = ModeloClientes::mdlActualizarCliente($tablaClientes, $item, $valor, $valorIdCliente);
				} else {

					$item = "ultima_compra";
					$valor = $guardarFechas[count($guardarFechas) - 1];
					$valorIdCliente = $traerVenta["id_cliente"];

					$comprasCliente = ModeloClientes::mdlActualizarCliente($tablaClientes, $item, $valor, $valorIdCliente);
				}
			} else {

				$item = "ultima_compra";
				$valor = "0000-00-00 00:00:00";
				$valorIdCliente = $traerVenta["id_cliente"];

				$comprasCliente = ModeloClientes::mdlActualizarCliente($tablaClientes, $item, $valor, $valorIdCliente);
			}

			/*=============================================
			FORMATEAR TABLA DE PRODUCTOS Y LA DE CLIENTES
			=============================================*/

			$productos =  json_decode($traerVenta["productos"], true);

			$totalProductosComprados = array();

			foreach ($productos as $key => $value) {

				array_push($totalProductosComprados, $value["cantidad"]);

				$tablaProductos = "productos";

				$item = "id";
				$valor = $value["id"];
				$orden = "id";

				$traerProducto = ModeloProductos::mdlMostrarProductos($tablaProductos, $item, $valor, $orden);

				$item1a = "ventas";
				$valor1a = $traerProducto["ventas"] - $value["cantidad"];

				$nuevasVentas = ModeloProductos::mdlActualizarProducto($tablaProductos, $item1a, $valor1a, $valor);

				$item1b = "stock";
				$valor1b = $value["cantidad"] + $traerProducto["stock"];

				$nuevoStock = ModeloProductos::mdlActualizarProducto($tablaProductos, $item1b, $valor1b, $valor);
			}

			$tablaClientes = "clientes";

			$itemCliente = "id";
			$valorCliente = $traerVenta["id_cliente"];

			$traerCliente = ModeloClientes::mdlMostrarClientes($tablaClientes, $itemCliente, $valorCliente);

			$item1a = "compras";
			$valor1a = $traerCliente["compras"] - array_sum($totalProductosComprados);

			$comprasCliente = ModeloClientes::mdlActualizarCliente($tablaClientes, $item1a, $valor1a, $valorCliente);

			/*=============================================
			ELIMINAR VENTA
			=============================================*/

			$respuesta = ModeloVentas::mdlEliminarVenta($tabla, $_GET["idVenta"]);

			if ($respuesta == "ok") {

				echo '<script>

				swal({
					  type: "success",
					  title: "La venta ha sido borrada correctamente",
					  showConfirmButton: true,
					  confirmButtonText: "Cerrar",
					  closeOnConfirm: false
					  }).then((result) => {
								

								 window.location = "ventas";

								
							})

				</script>';
			}
		}
	}

	/*=============================================
	RANGO FECHAS
	=============================================*/

	static public function ctrRangoFechasVentas($fechaInicial, $fechaFinal)
	{

		$tabla = "ventas";

		$respuesta = ModeloVentas::mdlRangoFechasVentas($tabla, $fechaInicial, $fechaFinal);

		return $respuesta;
	}

	/*=============================================
	DESCARGAR EXCEL
	=============================================*/

	public function ctrDescargarReporte()
	{

		if (isset($_GET["reporte"])) {

			$tabla = "ventas";

			if (isset($_GET["fechaInicial"]) && isset($_GET["fechaFinal"])) {

				$ventas = ModeloVentas::mdlRangoFechasVentas($tabla, $_GET["fechaInicial"], $_GET["fechaFinal"]);
			} else {

				$item = null;
				$valor = null;

				$ventas = ModeloVentas::mdlMostrarVentas($tabla, $item, $valor);
			}


			/*=============================================
			 CREAMOS EL ARCHIVO DE EXCEL
			 =============================================*/

			$Name = $_GET["reporte"] . '.xls';

			//Creamos contador para el total del reporte
			$cont = 0;

			header('Expires: 0');
			header('Cache-control: private');
			header("Content-type: application/vnd.ms-excel"); // Archivo de Excel
			header("Cache-Control: cache, must-revalidate");
			header('Content-Description: File Transfer');
			header('Last-Modified: ' . date('D, d M Y H:i:s'));
			header("Pragma: public");
			header('Content-Disposition:; filename="' . $Name . '"');
			header("Content-Transfer-Encoding: binary");

			echo utf8_decode("<table border='0'> 

			 		<tr> 
			 		<td style='font-weight:bold; border:1px solid #eee;'>CÓDIGO</td> 
			 		<td style='font-weight:bold; border:1px solid #eee;'>CLIENTE</td>
			 		<td style='font-weight:bold; border:1px solid #eee;'>VENDEDOR</td>
			 		<td style='font-weight:bold; border:1px solid #eee;'>CANTIDAD</td>
			 		<td style='font-weight:bold; border:1px solid #eee;'>PRODUCTOS</td>
			 		<td style='font-weight:bold; border:1px solid #eee;'>TOTAL</td>
			 		<td style='font-weight:bold; border:1px solid #eee;'>FECHA</td>		
					 </tr>");
					 

			foreach ($ventas as $row => $item) {

				$cliente = ControladorClientes::ctrMostrarClientes("id", $item["id_cliente"]);
				$vendedor = ControladorUsuarios::ctrMostrarUsuario("id", $item["id_vendedor"]);
				$productos =  json_decode($item["productos"], true);
				
				echo utf8_decode("<tr>
				<td style='border:1px solid #eee;'>" . $item["codigo"] . "</td> 
				<td style='border:1px solid #eee;'>" . $cliente["nombre"] . "</td>
				<td style='border:1px solid #eee;'>" . $vendedor["nombre"] . "</td>
				<td style='border:1px solid #eee;'>");

				foreach($productos as $key => $valorProducto){

					if( $valorProducto["cantidad"] < 1){
	
						if($valorProducto["unidad_medida"] == "m"){
	
							echo utf8_decode($valorProducto["cantidad"] * 100 ." cm"."". "<br>");
	
						}elseif($valorProducto["unidad_medida"] == "kg"){
	
							echo utf8_decode($valorProducto["cantidad"] * 1000 ." g"."". "<br>");
						}
	
					}else{
						
					  echo utf8_decode($valorProducto["cantidad"] . " " .$valorProducto["unidad_medida"]."<br>"); 
				      
					}							
					
				}
				echo utf8_decode("</td>");

				echo utf8_decode("<td style='border:1px solid #eee;'>");

				foreach( $productos as $key => $valorProducto){

					echo utf8_decode($valorProducto["descripcion"].""."<br>");

				}
				
				echo utf8_decode("</td>
				<td style='border:1px solid #eee;'>" . $item["total"] . "</td>");

				$cont += $item["total"];

				echo utf8_decode("</td>
				<td style='border:1px solid #eee;'>" . substr($item["fecha"], 0, 10) . "</td>		
				</tr>");
					
			}
			echo "</table>";

			echo utf8_decode("<h1> TOTAL: $ ".$cont."</h1>");
		}
	}
	/*=============================================
	SUMA TOTAL VENTAS
	=============================================*/

	public function ctrSumaTotalVentas()
	{

		$tabla = "ventas";

		$respuesta = ModeloVentas::mdlSumaTotalVentas($tabla);

		return $respuesta;
	}
}
