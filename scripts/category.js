"use strict";

/**
 * @type {HTMLButtonElement}
 */
const addCategoryButton = document.getElementById("addCategoryButton");
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

/**
 * @type {NodeListOf<HTMLInputElement>}
 */
const categorySelect = document.getElementsByName("categoryId");

if (!location.href.includes("id=")) {
  history.pushState(
    {},
    null,
    location.href +
      (location.href.includes("?") ? "&" : "?") +
      `id=${categorySelect.item(1).value}`
  );
}

document.getElementById("categoryId").value = categorySelect.item(1).value;

let locked = false;
categorySelect.forEach((node) =>
  node.addEventListener(
    "change",
    /**
     * @param {Event} event
     */
    async (event) => {
      if (locked) return;

      locked = true;

      document.querySelector('select[name="categoryId"]').value =
        event.target.value;

      document.forms["categorySelect"].categoryId.value = event.target.value;

      if (!location.href.includes("id=")) {
        history.pushState(
          {},
          null,
          location.href + `?id=${event.target.value}`
        );
      }

      history.pushState(
        {},
        null,
        location.href.replace(/id=\d+/, `id=${event.target.value}`)
      );

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
      } catch (error) {
        console.error(error);
      }

      locked = false;
    },
    false
  )
);
