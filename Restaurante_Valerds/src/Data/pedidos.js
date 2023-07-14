$(document).ready(function() {


  
    /* INICIO FACTURAR PEDIDO */
  $('#btn-facturar').on('click', function(event) {
    var $url_actual = window.location;
    $url_actual = $url_actual.toString().slice(0,7+$url_actual.toString().indexOf("pedido"));

    if($('#nombre-cliente').val().length > 0){
      swal({
          title: "¿Desea facturar este pedido?",
          icon: "warning",
          buttons: {
            cancelar: {
              text: "No",
              className: "swal-button--cancel",
              value: false,
            },
            aceptar: {
              text: "Si",
              value: "si",
              value: true,
            }
          },
          dangerMode: true,
          closeOnEsc: false,
          closeOnClickOutside: false,
        })
        .then((crear) => {
          
          if (crear) {
            var $url_actual = window.location;
            $url_actual = $url_actual.toString().slice(0,7+$url_actual.toString().indexOf("pedido"));
          //   window.location.href =  $url_actual+ $('#idPedido').val() + "/facturacion";
          
            $idPedido =$('#idPedido').val();
            $nombre =$('#nombre-cliente').val();// validar campo
            return $.ajax({
              url:   $url_actual+"facturacion",
              type: "GET",
              async: true,
              contentType: 'application/json',
              dataType: 'text',
              data: {
                nombre: $nombre,
                pedi: $idPedido
              },
              success: function(respuesta) {
                return true;
              },
              error: function(error) {
                return false;
              }
            });
          }
          return null;
        })
        .then((resultado) => {

          if (resultado != null) {

            if (resultado) {
              swal("Se ha facturado el pedido.", "", "success");
              var $url_actual = window.location;
              $url_actual = $url_actual.toString().slice(0,7+$url_actual.toString().indexOf("pedido")); 
              window.location.href =  $url_actual+ $('#mesa').val() + "/tableorders";
            } else {
              swal("Ha ocurrido un error al intentar facturar el pedido,", "", "error");
            }
          }
        })
        .catch(err => {
          if (err) {
            swal("Ha ocurrido un error al intentar facturar el pedido", "", "error");
          } else {
            swal.stopLoading();
            swal.close();
          }
        });
      }else{
        swal("Ingrese el nombre del cliente");
      }
  });
    /* FIN FACTURAR PEDIDO */
});

function eliminar(url) {
  swal("Está seguro que desea eliminar?", {
      buttons: {
        cancelar: {
          text: "Cancelar!",
          value: "cancel",
        },
        aceptar: {
          text: "Eliminar!",
          value: "delete",
        }
      },
    })
    .then((value) => {
      switch (value) {

        case "delete":
          location.href = url;
          return true;
        case "cancelar":
          return false;
      }
      return false;
    });
}