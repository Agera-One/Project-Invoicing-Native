const invoiceSelect = document.getElementById("invoice-select");
const summaryCard = document.getElementById("invoice-summary-card");
const amountInput = document.getElementById("amount-input");
const amountHint = document.getElementById("amount-hint");

const rupiah = (value) => "Rp" + Number(value).toLocaleString("id-ID");

function updateInvoiceSummary() {
  const selected = invoiceSelect.options[invoiceSelect.selectedIndex];

  if (!selected || !selected.value) {
    summaryCard.style.display = "none";
    amountHint.textContent = "";
    amountInput.removeAttribute("max");
    return;
  }

  const total = Number(selected.dataset.total || 0);
  const paid = Number(selected.dataset.paid || 0);
  const remaining = Number(selected.dataset.remaining ?? total - paid);

  document.getElementById("summary-code").textContent =
    selected.dataset.code || "-";
  document.getElementById("summary-customer").textContent =
    selected.dataset.customer || "-";
  document.getElementById("summary-date").textContent =
    selected.dataset.date || "-";
  document.getElementById("summary-due-date").textContent =
    selected.dataset.dueDate || "-";
  document.getElementById("summary-total").textContent = rupiah(total);
  document.getElementById("summary-paid").textContent = rupiah(paid);
  document.getElementById("summary-remaining").textContent = rupiah(remaining);

  summaryCard.style.display = "";

  amountHint.textContent = "Max: " + rupiah(remaining);
  amountInput.setAttribute("max", remaining);
}

invoiceSelect.addEventListener("change", updateInvoiceSummary);
updateInvoiceSummary();
