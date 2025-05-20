(() => {
  "use strict";

  // Fetch all the forms we want to apply custom Bootstrap validation styles to
  /**
   * @type {TMLFormElement}
   */
  const form = document.getElementById("registerForm");

  /**
   * @type {HTMLInputElement}
   */
  const userPassword = document.getElementById("userPassword");

  /**
   * @type {HTMLInputElement}
   */
  const userPasswordRepeat = document.getElementById("userPasswordRepeat");

  // Loop over them and prevent submission
  form.addEventListener(
    "submit",
    (event) => {
      form.classList.remove("was-validated");

      if (!form.checkValidity()) {
        event.preventDefault();
        event.stopPropagation();
      }

      if (userPassword.value != userPasswordRepeat.value) {
        event.preventDefault();
        event.stopPropagation();

        userPasswordRepeat.classList.add("is-invalid");
      } else {
        userPasswordRepeat.classList.remove("is-invalid");
      }

      form.classList.add("was-validated");
    },
    false
  );
})();
