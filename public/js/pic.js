document.getElementById("picForm")
  .addEventListener("submit", function (e) {
    let valid = true;

    const name = document.getElementById("name");
    const email = document.getElementById("email");
    const phone = document.getElementById("phone");
    const position = document.getElementById("position");

    const nameError = document.getElementById("nameError");
    const emailError = document.getElementById("emailError");
    const phoneError = document.getElementById("phoneError");
    const positionError = document.getElementById("positionError");

    name.classList.remove("is-invalid");
    email.classList.remove("is-invalid");
    phone.classList.remove("is-invalid");
    position.classList.remove("is-invalid");

    nameError.textContent = "";
    emailError.textContent = "";
    phoneError.textContent = "";
    positionError.textContent = "";

    if (name.value.length > 255) {
      nameError.textContent = "Maximum name length is 255 characters.";
      name.classList.add("is-invalid");
      valid = false;
    }

    if (email.value.length > 50) {
      emailError.textContent = "Maximum Email length is 50 characters.";
      email.classList.add("is-invalid");
      valid = false;
    }

    if (phone.value.length > 20) {
      phoneError.textContent = "Maximum phone length is 20 characters.";
      phone.classList.add("is-invalid");
      valid = false;
    }

    if (position.value.length > 255) {
      positionError.textContent = "Maximum position length is 255 characters.";
      position.classList.add("is-invalid");
      valid = false;
    }

    if (!valid) {
      e.preventDefault();
    }
  });
