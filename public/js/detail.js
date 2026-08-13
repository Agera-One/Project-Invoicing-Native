document.getElementById("itemDetailForm")
  .addEventListener("submit", function (e) {
    let valid = true;

    const quantity = document.getElementById("quantity");
    const unitPrice = document.getElementById("unit_price");

    const quantityError = document.getElementById("quantityError");
    const unitPriceError = document.getElementById("unitPriceError");

    quantity.classList.remove("is-invalid");
    unitPrice.classList.remove("is-invalid");

    quantityError.textContent = "";
    unitPriceError.textContent = "";

    if (Number(quantity.value) < 1) {
      quantityError.textContent = "The minimum quantity is 1.";
      quantity.classList.add("is-invalid");
      valid = false;
    } else if (Number(unitPrice.value) < 1) {
      unitPriceError.textContent = "The minimum price is 1.";
      unitPrice.classList.add("is-invalid");
      valid = false;
    }

    if (!valid) {
      e.preventDefault();
    }
  });
