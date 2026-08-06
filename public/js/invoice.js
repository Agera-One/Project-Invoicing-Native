const invoiceDate = document.getElementById("invoice_date");
const dueDate = document.getElementById("due_date");

invoiceDate.addEventListener("change", function () {
  if (!this.value) return;

  const date = new Date(this.value);

  date.setDate(date.getDate() + 7);

  const year = date.getFullYear();
  const month = String(date.getMonth() + 1).padStart(2, "0");
  const day = String(date.getDate()).padStart(2, "0");

  dueDate.value = `${year}-${month}-${day}`;
});
