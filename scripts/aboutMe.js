(() => {
  "use strict";

  /**
   * @type {HTMLFormElement}
   */
  const profilePitureForm = document.getElementById("profilePitureForm");

  /**
   * @type {HTMLImageElement}
   */
  const profilePicture = document.getElementById("profilePicture");

  // Loop over them and prevent submission
  profilePitureForm.addEventListener(
    "submit",
    /**
     * @param {SubmitEvent} event
     */
    (event) => {
      event.preventDefault();

      const data = new FormData(profilePitureForm);

      fetch("uploadAvatar.php", {
        method: "post",
        body: data,
      }).then(async (response) => {
        const message = await response.text();
        const toastElement = document.createElement("div");
        toastElement.classList.add("toast", "mt-3", "overflow-hidden");
        toastElement.setAttribute("role", "alert");
        toastElement.setAttribute("aria-live", "assertive");
        toastElement.setAttribute("aria-atomic", "true");

        toastElement.innerHTML = `<div class="toast-header border-0">
            <strong class="me-auto">${message}</strong>
            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>`;
        profilePitureForm.append(toastElement);

        const toast = new bootstrap.Toast(toastElement);
        toast.show();

        console.log(toast);

        profilePicture.src =
          profilePicture.src.split("?")[0] + `?t=${Date.now()}`;
      });
    },
    false
  );
})();
