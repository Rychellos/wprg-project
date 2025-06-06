const toastContainer = document.getElementById("toastContainer");

function showToast(message, type = "info", delay = 5000) {
  // Generate a unique ID
  const toastId = "toast-" + Date.now();

  // Create toast wrapper
  const toastEl = document.createElement("div");
  toastEl.className = `toast align-items-center text-bg-${type} border-0 show`;
  toastEl.setAttribute("role", "alert");
  toastEl.setAttribute("aria-live", "assertive");
  toastEl.setAttribute("aria-atomic", "true");
  toastEl.id = toastId;
  toastEl.style.minWidth = "250px";

  // Toast content
  toastEl.innerHTML = `
      <div class="d-flex">
        <div class="toast-body">${message}</div>
        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
      </div>
    `;

  const toast = new bootstrap.Toast(toastEl, { delay, autohide: true });

  toast.show();

  toastContainer.appendChild(toastEl);

  // Remove from DOM after hidden
  toastEl.addEventListener("hidden.bs.toast", () => {
    toastEl.remove();
  });
}
