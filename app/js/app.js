// app/js/app.js

import { apiClient } from "./ocho-api.js";

// Helper function to fetch HTML content from the backend
async function fetchPageContent(route) {
  return apiClient
    .get(`./api/get-page.php?route=${route}`)
    .then((response) => {
      return response.data;
    })
    .catch((error) => {
      console.error(error);
      return "<h1>Page Not Found</h1>";
    });
}

// Function to update the page content dynamically
async function navigate(route) {
  const destination = `${route}`;

  console.log(destination);

  const content = await fetchPageContent(destination);

  document.getElementById("app").innerHTML = content;
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
