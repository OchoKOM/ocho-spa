# SPA avec API et Routage Dynamique

Ce projet est une application web monopage (SPA) utilisant PHP pour le backend et JavaScript pour le frontend. Il permet de charger dynamiquement des pages et de gérer les routes via une API.

## Structure du Projet

```
root/
├─ .htaccess               # Configuration pour les URL propres
├─ api/
│  ├─ get-page.php         # Gestionnaire de routes
│  └─ json-response.php    # Helper pour réponses JSON
├─ app/
│  ├─ css/
│  │  └─ style.css         # Styles globaux
│  ├─ js/
│  │  ├─ app.js            # Logique principale SPA
│  │  └─ ocho-api.js       # Client API avec gestion d'erreurs
│  └─ uploads/             # Stockage des fichiers
├─ index.php               # Point d'entrée principal
├─ pages/                  # Contenu des pages
│  ├─ about/
│  │  ├─ metadata.json     # Métadonnées spécifiques
│  │  └─ page.php          # Contenu HTML
│  ├─ courses/
│  │  ├─ dir/
│  │  │  ├─ dir-1/         # Sous-répertoires
│  │  │  └─ dir-2/
│  │  └─ page.php
│  ├─ layout.php           # Layout principal
│  ├─ page.php             # Layout par défaut
│  └─ **other pages here** # Ajoutez vos pages ici
└─ README.md               # Documentation
```

### Détails des Fichiers

- **.htaccess** : Configure Apache pour rediriger les requêtes vers `index.php` et gérer les URLs propres.
- **api/get-page.php** : Script PHP pour résoudre les routes et renvoyer le contenu HTML des pages.
- **api/json-response.php** : Fonction PHP pour envoyer des réponses au format JSON.
- **app/css/style.css** : Fichier de style principal de l'application.
- **app/js/app.js** : Script JavaScript pour gérer la navigation et le chargement dynamique des pages.
- **app/js/ocho-api.js** : Classe JavaScript pour effectuer des requêtes HTTP vers l'API.
- **index.php** : Point d'entrée principal de l'application, servant de layout pour la SPA.
- **pages/** : Répertoire contenant les pages de l'application, chaque page ayant son propre répertoire.

## Fonctionnalités

- **Routage Dynamique** : Résolution dynamique des routes via `api/get-page.php`, avec chargement du contenu des pages en JavaScript.
- **Navigation Historique** : Utilisation de l'API History pour gérer la navigation avec le bouton précédent/suivant du navigateur.
- **Réponses JSON** : Les API retournent des réponses structurées au format JSON.

## Installation

1. Clonez le dépôt :
   ```sh
   git clone https://github.com/OchoKOM/ocho-spa
   ```
2. Placez le projet dans votre serveur web (exemple : `htdocs` pour XAMPP ou `www` pour WAMP).
3. Assurez-vous que le module Apache `mod_rewrite` est activé pour que `.htaccess` fonctionne.
4. Configurez un VirtualHost (recommandé) pour pointer directement sur le répertoire du projet.
5. Accédez à l'application via votre navigateur à l'adresse `http://localhost/` ou l'adresse de votre VirtualHost.

**Remarque** : L'application peut ne pas fonctionner correctement si elle n'est pas à la racine de `localhost`. Par exemple :

- _Non recommandé_ : `http://localhost/app` ou `http://mon-virtualhost/app`.
- _Recommandé_ : `http://localhost` ou `http://mon-virtualhost`.

## Utilisation

- **Pages** : Ajoutez des dossiers dans `pages/` pour chaque page de l'application. Insérez-y un fichier `page.php` ou `layout.php` pour le contenu HTML.
- **Navigation** : Cliquez sur les liens pour naviguer. Les pages se chargeront dynamiquement sans rechargement complet.
- **API** : Les requêtes à l'API sont gérées par `OchoClient` dans `ocho-api.js`.

## Exemple d'Utilisation de l'API

```js
import { apiClient } from "./app/js/ocho-api.js";

apiClient
  .get("api/get-page.php?route=about")
  .then((response) => {
    console.log(response.data);
  })
  .catch((error) => {
    console.error(error);
  });
```

## Fonctionnalités Clés

### Système de Métadonnées Hiérarchique

Chaque répertoire peut contenir un fichier `metadata.json` :

```json
{
  "title": "À Propos",
  "description": "Page de présentation de l'entreprise",
  "styles": ["/app/css/about.css"]
}
```

- Les titres et descriptions sont hérités des répertoires parents.
- Les styles sont cumulés du parent à l'enfant.

### Layouts Dynamiques

Exemple de `layout.php` :

```php
<header><!-- Navigation --></header>

<main><?= $content ?></main>

<footer><!-- Contenu global --></footer>
```

### Gestion des Styles

- Les feuilles de style sont chargées dynamiquement via les métadonnées.
- Elles sont appliquées de manière hiérarchique (global → spécifique).
- Les styles sont actualisés à chaque navigation.

### API Intégrée

Exemple de réponse JSON :

```json
{
  "content": "<h1>Bienvenue</h1>",
  "metadata": {
    "title": "Accueil",
    "description": "Site de démonstration"
  },
  "styles": ["/app/css/global.css", "/app/css/home.css"]
}
```

### Chargement Dynamique avec Métadonnées

Exemple de script :

```javascript
async function loadPage(route) {
  try {
    const response = await apiClient.get(`/api/get-page.php?route=${route}`);
    document.getElementById("app").innerHTML = response.data.content;
    document.title = response.data.metadata.title;
    document
      .querySelector('meta[name="description"]')
      .setAttribute("content", response.data.metadata.description);

    response.data.styles.forEach((styleUrl) => {
      if (!document.querySelector(`link[href="${styleUrl}"]`)) {
        const link = document.createElement("link");
        link.rel = "stylesheet";
        link.href = styleUrl;
        document.head.appendChild(link);
      }
    });
  } catch (error) {
    document.getElementById("app").innerHTML = `
      <h1>Erreur de chargement</h1>
      <p>Voir la console pour plus de détails.</p>
    `;
    console.error(error);
  }
}
```

## Configuration Avancée

### Priorité des Fichiers

1. `page.php` dans le répertoire courant.
2. `layout.php` dans le répertoire parent le plus proche.
3. Liste des sous-répertoires (si aucun fichier n’est trouvé).

### Règles de Réécriture (.htaccess)

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

1. Organiser les styles par fonctionnalité.
2. Utiliser les métadonnées pour améliorer le SEO.
3. Structurer les layouts de manière modulaire.
4. Valider les fichiers JSON avec :
   ```bash
   php -l metadata.json
   ```

## Contribuer

Les contributions sont les bienvenues. Ouvrez une pull request ou une issue pour discuter des modifications.

## Licence

Ce projet est sous licence MIT. Consultez le fichier `LICENSE` pour les détails.
