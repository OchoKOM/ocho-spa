// app/js/app.js
import "./theme.js"
import { apiClient } from "./ocho-api.js";

// Helper function to fetch HTML content from the backend
async function fetchPageContent(route) {
  return await new Promise(async (resolve, reject) => {
    try {
      const response = await apiClient.get(`./api/get-page.php?route=${route}`);
      if (response.status >= 300 && response.status < 400) {
        const location = response.data.location || response.headers.location;
        if (location) {
          // Gérer la redirection côté client
          navigate(location);
          reject("Redirection détectée vers " + location);
        }
      }
      if (!response.data.content) {
        console.warn("The response is not valid data: \n", response.data);
        throw new Error("No valid data in the response.");
      }
      resolve(response.data);
    } catch (error) {
      console.error(error);
      // Mise à jour du DOM
      resolve({ content: `
        <h1>Erreur de chargement de la page</h1>
        <p>Voir la console pour plus de détails.</p>
      `, metadata: { title: "Erreur de chargement" }, styles: [] });
    }
    
  });
}

// Function to update the page content dynamically
async function navigate(route) {
  const destination = `${route}`;
  const response = await fetchPageContent(destination);

  // Update content
  document.getElementById("app").innerHTML = response.content;

  // Update metadata
  document.title = response.metadata.title || "Title";
  const metaDescription = document.querySelector('meta[name="description"]');
  if (metaDescription) {
    metaDescription.content = response.metadata.description || "";
  }

  // Update styles
  const existingStyles = document.querySelectorAll("link[data-dynamic-css]");
  existingStyles.forEach((style) => style.remove());

  response.styles.forEach((styleUrl) => {
    const link = document.createElement("link");
    link.rel = "stylesheet";
    link.href = styleUrl;
    link.setAttribute("data-dynamic-css", "true");
    document.head.appendChild(link);
  });

  history.pushState({ route }, "", destination);
}

// Event listener for anchor navigation
function setupAnchorNavigation() {
  document.addEventListener("click", (event) => {
    const anchor = event.target.closest("a");
    if (anchor && anchor.href.startsWith(window.location.origin)) {
      event.preventDefault();
      const route = anchor.getAttribute("href");
      navigate(route);
    }
  });
}

// Handle browser back/forward navigation
window.addEventListener("popstate", (event) => {
  const route = event.state?.route || "/";
  navigate(route);
});

// Initialize the application
async function initialize() {
  const initialRoute = window.location.pathname;
  await navigate(initialRoute);
  setupAnchorNavigation();
}

initialize();
