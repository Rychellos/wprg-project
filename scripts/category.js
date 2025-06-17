"use strict";

/**
 * @type {HTMLButtonElement}
 */
const addCategoryButton = document.getElementById("categoryAddButton");
const addCategoryModal = new bootstrap.Modal(document.querySelector(".modal"));

addCategoryButton.addEventListener(
  "click",
  /**
   * @param {MouseEvent} event
   */
  (event) => {
    if (addCategoryModal) {
      addCategoryModal.show();
    }
  },
  false
);

function quizString(num) {
  let n = abs(num);

  // Sprawdź ostatnie dwie cyfry
  let lastTwo = n % 100;
  let lastOne = n % 10;

  if (n == 1) {
    return `${num} quiz`;
  } else if (lastTwo >= 12 && lastTwo <= 14) {
    return `${num} quizów`;
  } else if (lastOne >= 2 && lastOne <= 4) {
    return `${num} quizy`;
  } else {
    return `${num} quizów`;
  }
}

let locked = false;
ID_SETTER_FORM.idSelector.forEach((node) =>
  node.addEventListener(
    "change",
    /**
     * @param {Event} event
     */
    async (event) => {
      if (locked) return;

      locked = true;

      try {
        const req = await fetch(
          "category.php?api=detail&id=" + event.target.value
        );

        const data = await req.json();

        document.getElementById("categoryId").value = data.id;
        document.getElementById("categoryName").value = data.name;
        document.getElementById("categoryDescription").value = data.description;
        const quizzesWithCategory = document.getElementById(
          "quizzesWithCategory"
        );

        quizzesWithCategory.innerHTML = "";

        for (let index = 0; index < data.quizzesWithCategory.length; index++) {
          /**
           * @type {{
           * name: string,
           * quizId: number
           * }}
           */
          const entry = data.quizzesWithCategory[index];

          const element = document.createElement("a");
          element.href = entry.quizId;
          element.innerText = entry.name;
          element.classList.add("badge", "rounded-pill", "p-2", "text-bg-info");
          quizzesWithCategory.appendChild(element);
        }

        document.getElementById("quizCount").innerText = quizString(
          data.quizzesWithCategory.length
        );
      } catch (error) {
        console.error(error);
      }

      locked = false;
    },
    false
  )
);
