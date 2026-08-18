document.querySelectorAll(".custom-file-input").forEach(function (input) {
  input.addEventListener("change", function () {
    let fileName = this.files[0]?.name || "Choose File";
    this.nextElementSibling.innerHTML = fileName;
  });
});
