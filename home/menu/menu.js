const menuButton = document.querySelector(".menu-btn");
const menu = document.querySelector("#chapitour-menu");

menuButton.addEventListener("click", () => {
  const isOpen = menu.classList.toggle("is-open");

  menuButton.classList.toggle("is-active", isOpen);
  menuButton.setAttribute("aria-expanded", isOpen ? "true" : "false");
});

document.addEventListener("click", (event) => {
  const clickInsideMenu = menu.contains(event.target);
  const clickInsideButton = menuButton.contains(event.target);

  if (!clickInsideMenu && !clickInsideButton) {
    menu.classList.remove("is-open");
    menuButton.classList.remove("is-active");
    menuButton.setAttribute("aria-expanded", "false");
  }
});

document.addEventListener("keydown", (event) => {
  if (event.key === "Escape") {
    menu.classList.remove("is-open");
    menuButton.classList.remove("is-active");
    menuButton.setAttribute("aria-expanded", "false");
  }
});
