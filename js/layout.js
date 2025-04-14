document.addEventListener("DOMContentLoaded", async () => {
  if (document.getElementById("header")) {
    await loadComponent("header", "components/header.html");
  }

  if (document.getElementById("sidebar")) {
    await loadComponent("sidebar", "components/sidebar.html");
  }

  // Sidebar toggle functionality
  const sidebar = document.querySelector(".sidebar");
  const toggleBtn = document.querySelector(".toggle-btn");
  const content = document.querySelector(".main-content");

  if (sidebar && toggleBtn && content) {
    toggleBtn.addEventListener("click", () => {
      sidebar.classList.toggle("collapsed");
      content.classList.toggle("shifted");
    });
  }

  // Sidebar active state handling
  setTimeout(() => {
    const menuLinks = document.querySelectorAll(".sidebar .menu a");
    if (menuLinks.length > 0) {
      menuLinks.forEach((link) => {
        link.addEventListener("click", function () {
          menuLinks.forEach((item) => item.classList.remove("active"));
          this.classList.add("active");
          localStorage.setItem("activePage", this.getAttribute("href"));
        });
      });

      const activePage = localStorage.getItem("activePage");
      if (activePage) {
        menuLinks.forEach((link) => {
          if (link.getAttribute("href") === activePage) {
            link.classList.add("active");
          }
        });
      }
    }
  }, 100);
});

async function loadComponent(id, file) {
  try {
    const response = await fetch(file);
    if (!response.ok) {
      console.warn(
        `Component ${file} not found. This is okay if you've removed it.`
      );
      return;
    }
    const data = await response.text();
    const element = document.getElementById(id);
    if (element) {
      element.innerHTML = data;
    }
  } catch (error) {
    console.warn(`Failed to load component ${file}: ${error.message}`);
  }
}
