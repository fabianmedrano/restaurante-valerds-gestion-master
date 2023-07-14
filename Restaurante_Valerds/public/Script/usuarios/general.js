$(document).ready(function () {

  $(document).ready(function () {
    $("#visibilidad").click(function () {

      if ($("#usuarios_contrasena").prop("type") === "password") {
        $("#ojo").removeClass("fa-eye-slash");
        $("#ojo").addClass("fa-eye");
        $("#usuarios_contrasena").prop("type", "text");
      } else {
        $("#ojo").addClass("fa-eye-slash");
        $("#ojo").removeClass("fa-eye");
        $("#usuarios_contrasena").prop("type", "password");
      }
    });
  });
});
