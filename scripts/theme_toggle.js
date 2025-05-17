/**
 *
 * @returns {"light" | "dark"}
 */
function theme_toggle() {
  let theme = localStorage.getItem("bs_theme") || "light";

  theme = theme === "light" ? "dark" : "light";

  localStorage.setItem("bs_theme", theme);
  document.documentElement.setAttribute("data-bs-theme", theme);

  return theme;
}
