$(document).ready(function() {


    /* INICIO CLICK DE QUITAR UN ELEMENTO DE ARRIBA */
    $('#btn-menos1').on('click',  function(event) {
      if(verificacionTablaCantidad( $('#tpedido') ) > 1){
        quitarUnElemento( $('#tpedido'), $('#subpedido'));
      }else{
        Swal.fire("Solo queda un elemento");
      }
        
    });
    /* FIN CLICK DE QUITAR UN ELEMENTO DE ARRIBA */


    /* INICIO CLICK DE ABAJO A ARRIBA */
    $('#btn-quitarfila-abajo').on('click',  function(event) {
        moverElemento($('#subpedido'), $('#tpedido'));
    });
    /* FIN CLICK DE ABAJO A ARRIBA */


    /* INICIO CLICK DE ARRIBA A ABAJO  */
    $('#btn-quitarfila').on('click',  function(event) {
      if($("#tpedido tr").length > 1){
        moverElemento( $('#tpedido'), $('#subpedido'));
      }else{
        Swal.fire("Solo queda un elemento");
      }
    });
    /* FIN CLICK DE ARRIBA A ABAJO */


    /* INCIO QUITAR UN ELEMENTO DE LA ORIGEN PARA LA DESTINO */
    function quitarUnElemento($tablaorigen, $tabladestino) {
      
      Swal.fire({
        title: "Confirmar acción",
        text: "¿Desea mover lo del pedido?",
        type: 'warning',
        showCloseButton: true,
        showCancelButton: true,
        confirmButtonText: 'Mover',
        cancelButtonText: 'Cancelar',
        focusCancel: true,
      })
      .then((resultado) => {
  
        if (resultado.value == true) {                
          $tablaorigen.children("tr").each(function() {   
            if($(this).find('td:eq(6)').children("label").find('input').prop('checked')){
                $indice=verificacionTabla( $(this), $tabladestino);
                if(indice !=null) {
                    // INICIO SUMA UNO ALA TABLA DESTINO
                    $tabladestino.find('tr:eq('+indice+')').find('td:eq(2)').html(
                    parseInt($tabladestino.find('tr:eq('+indice+')').find('td:eq(2)').html())+1
                    );
                    // FIN SUMA UNO ALA TABLA DESTINO
                }else{
                    $tabladestino.append('<tr id="'+$(this).attr('id')+'">'+$(this).html()+'</tr>'); 
                    $('#subpedido tr:last').find('td:eq(2)').html(1);
                }
                    // INICIO RESTA UNO ALA TABLA ORIGEN
                    $(this).find('td:eq(2)').html( // no esta sirviendo
                        parseInt($(this).find('td:eq(2)').html())-1
                    );
                    // FIN RESTA UNO ALA TABLA ORIGEN
  
                    // INICIO VERIFICACION QUE LA CANTIDAD NO SEA CERO
                    if (parseInt($(this).find('td:eq(2)').html()) == 0) {
                        parseInt($(this).remove())
                    }
                    // FIN VERIFICACION QUE LA CANTIDAD NO SEA CERO
            }
        });
  
        deschek();
        }
      });
    }
    /* FIN QUITAR UN ELEMENTO DE LA ORIGEN PARA LA DESTINO */


/* INCIO QUITAR ELEMENTO DE LA ORIGEN PARA LA DESTINO */
  function moverElemento($tablaorigen, $tabladestino){
    
    Swal.fire({
      title: "Confirmar acción",
      text: "¿Desea mover lo del pedido?",
      type: 'warning',
      showCloseButton: true,
      showCancelButton: true,
      confirmButtonText: 'Mover',
      cancelButtonText: 'Cancelar',
      focusCancel: true,
    })
    .then((resultado) => {

      if (resultado.value == true) {
        $tablaorigen.children("tr").each(function() {   
          if($(this).find('td:eq(6)').children("label").find('input').prop('checked')){
            $indice=verificacionTabla( $(this), $tabladestino);
            if(indice !=null) {
              $tabladestino.find('tr:eq('+indice+')').find('td:eq(2)').html(
              parseInt($tabladestino.find('tr:eq('+indice+')').find('td:eq(2)').html())+
              parseInt($(this).find('td:eq(2)').html())
              );
            }else{
              $tabladestino.append('<tr id="'+$(this).attr('id')+'">'+$(this).html()+'</tr>'); 
            }
            $(this).remove();
          }
        });
        deschek();
      }
    });
  }
  /* FIN QUITAR ELEMENTO DE LA ORIGEN PARA LA DESTINO */


/* INICIO VERIFICAR SI EL ELEMENTO DE ORIGEN EXISTE EN LA TABLA DESTINO */
  function verificacionTabla( $elemento, $tabla ){
    indice= null;
    $tabla.children('tr').each(function() {
        
      if(parseInt($elemento.attr('id')) == $(this).attr('id')){
        indice = $('tr', $(this).closest("table")).index(this)-1; 
      }
    });
    return indice;
  };
  /* FIN VERIFICAR SI EL ELEMENTO DE ORIGEN EXISTE EN LA TABLA DESTINO */


  
/* INICIO VERIFICAR CANTIDAD TABLA */
function verificacionTablaCantidad( $tabla ){
  total= 0;
  $tabla.children('tr').each(function() {
    total =parseInt($(this).find('td:eq(2)').html())+ total;
  });
  return total;
};
/* FIN VERIFICAR CANTIDAD TABLA */

   /* INICIO DESELECCIONAR CHECKS */
    function deschek() {
      $('#tpedido').children("tr").each(function() {
        $(this).find('td:eq(6)').children("label").find('input').prop('checked', false);
      });
      $('#subpedido').children("tr").each(function() {
        $(this).find('td:eq(6)').children("label").find('input').prop('checked', false);
      });
    }
    /* FIN DESELECCIONAR CHECKS */


    $("#guardar").click(function() {
  
      if ($("#subpedido  tr").length > 0) {



        Swal.fire({
          title: "Confirmar acción",
          text: "¿Generar pedido?",
          type: 'question',
          showCancelButton: true,
          cancelButtonText: "Cancelar",
          showConfirmButton: true,
          confirmButtonText: "Generar",
          showLoaderOnConfirm: true,
          focusCancel: true,
          allowOutsideClick: false,
          allowEscapeKey: false,
          preConfirm: (valor) => {
            var resultado;
            $platillos = $("#subpedido tr");
            $mesa = $("#mesa").val();
            $idPedido = $('#idPedido').val();
            $pedido = [];

            $($platillos).each(function() {

              $idMenu = $(this).find('td:eq(4)').html();
              $cantidad = $(this).find('td:eq(2)').html();

              $platillo = {};
              $platillo.id = $idMenu;
              $platillo.cantidad = $cantidad;
              $pedido.push($platillo);
            });

            $url = $("#guardarsubURL").val();
            return $.ajax({
                url: $url,
                type: "GET",
                async: true,
                contentType: 'application/json',
                dataType: "text",
                data: {
                  pedido: JSON.stringify($pedido),
                  mesa: $mesa,
                  idPedido: $idPedido
                },
                success: function(respuesta, a, e) {
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
                  Swal.fire('Ha ocurrido un error al intentar dividir el pedido');
                  return false;
                }
              });
          },
        })         
        .then((resultado) => {

          if (resultado.value == true) {
            Swal.fire(
              'Se ha dividido el pedido correctamente',
              '',
              'success'
            );
            var $url_actual = window.location;
            $url_actual = $url_actual.toString().slice(0,7+$url_actual.toString().indexOf("pedido"));
            window.location.href =  $url_actual+ $('#idPedido').val() + "/edit";
          }
        });


      }
    });
  });
  
  

  