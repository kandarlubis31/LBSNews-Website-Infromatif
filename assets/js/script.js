document.addEventListener("DOMContentLoaded", () => {
  // Logic for Theme Toggle
  const themeToggleButton = document.getElementById("theme-toggle");
  const iconSun = document.getElementById("icon-sun");
  const iconMoon = document.getElementById("icon-moon");
  let currentTheme = localStorage.getItem("theme");

  const setTheme = (theme) => {
    document.documentElement.setAttribute("data-theme", theme);
    localStorage.setItem("theme", theme);

    // PERBAIKAN: Cek dulu apakah ikonnya ada sebelum mengubah style
    if (iconSun && iconMoon) {
      if (theme === "dark") {
        iconSun.style.display = "block";
        iconMoon.style.display = "none";
      } else {
        iconSun.style.display = "none";
        iconMoon.style.display = "block";
      }
    }
  };

  if (!currentTheme) {
    const systemPrefersDark = window.matchMedia(
      "(prefers-color-scheme: dark)"
    ).matches;
    currentTheme = systemPrefersDark ? "dark" : "light";
  }
  setTheme(currentTheme);

  if (themeToggleButton) {
    themeToggleButton.addEventListener("click", () => {
      const newTheme =
        document.documentElement.getAttribute("data-theme") === "dark"
          ? "light"
          : "dark";
      setTheme(newTheme);
    });
  }

  // Logic for Admin Sidebar Toggle
  const adminHamburger = document.getElementById("admin-hamburger");
  if (adminHamburger) {
    adminHamburger.addEventListener("click", () => {
      document.body.classList.toggle("sidebar-active");
    });
  }

  // Logic for Public Hamburger Menu
  const publicHamburger = document.querySelector(
    ".hamburger:not(#admin-hamburger)"
  );
  const mobileNav = document.querySelector(".mobile-nav");
  if (publicHamburger && mobileNav) {
    publicHamburger.addEventListener("click", () => {
      mobileNav.classList.toggle("active");
    });
  }
});
