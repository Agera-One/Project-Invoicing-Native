document.getElementById("customerForm")
  .addEventListener("submit", function (e) {
    let valid = true;

    const name = document.getElementById("name");
    const email = document.getElementById("email");
    const phone = document.getElementById("phone");
    const address = document.getElementById("address");

    const nameError = document.getElementById("nameError");
    const emailError = document.getElementById("emailError");
    const phoneError = document.getElementById("phoneError");
    const addressError = document.getElementById("addressError");

    name.classList.remove("is-invalid");
    email.classList.remove("is-invalid");
    phone.classList.remove("is-invalid");
    address.classList.remove("is-invalid");

    nameError.textContent = "";
    emailError.textContent = "";
    phoneError.textContent = "";
    addressError.textContent = "";

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

    if (address.value.trim() === "") {
      addressError.textContent = "Address is required.";
      address.classList.add("is-invalid");
      valid = false;
    }

    if (!valid) {
      e.preventDefault();
    }
  });
