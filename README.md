# Documentation Ocho SPA

**Repo GitHub:** [https://github.com/OchoKOM/ocho-spa](https://github.com/OchoKOM/ocho-spa)

**Documentation for non french speakers:** [https://ochokom.github.io/ocho-spa-docs](https://ochokom.github.io/ocho-spa-docs)

Ce projet est une application web monopage (SPA) utilisant PHP pour le backend et JavaScript pour le frontend. Il permet de charger dynamiquement des pages et de gérer les routes via une API.

## Structure du Projet

```
root/
├─ .htaccess                # Configuration Apache pour les URL propres
├─ router.php               # Routeur alternatif pour le serveur PHP intégré
├─ api/
│  ├─ get-page.php          # Gestionnaire de routes
│  ├─ json-response.php     # Helper pour réponses JSON
│  └─ spa-helpers.php       # Fonctions utilitaires pour la SPA
├─ app/
│  ├─ css/
│  │  └─ style.css          # Styles globaux
│  ├─ js/
│  │  ├─ app.js             # Logique principale SPA
│  │  └─ ocho-api.js        # Client API avec gestion d'erreurs
│  └─ uploads/              # Stockage des fichiers
├─ index.php                # Point d'entrée principal
├─ pages/                   # Contenu des pages
│  ├─ about/
│  │  ├─ metadata.json      # Métadonnées spécifiques
│  │  └─ page.php           # Contenu HTML
│  ├─ courses/
│  │  ├─ dir/
│  │  │  ├─ dir-1/          # Sous-répertoires
│  │  │  └─ dir-2/
│  │  └─ page.php
│  ├─ layout.php            # Layout principal
│  ├─ page.php              # Layout par défaut
│  └─ **other pages here**  # Ajoutez vos pages ici
└─ README.md                # Documentation
```

## Détails des Fichiers

- **.htaccess** : Configuration Apache pour rediriger les requêtes vers `index.php`.

  - **Alternative** : `router.php` remplace cette fonctionnalité pour le serveur PHP intégré.

- **router.php** : Routeur PHP pour émuler le comportement d`.htaccess` :

  - Gère le routage SPA vers `index.php`
  - Sert les fichiers statiques (CSS/JS/images)
  - Applique des extensions automatiques (`.php`, `.js`)
  - Gère les erreurs personnalisées (403, 404, 500)

- **api/get-page.php** : Script PHP pour résoudre les routes et renvoyer le contenu HTML des pages.
- **api/json-response.php** : Fonction PHP pour envoyer des réponses au format JSON.
- **api/spa-helpers.php** : Fonctions utilitaires pour la gestion des redirections SPA.
- **app/css/style.css** : Fichier de style principal.
- **app/js/app.js** : Script JavaScript pour la navigation dynamique.
- **app/js/ocho-api.js** : Client HTTP avec gestion d'erreurs.
- **index.php** : Point d'entrée principal de l'application.
- **pages/** : Répertoire contenant toutes les pages de l'application.

## Fonctionnalités

### Routage Multi-Environnement

- **Apache** : Utilisation de `.htaccess` en production
- **Serveur PHP** : Utilisation de `router.php` en développement

```php
<?php
// Gestion des extensions automatiques
if (file_exists($phpFile)) {
    include($phpFile); // Ex: /about → /about.php
}
```

### Fonctionnalités Principales

- **Routage Dynamique** via `api/get-page.php`
- **Navigation Historique** avec l'API History
- **Réponses JSON Structurées**
- **Système de Redirections Intelligentes**

## Installation

1. Clonez le dépôt :
   ```bash
   git clone https://github.com/OchoKOM/ocho-spa
   ```
2. **Configuration** :

   - ### Avec apache

     ```bash
     a2enmod rewrite && systemctl restart apache2
     ```

     #### Configuration VirtualHost

     ```apache
     <VirtualHost *:80>
         DocumentRoot /chemin/vers/ocho-spa
         ServerName localhost
         <Directory "/chemin/vers/ocho-spa">
             AllowOverride All
             Require all granted
         </Directory>
     </VirtualHost>
     ```

   - ### Avec des serveurs de développement local (WAMP, XAMPP, etc.)
     - Placez le projet dans votre serveur web (exemple : `htdocs` pour XAMPP ou `www` pour WAMP).
     - Configurez un VirtualHost (recommandé) pour pointer directement sur le répertoire du projet.
     - Accédez à l'application via votre navigateur à l'adresse `http://localhost/` ou l'adresse de votre VirtualHost.
     - **Note Importante** : L'application nécessite une configuration racine (ex: `http://localhost`). Les sous-répertoires peuvent causer des problèmes de routage.
   - ### Avec Serveur PHP Intégré
     - Ouvrez le dossier du projet dans le terminal
     ```bash
     cd ocho-spa
     ```
     - Servez le fichier `router.php`
     ```bash
     php -S localhost:4000 router.php
     ```

## Notes Techniques

  <div class="technical-notes">
      <table class="">
          <thead>
              <tr>
                  <th>Fonctionnalité</th>
                  <th>Apache</th>
                  <th>PHP Intégré</th>
                  <th>Serveurs de développement local (WAMP, XAMPP, etc.)</th>
              </tr>
          </thead>
          <tbody>
              <tr>
                  <td>Gestion des redirections</td>
                  <td>✅</td>
                  <td>✅</td>
                  <td>✅</td>
              </tr>
              <tr>
                  <td>Support des sous-répertoires</td>
                  <td>✅</td>
                  <td>⚠️ Limitée</td>
                  <td>✅</td>
              </tr>
              <tr>
                  <td>Performances</td>
                  <td>✅ Optimisé</td>
                  <td>⚠️ Développement</td>
                  <td>⚠️ Dépendant de la configuration</td>
              </tr>
              <tr>
                  <td>Interface</td>
                  <td>🔧 Ligne de commande, Configuration manuelle</td>
                  <td>🖥️ Sans interface (exécution CLI uniquement)</td>
                  <td>🌐 Interface Web + Gestionnaire graphique</td>
              </tr>
          </tbody>
      </table>
  </div>

## Utilisation

- **Pages** : Ajoutez des dossiers dans `pages/` pour chaque page de l'application. Insérez-y un fichier `page.php` ou `layout.php` pour le contenu HTML.
- **Navigation** : Cliquez sur les liens pour naviguer. Les pages se chargeront dynamiquement sans rechargement complet.
- **API** : Les requêtes à l'API sont gérées par `OchoClient` dans `ocho-api.js`. Pour plus d'informations visiter le repo [https://github.com/OchoKOM/xhr](https://github.com/OchoKOM/xhr)
- **Redirections** : Utilisez la fonction `spa_redirect()` pour rediriger vers une autre route.

## Configurations

### Structure des pages

- **Ajout de pages**
  Dans le répertoire `pages/` vous ajoutez des répertoires qui contiendront vos pages
  **Structure:**
  ```
  pages/ # Accessible via le chemin / (la racine)
  ├─ metadata.json # Métadonnées par défaut
  ├─ page.php # Contenu principal
  ├─ layout.php # Layout par défaut
  └─ votre-repertoire/ # Accessible via le chemin /votre-repertoire
      ├─ metadata.json # Métadonnées de la page
      ├─ page.php # Contenu HTML de la page
      ├─ layout.php # Layout de la page (optionnel)
      └─ sous-dossier/
        └─ page.php # Les métadonnées et le layout du parent s'appliqueront ici aussi
  ```
- **Priorité des Fichiers**

  1. `page.php` dans le répertoire courant
  2. `layout.php` dans le répertoire parent le plus proche
  3. Liste des sous-répertoires (si aucun fichier n'est trouvé)

- **Layouts Dynamiques**
  Chaque répertoire peut contenir un fichier `layout.php` avec cette structure :

  ```html
  <header><!-- Navigation --></header>
  <main>
    <?php 
          echo $pageContent; // Contenus des pages 
      ?>
  </main>
  <footer><!-- Pied de page --></footer>
  ```

  - La variable `$pageContent` affiche le contenu des `page.php` du répertoire courant et des sous-répertoires qui n'ont pas de `layout.php`
  - Le fichier de mise en page (layout) peut adopter un comportement de page en ajoutant le mot-clé `"as page"` en haut du fichier, comme dans l'exemple ci-dessous :
    ```html
    <?php 
      "as page"; // Chaîne clé pour agir comme une page.
    ?>
    <div class="page">
    <?php 
        echo $pageContent; // Contenu de la page (facultatif)
    ?>
      <h1>Un layout avec un comportement de page</h1>
    </div>
    ```

- **Système de Métadonnées Hiérarchique**
  Chaque répertoire peut contenir un fichier `metadata.json` avec cette structure:
  ```json
  {
    "title": "Titre de la page",
    "description": "Meta description",
    "styles": ["/chemin/vers/style.css", "/chemin/vers/style-2.css"]
  }
  ```
  - Les titres et descriptions sont hérités des répertoires parents.
  - **Gestion des Styles**
    - Les feuilles de style sont chargées dynamiquement via les métadonnées.
    - Elles sont appliquées de manière hiérarchique (global → spécifique).
    - Les styles sont actualisés à chaque navigation.
    - Placez le fichier `metadata.json` dans le répertoire de votre page qui doit appliquer les styles :
    ```json
    {
      "title": "Styles",
      "description": "Page avec style",
      "styles": ["/chemin/vers/style.css", "/chemin/vers/style-2.css"]
    }
    ```

## Chargement Dynamique avec API

### Réponses JSON de `get-page.php`

Vous pouvez gérer cette réponse avec votre propre logique ou utiliser `apiClient` comme expliqué ci-dessous

```json
{
  "content": "<h1>Contenu de votre page</h1>",
  "metadata": {
    "title": "Titre de page",
    "description": "Meta description de page"
  },
  "styles": ["/chemin/vers/style.css", "/chemin/vers/style-2.css"]
}
```

## Gestion des Redirections

Exemple de redirection dans un fichier `page.php` ou `layout.php` :

```php
<?php
if (!user_is_logged_in()) { // Votre condition de redirection
    spa_redirect('/login'); // Redirection vers la page de login
}
```
### Reponse du serveur
La réponse du serveur ressemble à ceci (avec un statut 302 et une url de redirection dans les en-têtes) :
```json
{
  "content": "<div class='spa-redirect'>Redirection...</div>",
  "metadata": {
    "title": "Redirection"
  },
  "styles": [],
}
```
L’url de redirection se trouve dans l’en-tête avec la clé `"X-SPA-Redirect"`. 
Utilisez votre propre logique ou suivez les instructions ci-dessous.

**Utilisation de apiClient:**
Importez `apiClient` depuis `ocho-api.js` :

```js
import { apiClient } from "/app/js/ocho-api.js";
```

- **Methode 1:**
  ```js
  apiClient.get(`/api/get-page?route=/path/to/page`).then((response) => {
    console.log(response); // Réponse de l'api
  });
  ```
- **Methode 2:**
  ```js
  const response = await apiClient.get(`/api/get-page.php?route=/path/to/page`);
  console.log(response); // Réponse de l'api
  ```

**Structure de la réponse `apiClient`:**

```js
{
    data: {
        content: "<h1>Contenu de votre page</h1>",
        metadata: {
            title: "Titre de page",
            description: "Contenu de metadescription"
        },
        styles: [
            "/chemin/vers/style-1.css",
            "/chemin/vers/style-2.css",
        ]
    },
    status: 200,
    statusText: "OK",
    headers: {
        "X-header-1": "Header-1-string",
        "X-header-2": "Header-2-string",
    }
}
```

Vous pouvez adapter selon votre propre logique ou suivre les instructions ci-dessous:

- **Navigation:** utilisez cette fonction pour gérer la navigation dynamique.

```js
async function navigate(route) {
  const destination = `${route}`;
  const response = await fetchPageContent(destination);

  // Mettre à jour le contenu de la page
  document.getElementById("app").innerHTML = response.content;

  // Mettre à jour les métadonnées
  document.title = response.metadata.title || "Title";
  const metaDescription = document.querySelector('meta[name="description"]');
  if (metaDescription) {
    metaDescription.content = response.metadata.description || "";
  }

  // Mettre à jour les styles
  const exclusionList = [];
  const newStyles = response.styles ?? [];

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
}
```

- **Contenu de pages et redirections:** utilisez cette fonction

```js
async function fetchPageContent(route) {
  return await new Promise(async (resolve) => {
    try {
      const response = await apiClient.get(`./api/get-page?route=${route}`);

      // Modifiez la partie de gestion des redirections :
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
      if (!response.data.content) {
        console.warn("The response is not valid data: \n", response.data);
        throw new Error("No valid data in the response.");
      }
      resolve(response.data);
    } catch (error) {
      console.error(error);
      // Mise à jour du DOM
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
```

## Configuration Avancée

```apache
RewriteEngine On

# Exclusion des assets et API
RewriteCond %{REQUEST_URI} !^/api/ [NC]
RewriteCond %{REQUEST_URI} !^/app/ [NC]
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^ index.php [L]
```

## Bonnes Pratiques

- Organiser les styles par fonctionnalité
- Utiliser les métadonnées pour améliorer le SEO
- Structurer les layouts de manière modulaire
- Valider les fichiers JSON avec :
  ```bash
  php -l metadata.json
  ```

## Contribuer

Les contributions sont les bienvenues. Ouvrez une `pull request` ou une `issue` pour discuter des modifications.

## Licence

Ce projet est sous licence `MIT`.
