$(document).on("click", ".eliminar", function() {

  Swal.fire({
      title: "Confirmar acción",
      text: "¿Descartar platillo del pedido?",
      type: 'warning',
      showCloseButton: true,
      showCancelButton: true,
      confirmButtonText: 'Descartar',
      cancelButtonText: 'Cancelar',
      focusCancel: true,
    })
    .then((resultado) => {

      if (resultado.value == true) {
        $(this).parent().parent().remove();
        $("#tablaPedido tbody").change();
      }
    });
});

$(document).on('keydown', '.cantidadCont input', function(e) {
  validarNumerosSinCero(e, this);
});

$(document).on('keydown', ".cantidadTabla", function(e) {
  validarNumeros(e, this);
});

$(document).on("input", ".cantidadCont input", function(e) {

  if ($(this).val() > 100) {
    $(this).val(100);
  }
});

var ellipsisC = false;
$(document).ready(function() {

  if (ellipsisC == false) {
    createEllipsis(".texto");
    createEllipsis(".nombre");
    ellipsisC = true;
  }

  $("#tablaPedido tbody").change(function() {

    if ($("#tablaPedido tbody tr").length > 0) {
      $("#verPedido").attr("disabled", false);
      $("#finalizar").attr("disabled", false);
    } else {
      $("#verPedido").attr("disabled", true);
      $("#finalizar").attr("disabled", true);
      $(".modalC").animate({
        'opacity': '0'
      }, 300, function() {
        $(".modalC").css("display", "none");
      });
    }
  });

  $("#mostrarCategorias .dropdown-item").click(function() {
    $('html, body').animate({
      scrollTop: $("#categoria" + $(this).attr("id")).offset().top
    }, 'fast');
  });

  $("#limpiarPedido").click(function() {

    Swal.fire({
        title: "Confirmar acción",
        text: "¿Descartar todos los platillos en el pedido?",
        type: 'warning',
        showCloseButton: true,
        showCancelButton: true,
        confirmButtonText: 'Descartar',
        cancelButtonText: 'Cancelar',
        focusCancel: true,
      })
      .then((resultado) => {

        if (resultado.value == true) {
          $("#tablaPedido tbody tr").remove();
          $("#tablaPedido tbody").change();
        }
      });
  });

  $(".columnaPlatillo").click(function() {
    $idPlatillo = $(this).attr("id");
    $mismoPlatillo = $("#tablaPedido tbody [idPlatilloTabla='" + $idPlatillo + "']");
    var cantidadAnt = 1;

    if ($mismoPlatillo.length > 0) {
      cantidadAnt = $($mismoPlatillo).find(".cantidadTabla").text();
    }

    Swal.fire({
        title: "Agregar al pedido",
        html: '<div id="formulario">' +
          '<div class="form-group text-left">' +
          '<label class="font-weight-bold">Cantidad</label>' +
          '<input name="cantidad" type="text" placeholder="Ingrese la cantidad" value="' + cantidadAnt + '" class="control form-control" requerido="true" valNumerosEnteros="true">' +
          '<div class="div-error"></div>' +
          '</div>' +
          '</div>',
        showCloseButton: true,
        showCancelButton: true,
        confirmButtonText: 'Agregar',
        cancelButtonText: 'Cancelar',
        showLoaderOnConfirm: true,
        focusCancel: true,
        preConfirm: (valor) => {

          if (validar($(document).find("#formulario"))) {
            cantidad = $(document).find("#formulario input[name=cantidad]").val();
            cantidad = parseInt(cantidad) || 0;

            if (cantidad < 1) {
              Swal.showValidationMessage('La cantidad mínima es 1');
              return false;
            } else if (cantidad > 100) {
              Swal.showValidationMessage('La cantidad máxima es 100');
              return false;
            }
            $idPlatillo = $(this).attr("id");
            $nombre = $(this).find(".nombre p").text();
            $mismoPlatillo = $("#tablaPedido tbody [idPlatilloTabla='" + $idPlatillo + "']");

            if ($mismoPlatillo.length == 0) {
              $fila = "<tr idPlatilloTabla='" + $idPlatillo + "'>" +
                "<td class='align-middle nombreTabla'>" + $nombre + "</td>" +
                "<td class='align-middle pointer cantidadTabla' title='Cambiar cantidad'>" + cantidad + "</td>" +
                "<td class='align-middle text-center' title='Descartar del pedido' ><i class='eliminar text-danger far fa-minus-square'></i></td>" +
                "</tr>";
              $("#tablaPedido tbody").append($fila);
              $("#tablaPedido tbody").change();
            } else {
              $($mismoPlatillo).find(".cantidadTabla").text(parseInt(cantidad));
            }
            return true;
          } else {
            return false;
          }
        },
      })
      .then((resultado) => {

        if (resultado.value == true) {

          Swal.fire({
            toast: true,
            type: 'success',
            title: 'Agregado correctamente',
            position: 'bottom-start',
            showConfirmButton: false,
            timer: 5000,
          });
        }
      });
  });

  $("#cerrarPrevPedido").click(function() {
    $(".modalC").animate({
      'opacity': '0'
    }, 300, function() {
      $(".modalC").css("display", "none");
    });
  });

  $("#verPedido").click(function() {

    if ($("#tablaPedido tbody tr").length > 0) {
      $("#mesa .dropdown-menu .dropdown-item")[0].click();

      if ($(".modalC").css("display") == "none") {
        $(".modalC").css("display", "flex");
        $(".modalC").animate({
          'opacity': '1'
        }, 300);
      }
    }
  });

  $(".modalC").click(function(e) {

    if ($(e.target).hasClass("modalC") == true) {

      $(".modalC").animate({
        'opacity': '0'
      }, 300, function() {
        $(".modalC").css("display", "none");
      });
    }
  });

  $("#finalizar").click(function() {
    $mesa = $("#mesa .dropdown-menu .dropdown-item.seleccionado").attr("value");

    if ($mesa == 0) {
      Swal.fire({
        toast: true,
        type: 'error',
        title: "No se ha seleccionado la mesa",
        position: 'bottom-start',
        showConfirmButton: false,
        timer: 5000,
      });
    } else {

      if ($("#tablaPedido tbody tr").length > 0) {
        $platillos = $("#tablaPedido tbody tr");
        $url = $("#crearPedidoURL").val();

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
              //logica
              $platillos = $("#tablaPedido tbody tr");
              $pedido = [];

              $($platillos).each(function() {
                $id = $(this).attr("idPlatilloTabla");
                $cantidad = $(this).find(".cantidadTabla").text();

                $platillo = {};
                $platillo.id = $id;
                $platillo.cantidad = $cantidad;
                $pedido.push($platillo);
              });

              return $.ajax({
                  url: $url,
                  type: "POST",
                  async: true,
                  contentType: 'application/json',
                  dataType: "text",
                  data: JSON.stringify({
                    mesa: $mesa,
                    pedido: $pedido
                  }),
                  success: function(respuesta, a, e) {
                    $("#tablaPedido tbody tr").remove();
                    $("#tablaPedido tbody").change();
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
                    Swal.showValidationMessage('Ha ocurrido un error al intentar generar el pedido');
                    return false;
                  }
                });
            },
          })
          .then((resultado) => {

            if (resultado.value == true) {

              Swal.fire({
                toast: true,
                type: 'success',
                title: 'Se ha generado el pedido correctamente',
                position: 'bottom-start',
                showConfirmButton: false,
                timer: 5000,
              });
            }
          });
      }
    }
  });

  $(document).on("click", ".cantidadTabla", function() {
    var cantidad = $(this).text().trim();

    Swal.fire({
        title: "Actualizar cantidad",
        html: '<div id="formulario">' +
          '<div class="form-group text-left">' +
          '<label class="font-weight-bold">Cantidad</label>' +
          '<input name="cantidad" type="text" placeholder="Ingrese la cantidad" value="' + cantidad + '" class="control form-control" requerido="true" valNumerosEnteros="true">' +
          '<div class="div-error"></div>' +
          '</div>' +
          '</div>',
        showCloseButton: true,
        showCancelButton: true,
        confirmButtonText: 'Actualizar',
        cancelButtonText: 'Cancelar',
        showLoaderOnConfirm: true,
        focusCancel: true,
        preConfirm: (valor) => {

          if (validar($(document).find("#formulario"))) {
            cantidad = $(document).find("#formulario input[name=cantidad]").val();
            cantidad = parseInt(cantidad) || 0;

            if (cantidad < 1) {
              Swal.showValidationMessage('La cantidad mínima es 1');
              return false;
            } else if (cantidad > 100) {
              Swal.showValidationMessage('La cantidad máxima es 100');
              return false;
            }
            $(this).text(cantidad);
            return true;
          } else {
            return false;
          }
        },
      })
      .then((resultado) => {

        Swal.fire({
          toast: true,
          type: 'success',
          title: "Actualizado correctamente",
          position: 'bottom-start',
          showConfirmButton: false,
          timer: 5000,
        });
      });
  });

  $("#mesa .dropdown-menu .dropdown-item").click(function() {

    if ($(this).attr("value") != 0) {
      $("#mesa .btn:first-child").html("Mesa " + $(this).attr("value"));
    } else {
      $("#mesa .btn:first-child").html("Seleccione la mesa");
    }
    $("#mesa .dropdown-menu .dropdown-item.seleccionado").removeClass("seleccionado");
    $(this).addClass("seleccionado");
  });

});

function createEllipsis(contenedores) {
  $contenedores = $(contenedores);

  $($contenedores).each(function() {
    var containerHeight = $(this).height();
    var $text = $(this).find("p");

    while ($text.outerHeight() > containerHeight) {
      $text.text(function(index, text) {
        return text.replace(/\W*\s(\S)*$/, '...');
      });
    }
  });

  if ($($contenedores).parent().css("opacity") == 0) {
    $($contenedores).parent().css("opacity", "1");
  }
}