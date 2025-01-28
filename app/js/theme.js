// Fonction pour appliquer la classe "dark" selon le thème
export function applyThemeClass() {
  const theme_svgs = {
    system: `<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-palette"><circle cx="13.5" cy="6.5" r=".5" fill="currentColor"/><circle cx="17.5" cy="10.5" r=".5" fill="currentColor"/><circle cx="8.5" cy="7.5" r=".5" fill="currentColor"/><circle cx="6.5" cy="12.5" r=".5" fill="currentColor"/><path d="M12 2C6.5 2 2 6.5 2 12s4.5 10 10 10c.926 0 1.648-.746 1.648-1.688 0-.437-.18-.835-.437-1.125-.29-.289-.438-.652-.438-1.125a1.64 1.64 0 0 1 1.668-1.668h1.996c3.051 0 5.555-2.503 5.555-5.554C21.965 6.012 17.461 2 12 2z"/></svg>`,
    light: `<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-sun"><circle cx="12" cy="12" r="4"/><path d="M12 2v2"/><path d="M12 20v2"/><path d="m4.93 4.93 1.41 1.41"/><path d="m17.66 17.66 1.41 1.41"/><path d="M2 12h2"/><path d="M20 12h2"/><path d="m6.34 17.66-1.41 1.41"/><path d="m19.07 4.93-1.41 1.41"/></svg>`,
    dark: `<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-moon"><path d="M12 3a6 6 0 0 0 9 9 9 9 0 1 1-9-9Z"/></svg>`
  }
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
  return theme_svgs[storedTheme || "system"];
}

export function getTheme() {
  return localStorage.getItem("theme") || "system";
}

// Fonction pour définir le thème
export function setTheme(mode) {
  if (["system", "dark", "light"].includes(mode)) {
    localStorage.setItem("theme", mode);
    return applyThemeClass();
  } else {
    console.error('Mode invalide. Utilisez "system", "dark" ou "light".');
  }
  return applyThemeClass();
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
