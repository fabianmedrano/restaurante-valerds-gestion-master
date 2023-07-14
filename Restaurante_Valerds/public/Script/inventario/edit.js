$(document).ready(function () {

  $("#enviar").click(function (event) {

    if (validar("#formulario")) {

      Swal.fire({
        type: 'question',
        title: "Confirmar acción",
        text: "¿Actualizar producto?",
        showCloseButton: true,
        showCancelButton: true,
        confirmButtonText:
          'Actualizar',
        cancelButtonText:
          'Cancelar',
        focusCancel: true,
      })
      .then((resultado) => {

        if (resultado.value == true) {
          $("#formulario").submit();
        }
      });
    }
  });
});
