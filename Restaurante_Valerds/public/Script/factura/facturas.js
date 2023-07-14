$(document).ready(function () {

  if ($('#tablaFactura').length > 0) {

    $('#tablaFactura').DataTable({
      "columnDefs": [
          { "orderable": false, "targets": [6] }, //desactivar ordenamiento en columnas
          { "searchable": false, "targets": [6] } //ignorar de la busqueda
      ]
    });
    $(".trans-opacity-0").css("opacity", "1");
  }

  if ($('#tablaDetalleFactura').length > 0) {

    $('#tablaDetalleFactura').DataTable({
      "columnDefs": [
          { "orderable": false, "targets": [] }, //desactivar ordenamiento en columnas
          { "searchable": false, "targets": [] } //ignorar de la busqueda
      ],
      "paging": false,
      oLanguage: {
        sInfo: "",
        sInfoEmpty: "",
      },
    });
    $(".trans-opacity-0").css("opacity", "1");
  }

  $("#enviar").click(function (event) {
    if (!$("#formulario")[0].checkValidity()) {
        $("#formulario").find("#submit-oculto").click();
    } else {
      swal("¿Está seguro?", {
        buttons: {
          cancelar: {
            text: "No"
          },
          aceptar: {
            text: "Si",
            value: "si",
          }
        },
      })
      .then((value) => {
        switch (value) {

          case "si":
            $("#formulario").submit();
            break;

          default:
        }
        });
    }
  });
});
