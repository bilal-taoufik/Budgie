document.addEventListener("DOMContentLoaded", () => {
  const prefsModal = document.querySelector(".fs-cc-prefs2_component");
  const settingsTriggers = document.querySelectorAll('[fs-cc="open-preferences"]');

  if (!prefsModal || settingsTriggers.length === 0) return;

  const updateSettingsTriggerVisibility = () => {
    const banner = document.querySelector('[fs-cc="banner"]');
    const floatingTrigger = document.querySelector(".cookie-settings-trigger");

    if (!floatingTrigger || !banner) return;

    const bannerHidden =
      banner.style.display === "none" ||
      banner.hidden ||
      window.getComputedStyle(banner).display === "none";

    floatingTrigger.style.display = bannerHidden ? "inline-flex" : "none";
  };

  updateSettingsTriggerVisibility();

  const observer = new MutationObserver(() => {
    updateSettingsTriggerVisibility();
  });

  const banner = document.querySelector('[fs-cc="banner"]');
  if (banner) {
    observer.observe(banner, {
      attributes: true,
      attributeFilter: ["style", "hidden", "class"],
    });
  }

  document.addEventListener("click", (event) => {
    const target = event.target.closest('[fs-cc="close"]');
    if (!target) return;
  });
});