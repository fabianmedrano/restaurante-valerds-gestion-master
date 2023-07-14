$(document).on("click", "#FiltroReporte", function() {
  var url = $("#FiltroReporte").val();
  Swal.fire({
      title: 'Selecionar filtro',
      html: '<div id="formulario">' +
        '<div class="form-group text-left">' +
        `<label class="font-weight-bold">Inicio</label>` +
        `<input type="date" id="fecha" name="fechaInicio" class="datepicker control form-control" required requerido="true"/><br>` +
        `<label class="font-weight-bold ">Final</label>` +
        `<input type="date" id="fecha" name="fechaFin" class="datepicker control form-control" required requerido="true" /><br><br>` +
        '<label class="font-weight-bold">Nombre</label>' +
        '<input name="nombre" type="text"  placeholder="Ingrese el nombre del producto o del usuario" class="control form-control">' +
        `</div>` +
        `</div>`,
      backdrop: `
               rgba(0,0,123,0.4)
               center left
               no-repeat
               overflow scroll`,
      showCloseButton: true,
      showCancelButton: true,
      confirmButtonText: 'Consultar',
      cancelButtonText: 'Cancelar',
      showLoaderOnConfirm: true,
      focusCancel: true,

      preConfirm: (valor) => {
        if (validar($(document).find("#formulario"))) {
          inicio = $(document).find("#formulario input[name=fechaInicio]").val();
          fin = $(document).find("#formulario input[name=fechaFin]").val();
          nombre = $(document).find("#formulario input[name=nombre]").val();
        } else {
          Swal.showValidationMessage('Fechas requeridas');
        }
      },
    })
    .then((result) => {
      if (result.value) {
        Swal.fire({
            type: 'success',
            title: 'Reporte generado',
            text: "El reporte se abrira en una nueva ventana",
            showConfirmButton: true,
            confirmButtonText: 'Abrir'
          })

          .then((result) => {
            if (result.value) {
              var win = window.open(url + "?inicio=" + inicio + "&fin=" + fin + "&nombre=" + nombre, "_blank");
              win.addEventListener('DOMContentLoaded', function() {
                win.history.replaceState(null, null, url); // must be same domain (or ignore domain)
              });
            }
          });
      }

    });
});

////////////////////////////////////////////////////////////////////////////////////////////////////
$(document).on("click", "#FiltroReportePlatillos", function() {
  var url = $("#FiltroReportePlatillos").val();
  Swal.fire({
      title: 'Selecionar filtro',
      html: '<div id="formulario">' +
        '<div class="form-group text-left">' +
        '<label class="font-weight-bold">Nombre</label>' +
        '<input name="nombre" type="text"  placeholder="Nombre del platillo o la palabra TODOS para busqueda general " class="control form-control" valLetrasEspacios="true">' +
        `</div>` +
        `</div>`,
      backdrop: `
               rgba(0,0,123,0.4)
               center left
               no-repeat
               overflow scroll`,
      showCloseButton: true,
      showCancelButton: true,
      confirmButtonText: 'Consultar',
      cancelButtonText: 'Cancelar',
      showLoaderOnConfirm: true,
      focusCancel: true,

      preConfirm: (valor) => {
        if (validar($(document).find("#formulario"))) {
          nombre = $(document).find("#formulario input[name=nombre]").val();
        } else {
          Swal.showValidationMessage('inserte un nombre valido');
        }
      },
    })
    .then((result) => {
      if (result.value) {
        var tex = "El reporte se abrira en una nueva ventana";

        Swal.fire({
            type: 'success',
            title: 'Reporte generado',
            text: tex,
            showConfirmButton: true,
            confirmButtonText: 'Abrir'
          })

          .then((result) => {
            if (result.value) {
              var win = window.open(url + "?nombre=" + nombre, );
              win.addEventListener('DOMContentLoaded', function() {
                win.history.replaceState(null, null, url); // must be same domain (or ignore domain)
              });
            }
          });
      }

    });
});

///////////////////////////////////////////////////////////////////////////////////////////////////////
$(document).on("click", "#FiltroReporteProductos", function() {
  var url = $("#FiltroReporteProductos").val();
  Swal.fire({
      title: 'Selecionar filtro',
      html: '<div id="formulario">' +
        '<div class="form-group text-left">' +
        '<label class="font-weight-bold">Nombre</label>' +
        '<input name="nombre" type="text"  placeholder="Nombre del producto o la palabra TODOS " class="control form-control" valLetrasEspacios="true">' +
        `</div>` +
        `</div>`,
      backdrop: `
               rgba(0,0,123,0.4)
               center left
               no-repeat
               overflow scroll`,
      showCloseButton: true,
      showCancelButton: true,
      confirmButtonText: 'Consultar',
      cancelButtonText: 'Cancelar',
      showLoaderOnConfirm: true,
      focusCancel: true,

      preConfirm: (valor) => {
        if (validar($(document).find("#formulario"))) {
          nombre = $(document).find("#formulario input[name=nombre]").val();
        } else {
          Swal.showValidationMessage('inserte un nombre valido');
        }
      },
    })
    .then((result) => {
      if (result.value) {
        var tex = "El reporte se abrira en una nueva ventana";

        Swal.fire({
            type: 'success',
            title: 'Reporte generado',
            text: tex,
            showConfirmButton: true,
            confirmButtonText: 'Abrir'
          })

          .then((result) => {
            if (result.value) {
              var win = window.open(url + "?nombre=" + nombre, );
              win.addEventListener('DOMContentLoaded', function() {
                win.history.replaceState(null, null, url); // must be same domain (or ignore domain)
              });
            }
          });
      }

    });
});


function datePicker() {
  $('#fecha').datepicker();
}