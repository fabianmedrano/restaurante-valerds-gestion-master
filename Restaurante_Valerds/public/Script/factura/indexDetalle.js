$(document).on("click", ".detalle", function() {
  $url = $("#URLFacturaDetalle").val();//Aqui es donde obtengo la url
  $factura = $(this);
  $idFactura = $(this).parent().attr("idFactura");

  Swal.fire({
  	title: 'Cargando...',
  	showCloseButton: true,
  	onOpen: () => {
  		Swal.showLoading();
  		$.ajax({
  			url:  $url,
  			type: "GET",
  			contentType: 'application/json',
  			dataType: 'text',
  			data:{"factura":$idFactura},
  			success: function(respuesta) {
  				if(respuesta != 'false'){
  					Swal.fire({
  						title: "Detalle factura",
  						html: '<div id="formulario ">' +
  						'<div class="form-group text-left">' +
  						' <div id="encabezado">'+
  						'</div>'+
  						'<tr name="idfactura" '+ $idFactura+'">'+
              '<div class="table-responsive ">'+
              '<table id="table_factura" class="table table-bordered table-hover">'+
              '<thead>'+
              '<tr>'+
              '<th style="text-align:center;">Cantidad</th>'+
              '<th style="text-align:center;">Platillo</th>'+
              '<th style="text-align:center;">Precio Unidad</th>'+
              '<th style="text-align:center;">SubTotal</th>'+
              '</tr>'+
              '</thead>'+
              '<tbody id="content_table">'+
              '<tr>'+
              '<td></td>'+
              '<td></td>'+
              '<td></td>'+
              '<td></td>'+
              '</tr>'+
              '</tbody>'+
              '</table>'+
              '</div>'+
              ' <div id="footer">'+
              '</div>'+
              '</div>',
              showCancelButton: false,
              showConfirmButton: true,
              confirmButtonText: "Cerrar",
              showCloseButton: true,

            })

  					var contenidoTotal = JSON.parse(respuesta);
  					contenidoTotal[1].forEach(function(elementoTotal) {
  						$("#footer").append("<h5 align='right'>"+"Total ₡"+(new Intl.NumberFormat("en-IN").format(elementoTotal.total))+'.00'+"</h5>"); 
  					});

  					var name_table=document.getElementById('table_factura');
  					var contenido = JSON.parse(respuesta);
  					$("#encabezado").append("<h5 class='modal-title'>"+"NºFactura: "+contenido[0][0].id_factura+"</h5>"); 
  					$("#encabezado").append("<h5 class='modal-title'>"+"Fecha: "+(contenido[0][0].fecha)+"</h5>");
  					$("#encabezado").append("<h5 class='modal-title'>"+"Cajero: "+contenido[0][0].nombreCajero+"</h5>");
  					$("#encabezado").append("<h5 class='modal-title'>"+"Cliente: "+contenido[0][0].nombreCliente+"</h5>");
  					$("#encabezado").append("<h5 class='modal-title'>"+"Mesa: "+contenido[0][0].numeroMesa+"</h5>");

  					contenido[0].forEach(function(elemento) {

  						var row= name_table.insertRow(0+1);
  						var cell1=row.insertCell(0);
  						var cell2=row.insertCell(1);
  						var cell3=row.insertCell(2);
  						var cell4=row.insertCell(3);


  						cell1.innerHTML="<p>"+elemento.cantidadProducto+"</p>";
  						cell2.innerHTML="<p>"+elemento.nombre+"</p>";
  						cell3.innerHTML="<p>"+'₡'+(new Intl.NumberFormat("en-IN").format(elemento.precioProducto))+'.00'+"</p>";
  						cell4.innerHTML="<p>"+'₡'+(new Intl.NumberFormat("en-IN").format(elemento.subTotal))+'.00'+"</p>";


  					});
  				}else{
  					resultado=false;

          }
        },
        error: function(error) {
          Swal.fire(
            'Ha ocurrido un error al intentar mostrar el detalle de la factura',
            '',
            'error'
            );
        }
      });
  	},
  });
});
