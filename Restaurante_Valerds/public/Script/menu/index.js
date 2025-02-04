$(document).ready(function () {
  //verificar que exista la tabla
  if ($('#tablaMenu').length > 0) {
    //Enfocar la posicion de la  columna que se usara para el agrupamiento
    var groupColumn = 4;
    table = $('#tablaMenu').DataTable({
     "columnDefs": [
         { "visible": false, "targets": groupColumn },
         { "orderable": false, "targets": [3] }, //desactivar ordenamiento en columnas
         { "searchable": false, "targets": [3] } //ignorar de la busqueda
    ],
   autoWidth: false,
   "order": [[ groupColumn, 'asc' ]],
   "displayLength": 25,
   "drawCallback": function ( settings ) {
       var api = this.api();
       var rows = api.rows( {page:'current'} ).nodes();
       var last=null;

       api.column(groupColumn, {page:'current'} ).data().each( function ( group, i ) {

           if ( last !== group ) {
               $(rows).eq( i ).before(
                 //formato de la fila de la categoria
                   '<tr data-target=".'+group+'" style="cursor: pointer; background-color: #9f50a2;" class="select-none group text-center"><td colspan="5">'+
                   '<a class="font-weight-bold text-white h4">'+ group + '</a>'
                   +'</td></tr>'
            );
            last = group;
          }
        });
      }
    });
    $(".trans-opacity-0").css("opacity", "1");
  }
  //Debido a que se actualizan los elementos de forma dimanica se tiene que hacer una seleccion directa del elemento
  //esto es porque de forma normal el elemento nuevo no existe en el documento inicial
  $(document).on('click', '.group', function (e) {
    $target = $(this).attr("data-target");

    if ($($target).css("display") == "none") {
      $($target).css("display", "table-row")
    } else {
      $($target).css("display", "none")
    }
  });

  $("#ocultarTodos").click(function () {
    $(".group").each(function () {
        $($(this).attr("data-target")).each(function () {
          $(this).css("display", "none");
        });
      });
  });

  $("#mostrarTodos").click(function () {
    $(".group").each(function () {
        $($(this).attr("data-target")).each(function () {
          $(this).css("display", "table-row")
        });
      });
  });
});
