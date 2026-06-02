class CategoryFilter {
  constructor() {
    this.filterItems = document.querySelectorAll(".filter-list li");
    this.cards = document.querySelectorAll(".place-card");

    this.initFilterEvents();
  }

  initFilterEvents() {
    this.filterItems.forEach((item) => {
      item.addEventListener("click", () => {
        const selectedFilter = item.getAttribute("data-filter");

        this.removeActiveClass();
        item.classList.add("active");

        this.filterCards(selectedFilter);
      });
    });
  }

  removeActiveClass() {
    this.filterItems.forEach((item) => {
      item.classList.remove("active");
    });
  }

  filterCards(selectedFilter) {
    this.cards.forEach((card) => {
      const cardCategory = card.getAttribute("data-category");

      if (selectedFilter === "all" || selectedFilter === cardCategory) {
        card.classList.remove("hide");
      } else {
        card.classList.add("hide");
      }
    });
  }
}

document.addEventListener("DOMContentLoaded", () => {
  new CategoryFilter();
});
