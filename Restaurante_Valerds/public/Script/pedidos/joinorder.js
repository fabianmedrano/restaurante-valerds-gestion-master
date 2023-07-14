$(document).ready(function () {


    /* INICIO MENSAJE UNION DE PEDIDOS DE MESA */
    $('#btn-unir').click( function () { ///  <<-- areglar esto
      swal("¿Qué desea hacer?", {

        buttons: {
          unirV: {
            text:"Unir pedidos",
            value:"unirV"
          },
          unir: {
            text:"Mezclar pedidos",
            value:"unirD"
          },
          cancelar: {
            text:"Cancelar",
            value:"cancelar" 
        },
          
        },
      })
      .then((value) => {
        switch (value) {
       
          case "unirD":
            clickUnirDos();
              break;
          case "unirV":
            clickUnirVarios();
              break;
         case "cancelar":
    
              break;
        }
      });
     /* Swal.fire({
          title: "Confirmar acción",
          text: "¿Qué desea hacer?",
          type: 'warning',
          html:
  
          '<button id="unirV" class="btn btn-primary">' +
            'Unir pedidos' +
          '</button>' +
          '<button id="unirD" class="btn btn-primary">' +
            'Mesclar dos pedidos' +
          '</button>' +
          '<button id="cancelar" class="btn btn-danger" >' +
            'Cancelar!' +
          '</button>' 
            ,
           onBeforeOpen: () => {
            const content = Swal.getContent();
            const $ = content.querySelector.bind(content);
        
            const $unirV = $('#unirV');
            const $unirD = $('#unirD');
            const $cancelar = $('#cancelar');
        
        
            $unirV.addEventListener('click', () => {
              clickUnirVarios();
              Swal.close();
            })
        
            $unirD.addEventListener('click', () => {
              clickUnirDos();
              Swal.close();
            })
        
            $cancelar.addEventListener('click', () => {
              Swal.close();
            })
          },
        })*/
    });
    /* FIN MENSAJE UNION DE PEDIDOS DE MESA */

    /* INICIO CLICK VARIOS */
  function clickUnirVarios() {
    $('#btn-continuar').toggleClass("unir-todo");
    habilitarSeleccion();
  }
    /* FIN CLICK VARIOS  */
    /* INICIO CLICK DOS */
    function clickUnirDos() {
      $("#tablaPedidos tbody tr" ).toggleClass("limitado");
      $('#btn-continuar').toggleClass("unir-dos");
      habilitarSeleccion();
    }
    /* FIN CLICK DOS  */

  /* INICIO HABILITAR SELECCION */
  function habilitarSeleccion() {
      $('#btn-unir').attr("disabled", true);
      $('#btn-unir').hide();
      $('#btn-cancelar').attr("disabled", false);
      $('#btn-cancelar').show();
      $('#btn-continuar').attr("disabled", false);
      $('#btn-continuar').show();
      $( "#tablaPedidos tbody tr" ).toggleClass("seleccionable" );
  }
  /* FIN HABILITAR SELECCION */


  /*INICIO DESBILITAR SELECCION */
  $('#btn-cancelar').click(function(){
    
    $('#btn-unir').attr("disabled", false);
    $('#btn-unir').show();
    $('#btn-cancelar').attr("disabled", true);
    $('#btn-cancelar').hide();
    $('#btn-continuar').attr("disabled", true);
    $('#btn-continuar').hide();

    $('#btn-continuar').removeClass("unir-todo");
    $('#btn-continuar').removeClass("unir-dos");
    
    $( "#tablaPedidos tbody tr" ).removeClass( "seleccionable"  );
    $( "#tablaPedidos tbody tr" ).removeClass("seleccionado" );
    $( "#tablaPedidos tbody tr" ).removeClass("limitado");
  });
  /*FIN DESBILITAR SELECCION */


  /* INICIO ACCION DE SELECCIONAR DOS ELEMENTOS */
  $("#tablaPedidos tbody tr").click( function () {
    if($(this).hasClass("seleccionable")){
      if ($(this).hasClass("seleccionado")){
        $( this ).removeClass("seleccionado" );
      }else{
        if($(this).hasClass("limitado")){
          if(contarSelectos()<2){
            $(this ).toggleClass("seleccionado" );
            // habilitar boton aceptar
          }
        }else{
          $(this ).toggleClass("seleccionado" );
        }
      }
    }
  });
  /* INICIO ACCION DE SELECCIONAR DOS ELEMENTOS */



  /* INICIO CONTAR ELEMENTOS SELECCIONADOS */
  function contarSelectos() {
      $i=0;
    $("#tablaPedidos tbody tr").each( function () {
      if($(this).hasClass("seleccionado")){
        $i++;
      }
    });
    return $i;
  }
  /*  FIN CONTAR ELEMENTOS SELECCIONADOS  */



  /*INICIO UNION PEDIDOS COMPLETOS SELECCIONADOS*/
  $('#btn-continuar').click(function(){
    if($('#btn-continuar').hasClass("unir-todo")){
      if( contarSelectos()>1){
        Swal.fire({
          title: "Confirmar acción",
          text: "¿Unir pedidos?",
          type: 'question',
          showCancelButton: true,
          cancelButtonText: "Cancelar",
          showConfirmButton: true,
          confirmButtonText: "Unir",
          showLoaderOnConfirm: true,
          focusCancel: true,
          allowOutsideClick: false,
          allowEscapeKey: false,
          preConfirm: (valor) => {
            var resultado;



            var $pedidos = []; 
            /*INICIO CARGA ID DE PEDIDOS */
            
              $("#tablaPedidos tbody tr").each( function () {
                if($(this).hasClass("seleccionado")){
                  $pedidos.push($(this).attr('id'));
                }
              });
              /*FIN CARGA ID DE PEDIDOS */
              $unirtodoURL= $("#unirtodoURL").val();
            return $.ajax({
                url: $unirtodoURL,
                type: "GET",
                async: true,
                contentType: 'application/json',
                dataType: "text",
                data: {
                  pedido: JSON.stringify($pedidos),
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
                  Swal.fire('Ha ocurrido un error al intentar unir los pedidos');
                  return false;
                }
              });

          

          },
        })     
        .then((resultado) => {

          if (resultado.value == true) {
            Swal.fire(
              'Se ha unido los pedidos correctamente',
              '',
              'success'
            );
            location.reload();
          }
        });
      }else{
        Swal.fire("No se ha seleccionado los elementos requeridos.");
      }
    }
    });   
  /*FIN  UNION PEDIDOS COMPLETOS SELECCIONADOS*/




  /*INICIO CONTINUAR CON UNION DE DOS ELEMENTOS*/
  $('#btn-continuar').click(function(){
    if($('#btn-continuar').hasClass("unir-dos")){
      if( contarSelectos()==2){
        var $url_actual = window.location;
        $url_actual = $url_actual.toString().slice(0,7+$url_actual.toString().indexOf("pedido"));
        var $idPedidos = []; 
        $c =0;
        
          $("#tablaPedidos tbody tr").each( function () {
          
            if($(this).hasClass("seleccionado")){
              $idPedidos[$c]= $(this).attr('id');
              $c++;
            }
          });
          window.location.href=$url_actual+$idPedidos[0]+"/"+$idPedidos[1]+"/join"; 
      }else{
        Swal.fire("No se ha seleccionado los elementos requeridos.");
      }
    }

  });   
  /*FIN CONTINUAR CON UNION DE DOS ELEMENTOS*/



    /* INICIO CLICK DE IZQUIERDA A DERECHA */
  $('#btn-mover-a-derecha').on('click',  function(event) {
    if($("#tabla-pedido-izquierda tr").length > 1){
      
      moverElemento($('#tabla-pedido-izquierda'), $('#tabla-pedido-derecha'));
    }else{
      Swal.fire("Solo queda un elemento");
    }
  });
  /* INICIO CLICK DE IZQUIERDA A DERECHA */


  /* INICIO CLICK DE DERECHA A IZQUIERDA  */
  $('#btn-mover-a-izquierda').on('click',  function(event) {
    if($("#tabla-pedido-derecha tr").length > 1){
      moverElemento( $('#tabla-pedido-derecha'), $('#tabla-pedido-izquierda'))
    }else{
      Swal.fire("Solo queda un elemento");
    }
    
  });
  /* INICIO CLICK DE DERECHA A IZQUIERDA */



  /* INCIO QUITAR ELEMENTO DE LA ORIGEN PARA LA DESTINO */
  function moverElemento($tablaorigen, $tabladestino){
    Swal.fire({
      title: "Confirmar acción",
      text: "¿Desea moverlo del pedido?",
      type: 'warning',
      showCloseButton: true,
      showCancelButton: true,
      confirmButtonText: 'Mover',
      cancelButtonText: 'Cancelar',
      focusCancel: true,
    })
    .then((resultado) => {

      $tablaorigen.children("tr").each(function() {
        if($(this).find('td:eq(4)').children("label").find('input').prop('checked')){
          $indice=verificacionTabla( $(this), $tabladestino);
          if(indice !=null) {
            $tabladestino.find('tr:eq('+indice+')').find('td:eq(1)').html(
            parseInt($tabladestino.find('tr:eq('+indice+')').find('td:eq(1)').html())+
            parseInt($(this).find('td:eq(1)').html())
            );
          }else{
            $tabladestino.append('<tr id="'+$(this).attr('id')+'">'+$(this).html()+'</tr>'); 
          }
          $(this).remove();
        }
      });
      deschek();
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


  /* INICIO DESELECCIONAR CHECKS */
  function deschek(){
    $('#tpedido').children("tr").each(function() {
      $(this).find('td:eq(4)').children("label").find('input').prop('checked', false); 
    });
    $('#subpedido').children("tr").each(function() {
      $(this).find('td:eq(4)').children("label").find('input').prop('checked', false); 
    });
  }
  /* FIN DESELECCIONAR CHECKS */


    /* INICIO MENSAJE GUARDAR CAMBIOS */
  $("#btn-guardar").click(function () {

    Swal.fire({
      title: "Confirmar acción",
      text: "¿Generar pedido?",
      type: 'question',
      showCancelButton: true,
      cancelButtonText: "Cancelar",
      showConfirmButton: true,
      confirmButtonText: "Guardar",
      showLoaderOnConfirm: true,
      focusCancel: true,
      allowOutsideClick: false,
      allowEscapeKey: false,
      preConfirm: (valor) => {
        var resultado;
        $idPedido1 = $('#idPedido1').val();
        $idPedido2 = $('#idPedido2').val();

        /* INICIO CARGA LISTA PLATILLOS PEDIDO */
        $pedido1 = [];
        $(' #tabla-pedido-izquierda tr').each(function () {
          $idMenu = $(this).attr('id');
          $cantidad =$(this).find('td:eq(1)').html();

          $platillo1 = {};
          $platillo1.id = $idMenu;
          $platillo1.cantidad = $cantidad;
          $pedido1.push($platillo1);

        });
        /* -------- */
        $pedido2 = [];
        $('#tabla-pedido-derecha tr').each(function () {

          $idMenu = $(this).attr('id');
          $cantidad =$(this).find('td:eq(1)').html();
          $platillo2 = {};
          $platillo2.id = $idMenu;
          $platillo2.cantidad = $cantidad;
          $pedido2.push($platillo2);
        });
        /* FIN CARGA LISTA PLATILLOS PEDIDO */

        $url = $("#guardarunionURL").val();
        return $.ajax({
            url: $url,
            type: "GET",
            async: true,
            contentType: 'application/json',
            dataType: "text",
            data: {
              pedido1: JSON.stringify($pedido1),
              pedido2: JSON.stringify($pedido2), 
              idPedido1: $idPedido1,
              idPedido2: $idPedido2
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
              Swal.fire('Ha ocurrido un error al intentar unir el pedido');
              return false;
            }
          });
      },
    })  .then((resultado) => {

      if (resultado.value == true) {
        Swal.fire(
          'Se ha unido los pedidos correctamente',
          '',
          'success'
        );
      }
    });



  });





});






