document.getElementById("userForm")
.addEventListener("submit", function (e) {
  let valid = true;

  const name = document.getElementById("name");
  const email = document.getElementById("email");
  const password = document.getElementById("password");
  const confirmPassword = document.getElementById("confirm_password");

  const nameError = document.getElementById("nameError");
  const emailError = document.getElementById("emailError");
  const passwordError = document.getElementById("passwordError");
  const confirmPasswordError = confirmPassword
    ? document.getElementById("confirmPasswordError")
    : null;

  name.classList.remove("is-invalid");
  email.classList.remove("is-invalid");
  password.classList.remove("is-invalid");
  if (confirmPassword) confirmPassword.classList.remove("is-invalid");

  nameError.textContent = "";
  emailError.textContent = "";
  passwordError.textContent = "";
  if (confirmPasswordError) confirmPasswordError.textContent = "";

  if (name.value.length > 255) {
    nameError.textContent = "Maximum name length is 255 characters.";
    name.classList.add("is-invalid");
    valid = false;
  }

  if (email.value.length > 50) {
    emailError.textContent = "Maximum email length is 50 characters.";
    email.classList.add("is-invalid");
    valid = false;
  }

  if (confirmPassword) {
    const pass = password.value;
    const confirm = confirmPassword.value;

    if (pass === "" && confirm === "") {
    } else if (pass === "" || confirm === "") {
      passwordError.textContent = "Password and Confirm Password are required.";
      confirmPasswordError.textContent =
        "Password and Confirm Password are required.";
      password.classList.add("is-invalid");
      confirmPassword.classList.add("is-invalid");
      valid = false;
    } else if (pass !== confirm) {
      confirmPasswordError.textContent =
        "Password and Confirm Password do not match.";
      password.classList.add("is-invalid");
      confirmPassword.classList.add("is-invalid");
      valid = false;
    } else if (pass.length < 8) {
      passwordError.textContent = "Password must be at least 8 characters.";
      password.classList.add("is-invalid");
      valid = false;
    }
  } else {
    if (password.value.length < 8) {
      passwordError.textContent = "Password must be at least 8 characters.";
      password.classList.add("is-invalid");
      valid = false;
    }
  }

  if (!valid) {
    e.preventDefault();
  }
});
