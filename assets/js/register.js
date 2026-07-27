document.addEventListener("DOMContentLoaded", () => {
  const form = document.getElementById("wizard-form");
  const steps = form.querySelectorAll(".wizard-step");
  const indicators = document.querySelectorAll("#wizard-steps li");
  const prevBtn = document.getElementById("wz-prev");
  const nextBtn = document.getElementById("wz-next");
  const submitBtn = document.getElementById("wz-submit");
  let current = 0;

  const show = (i) => {
    steps.forEach((s, idx) => s.classList.toggle("d-none", idx !== i));
    indicators.forEach((li, idx) => {
      li.classList.toggle("active", idx === i);
      li.classList.toggle("completed", idx < i);
    });
    prevBtn.innerHTML =
      i === 0
        ? '<i class="bi bi-box-arrow-left me-1" aria-hidden="true"></i>Back to Login'
        : '<i class="bi bi-arrow-left me-1" aria-hidden="true"></i>Previous';
    const last = i === steps.length - 1;
    nextBtn.classList.toggle("d-none", last);
    submitBtn.classList.toggle("d-none", !last);
    if (last) renderSummary();
  };

  const validateStep = (i) => {
    const step = steps[i];
    const fields = step.querySelectorAll("input, select, textarea");
    let valid = true;
    fields.forEach((field) => {
      field.classList.remove("is-invalid");
      if (!field.checkValidity()) {
        field.classList.add("is-invalid");
        valid = false;
      }
    });
    if (i === 0) {
      const p1 = document.getElementById("wz-password");
      const p2 = document.getElementById("wz-password2");
      if (p1.value !== p2.value) {
        p2.classList.add("is-invalid");
        valid = false;
      }
    }
    return valid;
  };

  const renderSummary = () => {
    const summary = document.getElementById("wz-summary");
    const get = (id) => document.getElementById(id);
    const groups = [
      {
        title: "Account",
        fields: [
          {
            label: "Email",
            value: get("wz-email").value,
          },
          {
            label: "Username",
            value: get("wz-username").value,
          },
        ],
      },
      {
        title: "Company Information",
        fields: [
          {
            label: "Company Name",
            value: get("wz-company-name").value,
          },
          {
            label: "Business Entity",
            value: get("wz-business-entity").value || "—",
          },
          {
            label: "Business Sector",
            value: get("wz-business-sector").value,
          },
          {
            label: "Business Website",
            value: get("wz-business-website").value || "—",
          },
          {
            label: "Business Description",
            value: get("wz-business-description").value || "—",
            full: true,
          },
        ],
      },
      {
        title: "Company Address",
        fields: [
          {
            label: "Country",
            value: get("wz-country").value,
          },
          {
            label: "Province",
            value: get("wz-province").value,
          },
          {
            label: "City/Regency",
            value: get("wz-city").value,
          },
          {
            label: "Subdistrict",
            value: get("wz-subdistrict").value,
          },
          {
            label: "Company Address",
            value: get("wz-business-address").value,
            full: true,
          },
        ],
      },
      {
        title: "Company Contact",
        fields: [
          {
            label: "Company Email",
            value: get("wz-company-email").value,
          },
          {
            label: "Company Phone Number",
            value: get("wz-company-phone").value,
          },
        ],
      },
    ];

    summary.innerHTML = groups
      .map(
        (group, idx) => `
        <div class="col-12 ${idx < groups.length - 1 ? "mb-4 pb-4 border-bottom" : "mb-2"}">
            <h3 class="h6 text-primary text-uppercase small fw-bold mb-3">${group.title}</h3>
            <div class="row g-3">
                ${group.fields
                  .map(
                    (f) => `
                    <div class="${f.full ? "col-12" : "col-md-6"}">
                        <div class="fw-semibold mb-1">${f.label}</div>
                        <div>${f.value}</div>
                    </div>`,
                  )
                  .join("")}
            </div>
        </div>`,
      )
      .join("");
  };

  nextBtn.addEventListener("click", () => {
    if (!validateStep(current)) return;
    if (current < steps.length - 1) {
      current++;
      show(current);
    }
  });

  prevBtn.addEventListener("click", () => {
    if (current > 0) {
      current--;
      show(current);
    } else {
      window.location.href = "login.php";
    }
  });

  form.addEventListener("submit", (e) => {
    if (!validateStep(current)) {
      e.preventDefault();
      return;
    }
  });

  show(0);
});
