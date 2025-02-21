// app/js/app.js
import "./theme";
import { apiClient } from "./ocho-api";


const appContent = document.getElementById("app");
// Helper function to fetch HTML content from the backend
async function fetchPageContent(route) {
  return await new Promise(async (resolve) => {
    try {
      const response = await apiClient.get(`./api/get-page?route=${route}`);
      if (!!response.headers["x-spa-refresh"]) {
        location.reload();
      }

      // Gestion des redirections
      if (response.status >= 300 && response.status < 400) {
        const location =
          response.headers["x-spa-redirect"] || response.data.location;

        if (location) {
          navigate(location);
          return;
        }
        console.error("Redirection error");
        console.log(response);

        resolve({
          content: `
            <h1>Erreur de redirection</h1>
            <p>Voir la console pour plus de détails.</p>
          `,
          metadata: { title: "Erreur de chargement" },
          styles: [],
        });
      }
      if (typeof response.data.content !== "string") {
        console.warn("The response is not valid data: \n", response.data);
        throw new Error("No valid data in the response.");
      }
      resolve(response.data);
    } catch (error) {
      console.error(error);
      // Mise à jour du DOM en cas d'erreur
      resolve({
        content: `
        <h1>Erreur de chargement de la page</h1>
        <p>Voir la console pour plus de détails.</p>
      `,
        metadata: { title: "Erreur de chargement" },
        styles: [],
      });
    }
  });
}

// Function to update the page content dynamically
async function navigate(route) {
  // Sauvegarder la position du scroll de la page actuelle avant de naviguer

  // Déclencher l'événement personnalisé "navigationstart"
  const navigationStartEvent = new CustomEvent("navigationstart", {
    detail: { route },
  });
  if (route.trim("/") === window.location.origin.trim("/")) {
    const refresh_url = location.href.split(window.location.origin)[1] || "/";
    location.href = refresh_url;
    return;
  }
  document.dispatchEvent(navigationStartEvent);

  const destination = `${route}`;
  const response = await fetchPageContent(destination);

  // Mettre à jour le contenu
  appContent.innerHTML = response.content;

  // Mettre à jour le titre et la meta description
  document.title = response.metadata.title || "Untitled";
  const metaDescription =
    document.querySelector('meta[name="description"]') ||
    document.createElement("meta");
  metaDescription.name = "description";
  if (metaDescription) {
    metaDescription.content = response.metadata.description || "";
  }
  !document.querySelector('meta[name="description"]') &&
    document.head.appendChild(metaDescription);

  const exclusionList = [];
  const newStyles = response.styles ?? [];
  // Mettre à jour les styles dynamiques
  const existingStyles = document.querySelectorAll("link[data-dynamic-css]");
  existingStyles.forEach((style) => {
    const sameHref = newStyles.some((s) => s === style.getAttribute("href"));
    sameHref && exclusionList.push(style.getAttribute("href"));
    !sameHref && style.remove();
  });

  newStyles.forEach((styleUrl) => {
    if (!exclusionList.includes(styleUrl)) {
      const link = document.createElement("link");
      link.rel = "stylesheet";
      link.href = styleUrl;
      link.setAttribute("data-dynamic-css", "true");
      document.head.appendChild(link);
    }
  });

  history.pushState({ route }, "", destination);

  // Restaurer la position du scroll pour la nouvelle route, ou revenir en haut par défaut
  const savedScroll = sessionStorage.getItem("scroll-" + route);
  if (savedScroll) {
    const { x, y } = JSON.parse(savedScroll);
    appContent.scrollTop = x;
    appContent.scrollLeft = y;
  } else {
    appContent.scrollTo(0, 0);
  }

  // Déclencher l'événement personnalisé "navigationend"
  const navigationEndEvent = new CustomEvent("navigationend", {
    detail: { route, response },
  });
  document.dispatchEvent(navigationEndEvent);
}

// Gestion de la navigation via les liens internes
function setupAnchorNavigation() {
  document.addEventListener("click", async (event) => {
    const anchor = event.target.closest("a");
    if (anchor && anchor.href.startsWith(window.location.origin)) {
      event.preventDefault();
      const route = anchor.getAttribute("href");
      navigate(route);
    }
  });
}

// Gérer la navigation avec les boutons "précédent/suivant" du navigateur
window.addEventListener("popstate", (event) => {
  const route = event.state?.route || "/";
  navigate(route);
});
window.addEventListener("touchmove", () => {
  sessionStorage.setItem(
    "scroll-" + window.location.pathname,
    JSON.stringify({
      x: appContent.scrollTop,
      y: appContent.scrollLeft,
    })
  );
});

// Enregistrer continuellement la position du scroll lors du défilement
appContent.addEventListener("scroll", () => {
  sessionStorage.setItem(
    "scroll-" + window.location.pathname,
    JSON.stringify({
      x: appContent.scrollTop,
      y: appContent.scrollLeft,
    })
  );
});

// Initialiser l'application
async function initialize() {
  const initialRoute = window.location.pathname;
  await navigate(initialRoute);
  setupAnchorNavigation();
}

initialize();
