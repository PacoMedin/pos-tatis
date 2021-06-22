/*========================
    EDITAR Etiqueta
========================*/

$(document).on("click", ".btnEditarEtiqueta",function() {
    var idEtiqueta = $(this).attr("idEtiqueta");
    var datos = new FormData();
    datos.append("idEtiqueta", idEtiqueta);

    $.ajax({

        url: "ajax/etiquetas.ajax.php",
        method: "POST",
        data: datos,
        cache: false,
        contentType: false,
        processData: false,
        dataType:"json",
        success: function(respuesta){
           //console.log("respuesta", respuesta);
           $("#editarEtiqueta").val(respuesta["etiqueta"]);
           $("#idEtiqueta").val(respuesta["id"]);
        }
     });
});

/*========================
    ELIMINAR Etiqueta
========================*/
$(document).on("click", ".btnEliminarEtiqueta", function(){

    var idEtiqueta = $(this).attr("idEtiqueta");

    swal({
        type: "warning",
        title: "¿Está seguro de borrar la etiqueta?",
        text: "¡Si no lo está puede cancelar la acción!",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: '#d33',
        cancelButtonText: 'Cancelar',
        confirmButtonText: 'Si, borrar etiqueta!',

    }).then(function(result) {
        if (result.value) {
            window.location = "index.php?ruta=etiquetas&idEtiqueta=" + idEtiqueta;

        }
    });

});