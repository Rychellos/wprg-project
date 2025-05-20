let timerElements = document.querySelectorAll(".next-quiz-timer");

function updateCountdown() {
  const now = new Date();

  let hours = 23 - now.getHours();
  let minutes = 59 - now.getMinutes();
  let seconds = 60 - now.getSeconds();

  seconds = seconds < 10 ? "0" + seconds : seconds;
  minutes = minutes < 10 ? "0" + minutes : minutes;
  hours = hours < 10 ? "0" + hours : hours;

  timerElements.forEach((element) => {
    element.innerHTML = `${hours}:${minutes}:${seconds}`;
  });
}

updateCountdown();
setInterval(updateCountdown, 1000);
