$(document).ready(function () {

  if ($('#tablaUsuarios').length > 0) {
      $('#tablaUsuarios').DataTable({
        "columnDefs": [
            { "orderable": false, "targets": [5] }, //desactivar ordenamiento en columnas
            { "searchable": false, "targets": [5] } //ignorar de la busqueda
        ],
        autoWidth: false,
      });
      $(".trans-opacity-0").css("opacity", "1");
  }
});
