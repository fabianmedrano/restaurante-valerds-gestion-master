$(document).ready(function() {

  if ($('#tablaCategorias').length > 0) {

    $('#tablaCategorias').DataTable({
      "columnDefs": [{
        "orderable": false,
        "targets": [0]
        }, //desactivar ordenamiento en columnas
        {
          "searchable": false,
          "targets": [0]
        } //ignorar de la busqueda
        ],
        autoWidth: false,
      });
    $(".trans-opacity-0").css("opacity", "1");
  }

  $(document).on("click", ".nombre", function() {
    var categoria = this;
    $idCategoria = $(this).parent().attr("idCategoria");
    $url = $("#actualizarNombreURL").val();

    Swal.fire({
      title: "Actualizar categoría",
      html: '<div id="formulario">' +
      '<div class="form-group text-left">' +
      '<label class="font-weight-bold">Categoría</label>' +
      '<input name="nombre" type="text" value="' + categoria.innerText + '" placeholder="Ingrese la categoría" class="control form-control" requerido="true" valLetrasEspacios="true">' +
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
        var cadena = $(document).find("#formulario input[name=nombre]").val(),
        nuevaCadena = cadena.trim();
        if(nuevaCadena!=""){
        if (validar($(document).find("#formulario"))) {
          nombre = $(document).find("#formulario input[name=nombre]").val();
          $nombre=nombre.trim();
          var resultado;
          return $.ajax({
            url: $url,
            type: "GET",
            async: true,
            contentType: 'application/json',
            dataType: 'text',
            data: {
              nombre: $nombre,
              idCategoria: $idCategoria
            },
            success: function(respuesta) {
         
             if(respuesta == 'true'){
               $(categoria).attr("data-order", nombre);
               $(categoria).html(nombre);
               resultado = true;
             }else{
              resultado=false;
            }
          },
          error: function(error) {
            resultado = false;
          }
        })
          .then(() => {
            if (resultado == true) {
              return true;
            } else {
              Swal.showValidationMessage('Ha ocurrido un error al intentar actualizar el nombre');
              return false;
            }
          });
        } else {
          return false;
        }
      } else {
        Swal.showValidationMessage('Se deben ingresar datos, para insertar la categoría');
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
});
