(function () {
  const formatNumber = new Intl.NumberFormat("ar-EG");

  function updateCountdown() {
    const root = document.querySelector("[data-countdown]");
    if (!root || !window.qeTheme || !qeTheme.eidDate) return;

    const target = new Date(qeTheme.eidDate).getTime();
    if (Number.isNaN(target)) return;

    const diff = Math.max(0, target - Date.now());
    const days = Math.floor(diff / 86400000);
    const hours = Math.floor((diff % 86400000) / 3600000);
    const minutes = Math.floor((diff % 3600000) / 60000);
    const seconds = Math.floor((diff % 60000) / 1000);

    root.querySelector("[data-days]").textContent = formatNumber.format(days);
    root.querySelector("[data-hours]").textContent = formatNumber.format(hours);
    root.querySelector("[data-minutes]").textContent = formatNumber.format(minutes);
    root.querySelector("[data-seconds]").textContent = formatNumber.format(seconds);
  }

  function initProductBooking() {
    const booking = document.querySelector("[data-product-booking]");
    if (!booking) return;

    const basePrice = Number(booking.dataset.basePrice || 0);
    const priceOutput = booking.querySelector("[data-price-output]");
    const imageOutput = booking.querySelector("[data-product-image]");
    const controls = booking.querySelectorAll("[data-price-addon]");

    function calculate() {
      let total = basePrice;

      controls.forEach((control) => {
        const isChosen = control.type === "checkbox" ? control.checked : control.checked;
        if (isChosen) {
          total += Number(control.dataset.priceAddon || 0);

          if (control.dataset.optionImage && imageOutput) {
            imageOutput.src = control.dataset.optionImage;
          }
        }
      });

      if (priceOutput) {
        priceOutput.textContent = formatNumber.format(Math.round(total));
      }
    }

    controls.forEach((control) => control.addEventListener("change", calculate));
    calculate();
  }

  document.addEventListener("DOMContentLoaded", function () {
    updateCountdown();
    setInterval(updateCountdown, 1000);
    initProductBooking();
  });
})();
