$(document).on("click", "#modificarMesas", function() {
  var url = $("#obtenerMesasURL").val();

  Swal.fire({
    title: 'Cargando...',
    showCloseButton: true,
    onOpen: () => {
      Swal.showLoading();
      $.ajax({
        url: url,
        type: "GET",
        contentType: 'application/json',
        dataType: 'text',
        success: function(respuesta) {
          var numero = JSON.parse(respuesta);
          Swal.fire({
              title: 'Actualizar número de mesas',
              html: `
              <div id="formulario">
                <div class="form-group text-left">
                  <label class="font-weight-bold">Número</label>
                  <input name="numero" type="text" placeholder="Ingrese el número" value="` + numero + `" class="control form-control" requerido="true" valNumerosEnteros="true">
                  <div class="div-error"></div>
                </div>
              </div>`,
              showCloseButton: true,
              showCancelButton: true,
              confirmButtonText: 'Actualizar',
              cancelButtonText: 'Cancelar',
              showLoaderOnConfirm: true,
              focusCancel: true,
              preConfirm: (valor) => {

                if (validar($(document).find("#formulario"))) {
                  numero = $(document).find("#formulario input[name=numero]").val();
                  numero = parseInt(numero) || 0;
                  var resultado;

                  if (numero < 1) {
                    Swal.showValidationMessage('El número mínimo es 1');
                    return false;
                  } else if (numero > 100) {
                    Swal.showValidationMessage('El número máximo es 100');
                    return false;
                  }
                  url = $("#modificarMesasURL").val();

                  return $.ajax({
                      url: url,
                      type: "POST",
                      async: true,
                      contentType: 'application/json',
                      dataType: 'text',
                      data: JSON.stringify({
                        numero: numero,
                      }),
                      success: function(respuesta) {
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
                        Swal.showValidationMessage('Ha ocurrido un error al intentar actualizar el número de mesas');
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
                  'Se ha actualizado el número de mesas correctamente',
                  '',
                  'success'
                );
              }
            });
        },
        error: function(error) {
          Swal.fire(
            'Ha ocurrido un error al intentar actualizar el número de mesas',
            '',
            'error'
          );
        }
      });
    },
  });
});