$(document).ready(function() {


  
    /* INICIO FACTURAR PEDIDO */


    ////////////////////////////////////////////////////////////////////////

    
  $('#btn-facturar').on('click', function(event) {
   
    if($('#nombre-cliente').val().trim().length > 0){
    Swal.fire({
      title: "Confirmar acción",
      text: "¿Desea facturar este pedido?",
      type: 'question',
      showCancelButton: true,
      cancelButtonText: "Cancelar",
      showConfirmButton: true,
      confirmButtonText: "Facturar",
      showLoaderOnConfirm: true,
      focusCancel: true,
      allowOutsideClick: false,
      allowEscapeKey: false,
      preConfirm: (valor) => {
        var resultado;

        var $url_actual = window.location;
        $url_actual = $url_actual.toString().slice(0,7+$url_actual.toString().indexOf("pedido"));
      
        $idPedido =$('#idPedido').val();
        $nombre =$('#nombre-cliente').val();// validar campo
 
        return $.ajax({
            url:  $url_actual+"facturacion",
            type: "GET",
            async: true,
            contentType: 'application/json',
            dataType: "text",
            data: {
              nombre: $nombre,
              pedi: $idPedido
            },
            success: function(respuesta, a, e) {
              $("#tablaPedido tbody tr").remove();
              $("#tablaPedido tbody").change();
              resultado = true;
            },
            error: function(error) {
              resultado = false;
            }
          })
          .then(() => {

            if (resultado == true) {
              // mensaje de exito
              var $url_actual = window.location;
              $url_actual = $url_actual.toString().slice(0,7+$url_actual.toString().indexOf("pedido")); 
              window.location.href =  $url_actual+ $('#mesa').val() + "/tableorders";
            } else {
              Swal.fire('Ha ocurrido un error al facturar el pedido');
              return false;
            }
          });
        },
      })
      .then((resultado) => {

        if (resultado.value == true) {
          Swal.fire(
            'Se ha facturado el pedido correctamente',
            '',
            'success'
          );
        }
      });
    }else{
      Swal.fire("Ingrese el nombre del cliente");
    }
  });
    /* FIN FACTURAR PEDIDO */

});
function eliminar(url) {
  Swal.fire({
    title: "Confirmar acción",
    text: "¿Está seguro que lo desea eliminar?",
    type: 'warning',
    showCloseButton: true,
    showCancelButton: true,
    confirmButtonText: 'Eliminar',
    cancelButtonText: 'Cancelar',
    focusCancel: true,
  })
  .then((resultado) => {
    if (resultado.value == true) {
      location.href = url;
    }
  });

}