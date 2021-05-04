function showPasswordFields() {
    var x = document.getElementById("showPassword");
    var y = document.getElementById("showPassword1");
    var z = document.getElementById("showPassword2");
    if (x.type === "password") {
      x.type = "text";
      y.type = "text";
      z.type = "text";
    } else {
      x.type = "password";
      y.type = "password";
      z.type = "password";
    }
  }
