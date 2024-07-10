/*!
 * Tabler v1.0.0-beta19 (https://tabler.io)
 * @version 1.0.0-beta19
 * @link https://tabler.io
 * Copyright 2018-2023 The Tabler Authors
 * Copyright 2018-2023 codecalm.net Paweł Kuna
 * Licensed under MIT (https://github.com/tabler/tabler/blob/master/LICENSE)
 */
(function (factory) {
  typeof define === "function" && define.amd ? define(factory) : factory();
})(function () {
  "use strict";

  var themeStorageKey = "tablerTheme";
  var defaultTheme = "light";
  var selectedTheme = localStorage.getItem(themeStorageKey) || defaultTheme;

  document.body.setAttribute("data-bs-theme", selectedTheme);

  function toggleTheme() {
    selectedTheme = selectedTheme === "dark" ? "light" : "dark";
    localStorage.setItem(themeStorageKey, selectedTheme);

    if (selectedTheme === "dark") {
      document.body.setAttribute("data-bs-theme", selectedTheme);
    } else {
      document.body.removeAttribute("data-bs-theme");
    }
  }

  // Call the toggleTheme function on initial load (optional)
  // toggleTheme(); // Uncomment to apply initial theme based on storage

  // Bind toggleTheme function to an event or button click (replace 'toggle-theme-btn' with your actual element ID)
  document
    .getElementById("toggle-theme-btn")
    .addEventListener("click", toggleTheme);
});
