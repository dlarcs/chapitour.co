class CategoryFilter {
  constructor() {
    const visibleSections = document.querySelectorAll(".visible");

    if (visibleSections.length > 0) {
      const observer = new IntersectionObserver(
        (entries) => {
          entries.forEach((entry) => {
            if (entry.isIntersecting) {
              entry.target.classList.add("is-visible");
              observer.unobserve(entry.target);
            }
          });
        },
        {
          threshold: 0.3
        }
      );

      visibleSections.forEach((section) => {
        observer.observe(section);
      });
    }

    this.filterItems = document.querySelectorAll(".filter-list li");
    this.cards = document.querySelectorAll(".place-card");

    this.initFilterEvents();
  }

  initFilterEvents() {
    this.filterItems.forEach((item) => {
      item.addEventListener("click", () => {
        const selectedFilter = item
          .getAttribute("data-filter")
          .trim()
          .toLowerCase();

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
      const cardCategories = card
        .getAttribute("data-category")
        .trim()
        .toLowerCase()
        .split(/\s+/);

      const shouldShow =
        selectedFilter === "all" ||
        cardCategories.includes(selectedFilter);

      if (shouldShow) {
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
