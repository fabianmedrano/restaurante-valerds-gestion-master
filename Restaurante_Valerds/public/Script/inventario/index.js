$(document).ready(function() {

  if ($('#tablaInventario').length > 0) {
    var cols;

    if ($('#tablaInventario colgroup col').length == 5) {
      cols = [2, 3, 4];
    } else {
      cols = [2, 3];
    }

    $('#tablaInventario').DataTable({
      "columnDefs": [{
          "orderable": false,
          "targets": cols,
        }, //desactivar ordenamiento en columnas
        {
          "searchable": false,
          "targets": cols,
        } //ignorar de la busqueda
      ],
      autoWidth: false,
    });
    $(".trans-opacity-0").css("opacity", "1");
  }

  $(document).on("click", ".stock", function() {
    var articulo = this;
    $idArticulo = $(this).parent().attr("idArticulo");
    $nombre = $(this).parent().attr("nombre");
    $url = $("#actualizarStockURL").val();

    Swal.fire({
        title: "Actualizar stock",
        html: '<div id="formulario">' +
          '<div class="form-group text-left">' +
          '<label class="font-weight-bold">Stock</label>' +
          '<input name="stock" type="text" value="' + articulo.innerText + '" placeholder="Ingrese la cantidad" class="control form-control" requerido="true" valNumerosEnteros="true">' +
          '<div class="div-error"></div>' +
          '</div>' +
          '</div>',
        backdrop: `
        rgba(0,0,123,0.4)
        center left
        no-repeat
        overflow scroll
      `,
        showCloseButton: true,
        showCancelButton: true,
        confirmButtonText: 'Actualizar',
        cancelButtonText: 'Cancelar',
        showLoaderOnConfirm: true,
        focusCancel: true,
        preConfirm: (valor) => {

          if (validar($(document).find("#formulario"))) {
            stock = $(document).find("#formulario input[name=stock]").val();
            var resultado;

            return $.ajax({
                url: $url,
                type: "POST",
                async: true,
                contentType: 'application/json',
                dataType: 'text',
                data: JSON.stringify({
                  stock: stock,
                  idArticulo: $idArticulo
                }),
                success: function(respuesta) {
                  $(articulo).attr("data-order", stock);
                  $(articulo).html(parseInt(stock));
                  resultado = true;
                },
                error: function(error) {
                  resultado = false;
                }
              })
              .then(() => {
                if (resultado == true) {
                  return true;
                } else {
                  Swal.showValidationMessage('Ha ocurrido un error al intentar actualizar el stock');
                  return false;
                }
              });
          } else {
            return false;
          }
        },
      })
      .then((resultado) => {

        if (resultado.value == true) {
          Swal.fire(
            'Actualizado correctamente',
            '',
            'success'
          );
        }
      });
  });


  $(document).on("click", "#eliminar", function() {
    var eliminar = this;

    Swal.fire({
        type: 'danger',
        title: "Confirmar acción",
        text: "¿Eliminar producto?",
        showCloseButton: true,
        showCancelButton: true,
        confirmButtonText: 'Eliminar',
        cancelButtonText: 'Cancelar',
        focusCancel: true,
      })
      .then((resultado) => {

        if (resultado.value == true) {
          $(eliminar).next().submit();
        }
      });
  });

});