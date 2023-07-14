$(document).ready(function() {
  $(document).on("click", ".nombreAgregar", function() {
   $url = $("#ingresarNombreURL").val();
   Swal.fire({
    title: "Insertar categoría",
    html: '<div id="formulario">' +
    '<div class="form-group text-left">' +
    '<label class="font-weight-bold">Nombre</label>' +
    '<input name="nombre" type="text"  placeholder="Ingrese el nombre de la categoría" class="control form-control" requerido="true" valLetrasEspacios="true">' +
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
    confirmButtonText: 'Insertar',
    cancelButtonText: 'Cancelar',
    showLoaderOnConfirm: true,
    focusCancel: true,
    preConfirm: (valor) => {
      var cadena = $(document).find("#formulario input[name=nombre]").val(),
      nuevaCadena = cadena.trim();
      if (nuevaCadena!="") {
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
            nombre: $nombre
            },
            success: function(respuesta) {
              if(respuesta != 'false'){
                var contenido = JSON.parse(respuesta);
                contenido.forEach(function(elemento) {
                  if(elemento.nombre===nombre){
                   $fila = "<tr idCategoria='"+elemento.idCategoria+" '>"+
                   "<td class='align-middle pointer nombre' data-toggle='tooltip' data-placement='top' title='Modificar Nombre'>"+elemento.nombre+"</td"+
                   "</tr>";
                   $("#tablaCategorias tbody").append($fila);
                   
                 }
               });
                location.reload();
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
              Swal.showValidationMessage('Ha ocurrido un error al intentar insertar el nombre');
              return false;
            }
          });
        }else{
          Swal.showValidationMessage('Ha ocurrido un error al intentar insertar el nombre');
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
        'Insertado correctamente',
        '',
        'success'
        );
    }
  });
 });
});

