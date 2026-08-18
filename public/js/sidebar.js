document.addEventListener("DOMContentLoaded", () => {
  const page = location.pathname.split("/").pop().replace(".php", "");

  const pages = {
    dashboard: "dashboard",

    item: "master",
    customer: "master",
    pic: "master",

    invoice: "sales",
    payment: "sales",
    outstanding: "sales",
    overdue: "sales",

    revenue: "reports",
    "best-seller": "reports",

    company: "admin",
    user: "admin",
  };

  document.querySelectorAll(".nav-link[data-page]").forEach((link) => {
    if (link.dataset.page === page) {
      link.classList.add("active");
      const menu = pages[page];

      if (menu) {
        const group = document.querySelector(`[data-menu="${menu}"]`);

        if (group) {
          group.classList.add("menu-open");

          group.querySelector(":scope > .nav-link").classList.add("active");
        }
      }
    }
  });
});
