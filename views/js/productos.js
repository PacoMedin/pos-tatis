/*===================================
CARGAR LA TABLA DINÁMICA DE PRODUCTOS
=====================================*/

//  $.ajax({
//    url: "ajax/datatable-productos.ajax.php",
//      success:function(res){
//          console.log("respuesta", res);
//      }
//  });

var perfilOculto = $("#perfilOculto").val();



 $('.tablaProductos').DataTable( {
     "ajax": "ajax/datatable-productos.ajax.php?perfilOculto="+perfilOculto,
     "deferRender": true,
     "retrieve": true,
     "processing": true,
     "language": {

        "sProcessing": "Procesando...",
        "sLengthMenu": "Mostrar _MENU_ registros",
        "sZeroRecords": "No se encontraron resultados",
        "sEmptyTable": "Ningún dato disponible en esta tabla",
        "sInfo": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_",
        "sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0",
        "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
        "sInfoPostFix": "",
        "sSearch": "Buscar:",
        "sUrl": "",
        "sInfoThousands": ",",
        "sLoadingRecords": "Cargando...",
        "oPaginate": {
            "sFirst": "Primero",
            "sLast": "Último",
            "sNext": "Siguiente",
            "sPrevious": "Anterior"
        },
        "oAria": {
            "sSortAscending": ": Activar para ordenar la columna de manera ascendente",
            "sSortDescending": ": Activar para ordenar la columna de manera descendente"
        }

    }

 } );

 $(document).ready(function() {
   
   
 
    $("#nuevaUnidad").change(function(){
        var und = $("#nuevaUnidad").val();
        $("#nuevoStock").attr("nuevaUnidad", und);

        if(und == "pz"){
            $("#nuevoStock" ).removeAttr("step");
            
        }else{
           // $("#nuevoStock" ).attr("step", "any");
            
			if($("#nuevoStock").attr("nuevaUnidad") == "m"){

				$("#nuevoStock").attr("step","0.01");

			}else if($("#nuevoStock").attr("nuevaUnidad") == "kg") {


				$("#nuevoStock").attr("step","0.001");

			}
           
            
        }

    });
    
    $("#editarUnidad").change(function(){
    var und = $("#editarUnidad").val();
    $("#editarStock").attr("editarUnidad", und);
                
    if(und == "pz"){
        $("#editarStock" ).removeAttr("step");
        
    }else{
    
        if($("#editarStock").attr("editarUnidad") == "m"){
        
            $("#editarStock").attr("step","0.01");
        
        }else if($("#editarStock").attr("editarUnidad") == "kg") {
        
        
            $("#editarStock").attr("step","0.001");
        
        }
    
        
    }
   

});
    
    
    $(".form-editar").keypress(function(e) {
        if (e.which == 13) {
            return false;
        }
    });
});


 
/*==========================================
CAPTURANDO LA CATEGORÍA PARA ASIGNAR CÓDIGO
============================================*/
// $("#nuevaCategoria").change(function(){
//     var idCategoria = $(this).val();
//     var datos = new FormData();
//     datos.append("idCategoria", idCategoria);

//     $.ajax({
//         url:"ajax/productos.ajax.php",
//         method: "POST",
//         data: datos,
//         cache: false,
//         contentType: false,
//         processData: false,
//         dataType:"json",
//         success:function(respuesta){
            
//             //console.log("respuesta", respuesta);
            
//             if(!respuesta){

//                 var nuevoCodigo = idCategoria+"01";
//                 $("#nuevoCodigo").val(nuevoCodigo);

//             }else{

//                 var nuevoCodigo = Number(respuesta["codigo"]) + 1;
//                 $("#nuevoCodigo").val(nuevoCodigo);
//             }
           


//         }
//     });

// });


/*==========================================
VAlDAR CODIGO
============================================*/
$("#nuevoCodigo").change(function(){
    var codigo = $("#nuevoCodigo").val();

    var datos = new FormData();
    datos.append("traerProductos", "ok");
    
	$.ajax({
		url:"ajax/productos.ajax.php",
      	method: "POST",
      	data: datos,
      	cache: false,
      	contentType: false,
      	processData: false,
      	dataType:"json",
      	success:function(respuesta){
			// respuesta.forEach(element =>  ``
            // console.log("FOR element> ",element);
            respuesta.forEach(element => {
                if(codigo == element["codigo"]){
                    $("#nuevoCodigo").val("");
                    $("#nuevoCodigo").focus();
                    
                    swal({
                        title: "¡Error al ingresar código!",
                        text: "El código que ingreso ya se encuentra registrado.",
                        type: "error",
                        confirmButtonText: "Cerrar"
                    });
                    
                    
                }
                
            });
            
          }
    });
  
});


/*==========================================
VAlDAR CODIGO VENTANA EDITAR
============================================*/
$("#editarCodigo").on("change",function(evento){
    $("#editarCodigo").off("keydown");

    var codigo = $("#editarCodigo").val();

    var datos = new FormData();
    datos.append("traerProductos", "ok");
    
    $.ajax({
        url:"ajax/productos.ajax.php",
          method: "POST",
          data: datos,
          cache: false,
          contentType: false,
          processData: false,
          dataType:"json",
          success:function(respuesta){
           
            respuesta.forEach(element => {

               // console.log(element["codigo"]);
                if(codigo == element["codigo"]){
                    $("#editarCodigo").val("");
                    $("#editarCodigo").focus();
                    swal({
                        title: "¡Error al ingresar código!",
                        text: "El código que ingreso ya se encuentra registrado.",
                        type: "error",
                        confirmButtonText: "Cerrar"
                    });
                    
                   
                }
                
            });
            
          }
    });
    
  
});

/*==========================================
AGREGANDO PRECIO DE VENTA
============================================*/
$("#nuevoPrecioCompra, #editarPrecioCompra").change(function(){

    if($(".porcentaje").prop("checked")){

        var valorPorcentaje = $(".nuevoPorcentaje").val();
        //console.log("Porcentaje", valorPorcentaje);
        var porcentaje =Number(($("#nuevoPrecioCompra").val() * valorPorcentaje / 100)) + Number($("#nuevoPrecioCompra").val());
        var editarPorcentaje =Number(($("#editarPrecioCompra").val() * valorPorcentaje / 100)) + Number($("#editarPrecioCompra").val());
       
       // console.log("Porcentaje", porcentaje);
        $("#nuevoPrecioVenta").val(porcentaje);
        $("#nuevoPrecioVenta").prop("readonly", true);

        $("#editarPrecioVenta").val(editarPorcentaje);
        $("#editarPrecioVenta").prop("readonly", true);
        
    }
  
});


/*==========================================
CAMBIO DE PORCENTAJE
============================================*/
$(".nuevoPorcentaje").change(function(){

    calcularPorcentaje();

});
$(".editarPorcentaje").change(function(){

    calcularPorcentaje();

});

$(".nuevoPorcentaje").keyup(function(){

        calcularPorcentaje();

});
$(".editarPorcentaje").keyup(function(){

    calcularPorcentaje();

});

$(".porcentaje").on("ifUnchecked", function(){

    $("#nuevoPrecioVenta").prop("readonly", false);
    $("#editarPrecioVenta").prop("readonly", false);
});

$(".porcentaje").on("ifChecked", function(){

    calcularPorcentaje();
    
    $("#nuevoPrecioVenta").prop("readonly", true);
    $("#editarPrecioVenta").prop("readonly", true);

});

$(".eporcentaje").on("ifUnchecked", function(){

    $("#editarPrecioVenta").prop("readonly", false);
});

$(".eporcentaje").on("ifChecked", function(){

    calcularPorcentaje();
    $("#editarPrecioVenta").prop("readonly", true);

});

function calcularPorcentaje(){
    
    if($(".porcentaje").prop("checked")){

    var valorPorcentaje = $(".nuevoPorcentaje").val();
    var porcentaje = Number(($("#nuevoPrecioCompra").val() * valorPorcentaje / 100)) + Number($("#nuevoPrecioCompra").val());

    //  console.log(editarPorcentaje);
    $("#nuevoPrecioVenta").val(porcentaje);
    $("#nuevoPrecioVenta").prop("readonly", true);


    }
    if($(".eporcentaje").prop("checked")){
        var valorEditarPorcentaje = $(".editarPorcentaje").val();
        var editarPorcentaje = Number(($("#editarPrecioCompra").val() * valorEditarPorcentaje / 100)) + Number($("#editarPrecioCompra").val());

        
    $("#editarPrecioVenta").val(editarPorcentaje);
    $("#editarPrecioVenta").prop("readonly", true);

    }
    
}



/*================================
SUBIENDO LA FOTO DEL PRODUCTO
==================================*/
$(".nuevaImagen").change(function() {

    var imagen = this.files[0];
    //console.log("imagen", imagen);
    /*===============================================
    VALIDAMOS EL FORMATO DE  LA IMAGEN SEA JPG O PNG
    =================================================*/

    if (imagen["type"] != "image/jpeg" && imagen["type"] != "image/png") {

        $(".nuevaImagen").val("");
        swal({
            title: "Error al subir imagen",
            text: "¡La imagen debe estar en formato JPG o PNG!",
            type: "error",
            confirmButtonText: "¡Cerrar!"
        });

    } else if (imagen["size"] > 2000000) {

        $(".nuevaImagen").val("");
        swal({
            title: "Error al subir imagen",
            text: "¡La imagen no debe pesar más de 2MB!",
            type: "error",
            confirmButtonText: "¡Cerrar!"
        });
    } else {
        var datosImagen = new FileReader;
        datosImagen.readAsDataURL(imagen);

        $(datosImagen).on("load", function(event) {

            var rutaImagen = event.target.result;

            $(".previsualizar").attr("src", rutaImagen);
        });
    }

});

/*================================
EDITAR PRODUCTO
==================================*/
$(".tablaProductos tbody").on("click", "button.btnEditarProducto", function(){
        var idProducto = $(this).attr("idProducto");
        // console.log("object", idProducto);
        var datos = new FormData();
        datos.append("idProducto", idProducto);
    
        $.ajax({
            url: "ajax/productos.ajax.php",
            method: "POST",
            data: datos,
            cache: false,
            contentType: false,
            processData: false,
            dataType: "json",
            success: function(respuesta) {
                //console.log("respuesta", respuesta);
                var datosCategoria = new FormData();
                datosCategoria.append("idCategoria", respuesta["id_categoria"]);

                var datosEtiqueta = new FormData();
                datosEtiqueta.append("idEtiqueta", respuesta["id_etiqueta"]);
                
                $("#editarUnidadM").html(respuesta["unidad_medida"]);
                $("#editarUnidadM").val(respuesta["unidad_medida"]);
                $("#editarStock").attr("editarUnidad",respuesta["unidad_medida"]);
                

                    var und = respuesta["unidad_medida"];
                    $("#editarStock").attr("editarUnidad", und);
                                
                    if(und == "pz"){
                        $("#editarStock" ).removeAttr("step");
                        
                    }else{
                    
                        if($("#editarStock").attr("editarUnidad") == "m"){
                        
                            $("#editarStock").attr("step","0.01");
                        
                        }else if($("#editarStock").attr("editarUnidad") == "kg") {
                        
                        
                            $("#editarStock").attr("step","0.001");
                        
                        }
                    
                        
                    }
                
                $.ajax({
                    url: "ajax/categorias.ajax.php",
                    method: "POST",
                    data: datosCategoria,
                    cache: false,
                    contentType: false,
                    processData: false,
                    dataType: "json",
                    success:function(respuesta){
                        //console.log("respuesta", respuesta);

                        $("#editarCategoria").val(respuesta["id"]);
                        $("#editarCategoria").html(respuesta["categoria"]);

                    }

                });
                $.ajax({
                    url: "ajax/etiquetas.ajax.php",
                    method: "POST",
                    data: datosEtiqueta,
                    cache: false,
                    contentType: false,
                    processData: false,
                    dataType: "json",
                    success:function(respuesta){
                        //console.log("respuesta", respuesta);
                        $("#editarEtiqueta").val(respuesta["id"]);
                        $("#editarEtiqueta").html(respuesta["etiqueta"]);

                    }

                });
                $("#editarId").val(respuesta["id"]);
                $("#editarCodigo").val(respuesta["codigo"]);
                $("#editarDescripcion").val(respuesta["descripcion"]);
                $("#editarStock").val(respuesta["stock"]);
                $("#editarPrecioCompra").val(respuesta["precio_compra"]);
                $("#editarPrecioVenta").val(respuesta["precio_venta"]);
                
    
                if (respuesta["imagen"] != "") {

                    $("#imagenActual").val(respuesta["imagen"]);
                    $(".previsualizar").attr("src", respuesta["imagen"]);
                }
            }
        });
});



/*================================
ELIMINAR PRODUCTO
==================================*/
$(".tablaProductos tbody").on("click", "button.btnEliminarProducto", function(){
    var idProducto = $(this).attr("idProducto");
    var codigo = $(this).attr("codigo");
    var imagen = $(this).attr("imagen");
    //console.log(idProducto);
    swal({
        title: '¿Está seguro de borrar el producto?',
        text: '¡Si no lo está puede cancelar la acción!',
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: '#d33',
        cancelButtonText: 'Cancelar',
        confirmButtonText: '¡Si, borrar producto!'
    }).then((result) => {
        if(result.value){
            window.location = "index.php?ruta=productos&idProducto="+idProducto+"&imagen="+imagen+"&codigo="+codigo;
        }
      
    });
});