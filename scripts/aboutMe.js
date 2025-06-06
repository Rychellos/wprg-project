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
        showToast(message, "success");

        const oldSrc = profilePicture.src;

        profilePicture.src = "";

        fetch(oldSrc, { method: "POST", credentials: "include" });

        profilePicture.src = oldSrc;
      });
    },
    false
  );
})();
