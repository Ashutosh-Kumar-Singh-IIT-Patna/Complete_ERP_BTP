document.addEventListener("DOMContentLoaded", () => {
  // Handle dropdown toggling
  const dropdowns = document.querySelectorAll(".dropdown");
  dropdowns.forEach((dropdown) => {
    const button = dropdown.querySelector(".dropdown-button");
    const menu = dropdown.querySelector(".dropdown-menu");

    button.addEventListener("click", () => {
      // Close other dropdowns
      dropdowns.forEach((d) => d.classList.remove("active"));
      dropdown.classList.toggle("active");
    });

    menu.addEventListener("click", (event) => {
      const item = event.target.closest(".dropdown-item");
      if (item) {
        const value = item.dataset.value;
        const line1 = item.querySelector(".line1").textContent;

        button.textContent = line1;
        dropdown.classList.remove("active");
        button.dataset.value = value;
      }
    });
  });

  // Close dropdowns on outside click
  document.addEventListener("click", (event) => {
    if (!event.target.closest(".dropdown")) {
      dropdowns.forEach((d) => d.classList.remove("active"));
    }
  });

  // Handle form submission
  const form = document.getElementById("course-registration-form");
  form.addEventListener("submit", (event) => {
    event.preventDefault();
    const selectedCourses = {};

    dropdowns.forEach((dropdown) => {
      const button = dropdown.querySelector(".dropdown-button");
      const value = button.dataset.value;

      if (!value) {
        alert("Please select a course for each dropdown.");
        return;
      }

      selectedCourses[button.name] = value;
    });

    console.log("Selected Courses:", selectedCourses);
    alert("Courses successfully registered!");
  });
});
