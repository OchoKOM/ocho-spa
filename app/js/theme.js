// Fonction pour appliquer la classe "dark" selon le thème
function applyThemeClass() {
  const storedTheme = localStorage.getItem("theme");

  if (storedTheme === "dark") {
    document.body.classList.add("dark");
  } else if (storedTheme === "light") {
    document.body.classList.remove("dark");
  } else {
    // Mode "system" : respecter les préférences de l'utilisateur
    const isDarkMode = window.matchMedia(
      "(prefers-color-scheme: dark)"
    ).matches;
    document.body.classList.toggle("dark", isDarkMode);
  }
}

// Fonction pour définir le thème
export function setTheme(mode) {
  if (["system", "dark", "light"].includes(mode)) {
    localStorage.setItem("theme", mode);
    applyThemeClass();
  } else {
    console.error('Mode invalide. Utilisez "system", "dark" ou "light".');
  }
}

// Écouter les changements du système si le mode est "system"
window
  .matchMedia("(prefers-color-scheme: dark)")
  .addEventListener("change", () => {
    if (!["dark", "light"].includes(localStorage.getItem("theme"))) {
      setTheme("system")
    }
  });

// Appliquer la classe au chargement de la page
applyThemeClass();
