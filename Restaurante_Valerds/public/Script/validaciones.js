var letras = "A-Za-z";
var numeros = "0-9";
var espacios = "\\s";
var letrasAcentos = "áéíóúÁÉÍÓÚäëïöüÄËÏÖÜ";
var puntuaciones = ".,";
var simbolos = "!#$%&'*+/=?^_`{|}~-";
var correo = "(^[a-zA-Z0-9!#$%&'*+/=?^_`{|}~-]{1,64}@)([a-zA-Z0-9-]{1,94}\\.)([a-zA-Z0-9-]{1,94}$)";

function validar(form) {
  var control = $(form).find(".control");
  var errores = true;
  var valor = "";
  var exp;

  $.each(control, function(o, elemento) {
    var error;

    if ($(elemento).parent().hasClass("input-group")) {
      error = $(elemento).parent().next(".div-error");
    } else {
      error = $(elemento).next(".div-error");
    }

    if (elemento.nodeName == "INPUT") {
      valor = $(elemento).val().trim() || "";

      if ($(elemento).attr("requerido")) {

        if (valor.length == 0) {
          $(error).text("Este espacio es requerido");
          errores = false;
          return;
        }
      }

      if ($(elemento).attr("valCorreo")) {
        exp = new RegExp(correo);

        if (valor.match(exp) == null) {
          $(error).text("El correo no es valido");
          errores = false;
          return;
        }
      }

      if ($(elemento).attr("valLetras")) {
        exp = new RegExp("^[" + letras + letrasAcentos + "]+$");

        if (valor.match(exp) == null) {
          $(error).text("Solo se aceptan letras");
          errores = false;
          return;
        }
      }

      if ($(elemento).attr("valLetrasEspacios")) {
        exp = new RegExp("^[" + letras + letrasAcentos + espacios + "]+$");

        if (valor.match(exp) == null) {
          $(error).text("Solo se aceptan letras y espacios");
          errores = false;
          return;
        }
      }

      if ($(elemento).attr("valLetrasNumeros")) {
        exp = new RegExp("^[" + letras + letrasAcentos + numeros + "]+$");

        if (valor.match(exp) == null) {
          $(error).text("Solo se aceptan letras sin acentos y números");
          errores = false;
          return;
        }
      }

      if ($(elemento).attr("valLetrasNumerosAcentos")) {
        exp = new RegExp("^[" + letras + letrasAcentos + numeros + "]+$");

        if (valor.match(exp) == null) {
          $(error).text("Solo se aceptan letras sin acentos y números");
          errores = false;
          return;
        }
      }

      if ($(elemento).attr("valLetrasAcentosNumerosPuntuacionesSimbolos")) {
        exp = new RegExp("^[" + letras + letrasAcentos + numeros + puntuaciones + simbolos + "]+$");

        if (valor.match(exp) == null) {
          $(error).text("Solo se aceptan letras, números y caracteres generales");
          errores = false;
          return;
        }
      }

      if ($(elemento).attr("valLetrasAcentosNumerosEspacioPuntuacionesSimbolos")) {
        exp = new RegExp("^[" + letras + letrasAcentos + numeros + espacios + puntuaciones + simbolos + "]+$");

        if (valor.match(exp) == null) {
          $(error).text("Solo se aceptan letras, números, espacios y caracteres generales");
          errores = false;
          return;
        }
      }

      if ($(elemento).attr("valNumerosEnteros")) {
        exp = new RegExp("^[" + numeros + "]+$");

        if (valor.match(exp) == null) {
          $(error).text("Solo se aceptan números positivos enteros");
          errores = false;
          return;
        }
      }
    }

    if (elemento.nodeName == "SELECT") {
      valor = $(elemento).val() || "0";

      if ($(elemento).attr("requerido")) {

        if (valor == "0") {
          $(error).text("Seleccione una opción");
          errores = false;
          return;
        }
      }
    }

    $(error).text("");
  });
  return errores;
}