"use strict";

/**
 * @type {HTMLButtonElement}
 */
const addQuizAdminButton = document.getElementById("questionAdminAddButton");
const addQuizAdminModal = new bootstrap.Modal(
  document.querySelector("#questionAdminModal")
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
        location.reload();
        // const req = await fetch(
        //   "quizAdmin.php?api=detail&id=" + event.target.value
        // );
        // const data = await req.json();
        // document.getElementById("quizAdminId").value = data.id;
        // document.getElementById("quizAdminName").value = data.name;
        // document.getElementById("quizAdminDescription").value =
        //   data.description;
        // const quizCategories = document.getElementById("quizCategories");
        // quizCategories.innerHTML = "";
        // for (let index = 0; index < data.quizCategories.length; index++) {
        //   /**
        //    * @type {{
        //    * name: string,
        //    * id: number
        //    * }}
        //    */
        //   const entry = data.quizCategories[index];
        //   const element = document.createElement("a");
        //   element.href = entry.id;
        //   element.innerText = entry.name;
        //   element.classList.add("badge", "rounded-pill", "p-2", "text-bg-info");
        //   quizCategories.appendChild(element);
        // }
      } catch (error) {
        // console.error(error);
        location.reload();
      }

      locked = false;
    },
    false
  )
);
