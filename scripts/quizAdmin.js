"use strict";

/**
 * @type {HTMLButtonElement}
 */
const addQuizAdminButton = document.getElementById("quizAdminAddButton");
const addQuizAdminModal = new bootstrap.Modal(
  document.querySelector("#quizAddModal")
);

addQuizAdminButton.addEventListener(
  "click",
  /**
   * @param {MouseEvent} event
   */
  (event) => {
    if (addQuizAdminModal) {
      addQuizAdminModal.show();
    }
  },
  false
);

const ADD_QUIZ_SEARCH_BAR = document.getElementById("addQuizCategorySearchBar");
const ADD_QUIZ_CATEGORY_SEARCH_CONTAINER = document.getElementById(
  "addQuizCategorySearchContainer"
);

ADD_QUIZ_SEARCH_BAR.addEventListener("input", function () {
  const value = this.value.toLowerCase();
  ADD_QUIZ_CATEGORY_SEARCH_CONTAINER.querySelectorAll(".form-check").forEach(
    (checkbox) => {
      checkbox.style.display = checkbox.textContent
        .toLowerCase()
        .includes(value)
        ? ""
        : "none";
    }
  );
});

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
          "quizAdmin.php?api=detail&id=" + event.target.value
        );

        const data = await req.json();

        document.getElementById("quizAdminId").value = data.id;
        document.getElementById("quizAdminName").value = data.name;
        document.getElementById("quizAdminDescription").value =
          data.description;
        const quizCategories = document.getElementById("quizCategories");

        quizCategories.innerHTML = "";

        for (let index = 0; index < data.quizCategories.length; index++) {
          /**
           * @type {{
           * name: string,
           * id: number
           * }}
           */
          const entry = data.quizCategories[index];

          const element = document.createElement("a");
          element.href = entry.id;
          element.innerText = entry.name;
          element.classList.add("badge", "rounded-pill", "p-2", "text-bg-info");
          quizCategories.appendChild(element);
        }
      } catch (error) {
        console.error(error);
        // location.reload();
      }

      locked = false;
    },
    false
  )
);
