$(document).ready(function () {
  $("#reg-pass").attr("type", "password");
  $("#log-pass").attr("type", "password");
  $("#reg-confi-pass").attr("type", "password");

  $("#reg-pass-show").on("click", function () {
    var passInput = $("#reg-pass");
    var icon = $("#visibility-icon-pass");
    if (passInput.attr("type") === "password") {
      passInput.attr("type", "text");
      icon.text("visibility_off");
    } else {
      passInput.attr("type", "password");
      icon.text("visibility");
    }
  });
  $("#log-pass-show").on("click", function () {
    var passInput = $("#log-pass");
    var icon = $("#visibility-icon-pass");
    if (passInput.attr("type") === "password") {
      passInput.attr("type", "text");
      icon.text("visibility_off");
    } else {
      passInput.attr("type", "password");
      icon.text("visibility");
    }
  });
  $("#reg-confi-pass-show").on("click", function () {
    var passInput = $("#reg-confi-pass");
    var icon = $("#visibility-icon-confi-pass");
    if (passInput.attr("type") === "password") {
      passInput.attr("type", "text");
      icon.text("visibility_off");
    } else {
      passInput.attr("type", "password");
      icon.text("visibility");
    }
  });
  $("#registo-form").submit(function (event) {
    event.preventDefault();

    var email = $("#reg-email").val();
    var username = $("#reg-username").val();
    var password = $("#reg-pass").val();
    var confi_pass = $("#reg-confi-pass").val();

    $.ajax({
      type: "POST",
      url: "views/post_views/post_registo.php",
      data: {
        email: email,
        username: username,
        password: password,
        confi_pass: confi_pass,
      },
      dataType: "html",
      success: function (response) {
        $("#alert-container").html(response);
      },
    });
  });
  $("#login-form").submit(function (event) {
    event.preventDefault();

    var username = $("#log-username").val();
    var password = $("#log-pass").val();
    if ($("#log-manter-sessao").is(":checked")) {
      var manter = 1;
    } else {
      var manter = 0;
    }
    $.ajax({
      type: "POST",
      url: "views/post_views/post_login.php",
      data: {
        username: username,
        password: password,
        manter: manter,
      },
      dataType: "html",
      success: function (response) {
        $("#alert-container").html(response);
      },
    });
  });
  $("#aceitar-privacidade").change(function () {
    if ($(this).is(":checked")) {
      $("#submit-reg").prop("disabled", false);
    } else {
      $("#submit-reg").prop("disabled", true);
    }
  });
});
