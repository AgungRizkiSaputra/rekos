// Toggle Mobile Menu
document.getElementById("menu-button").addEventListener("click", function () {
  const mobileMenu = document.getElementById("mobile-menu");
  if (mobileMenu.classList.contains("hidden")) {
    mobileMenu.classList.remove("hidden", "opacity-0", "scale-y-0");
    mobileMenu.classList.add("opacity-100", "scale-y-100");
  } else {
    mobileMenu.classList.add("opacity-0", "scale-y-0");
    setTimeout(() => mobileMenu.classList.add("hidden"), 300);
  }
});

// Smooth scrolling for anchor links
document.querySelectorAll('a[href^="#"]').forEach((anchor) => {
  anchor.addEventListener("click", function (e) {
    e.preventDefault();

    const targetId = this.getAttribute("href");
    if (targetId === "#") return;

    const targetElement = document.querySelector(targetId);
    if (targetElement) {
      window.scrollTo({
        top: targetElement.offsetTop - 80,
        behavior: "smooth",
      });
    }
  });
});

// Animasi rotasi saat hover menu button
const menuButton = document.getElementById("menu-button");

menuButton.addEventListener("mouseenter", () => {
  menuButton.classList.add("rotate-90", "transition-transform", "duration-300");
});

menuButton.addEventListener("mouseleave", () => {
  menuButton.classList.remove("rotate-90");
});

// Efek scroll navbar
window.addEventListener("scroll", function () {
  const navbar = document.getElementById("navbar");
  if (window.scrollY > 50) {
    navbar.classList.add("shadow-md", "py-3");
    navbar.classList.remove("py-4");
  } else {
    navbar.classList.remove("shadow-md", "py-3");
    navbar.classList.add("py-4");
  }
});
