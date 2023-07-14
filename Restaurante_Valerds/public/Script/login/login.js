$(document).ready(function () {
  $("#visibilidad").click(function () {

    if ($("#contrasena").prop("type") === "password") {
      $("#ojo").removeClass("fa-eye-slash");
      $("#ojo").addClass("fa-eye");
      $("#contrasena").prop("type", "text");
    } else {
      $("#ojo").addClass("fa-eye-slash");
      $("#ojo").removeClass("fa-eye");
      $("#contrasena").prop("type", "password");
    }
  });
});
