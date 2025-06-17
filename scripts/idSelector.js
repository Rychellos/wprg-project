/**
 * @type {NodeListOf<HTMLInputElement>}
 */
const ID_SETTER_FORM = document.forms.idSelectorForm;

if (ID_SETTER_FORM.length > 1) {
  document.querySelector('select[name="idSelector"]').value =
    document.querySelector('input[name="idSelector"]').value;
}

if (!location.href.includes("id=")) {
  history.pushState(
    {},
    null,
    location.href +
      (location.href.includes("?") ? "&" : "?") +
      `id=${
        ID_SETTER_FORM.idSelector.length
          ? ID_SETTER_FORM.idSelector.item(0).value
          : 0
      }`
  );
}

ID_SETTER_FORM.reset();

ID_SETTER_FORM.idSelector.forEach((element) => {
  element.addEventListener("change", (event) => {
    if (ID_SETTER_FORM.length > 1) {
      document.querySelector('select[name="idSelector"]').value =
        document.querySelector('input[name="idSelector"]').value;
    }

    ID_SETTER_FORM.idSelector.value = event.target.value;

    if (!location.href.includes("id=")) {
      history.pushState({}, null, location.href + `?id=${event.target.value}`);
    }

    history.pushState(
      {},
      null,
      location.href.replace(/id=\d+/, `id=${event.target.value}`)
    );

    location.reload();
  });
});
