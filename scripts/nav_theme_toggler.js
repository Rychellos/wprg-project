const toggler = document.getElementById("nav_theme_toggler");

if (toggler && theme_toggle) {
  toggler.addEventListener("click", () => {
    if (theme_toggle() === "light") {
      toggler.classList.add("light");
      toggler.classList.remove("dark");
    } else {
      toggler.classList.remove("light");
      toggler.classList.add("dark");
    }
  });

  if (localStorage.getItem("bs_theme") === "light") {
    toggler.classList.add("light");
    toggler.classList.remove("dark");
  } else {
    toggler.classList.remove("light");
    toggler.classList.add("dark");
  }

  toggler.classList.remove("invisible");
}
