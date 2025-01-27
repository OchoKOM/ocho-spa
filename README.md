# SPA avec API et Routage Dynamique

Ce projet est une application web monopage (SPA) utilisant PHP pour le backend et JavaScript pour le frontend. Il permet de charger dynamiquement des pages et de gérer les routes via l'API.

## Structure du Projet

```
root/
├─ .htaccess
├─ api/
│  ├─ get-page.php       # Gestionnaire de routes
│  └─ json-response.php  # Helper pour réponses JSON
├─ app/
│  ├─ css/
│  │  └─ style.css       # Styles globaux
│  ├─ js/
│  │  ├─ app.js          # Logique principale SPA
│  │  └─ ocho-api.js     # Client API avec gestion d'erreurs
│  └─ uploads/           # Stockage des fichiers
├─ index.php             # Point d'entrée principal
├─ pages/                # Contenu des pages
│  ├─ about/
│  │  ├─ metadata.json   # Métadonnées spécifiques
│  │  └─ page.php        # Contenu HTML
│  ├─ courses/
│  │  ├─ dir/
│  │  │  ├─ dir-1/         # Sous-répertoires
│  │  │  └─ dir-2/
│  │  └─ page.php
│  ├─ layout.php         # Layout principal
│  ├─ page.php           # Layout par défaut
│  └─ test/
│     └─ page.php
└─ README.md
```

### Détails des Fichiers

- **.htaccess** : Fichier de configuration Apache pour rediriger les requêtes vers `index.php` et gérer les URLs propres.
- **api/get-page.php** : Script PHP pour résoudre les routes et renvoyer le contenu HTML des pages.
- **api/json-response.php** : Fonction PHP pour envoyer des réponses JSON.
- **app/css/style.css** : Fichier CSS pour le style de l'application.
- **app/js/app.js** : Script JavaScript principal pour gérer la navigation et le chargement dynamique des pages.
- **app/js/ocho-api.js** : Classe JavaScript pour gérer les requêtes HTTP vers l'API.
- **index.php** : Point d'entrée principal de l'application, sert le layout de la SPA.
- **pages/** : Contient les différentes pages de l'application, chaque page ayant son propre répertoire.

## Fonctionnalités

- **Routage Dynamique** : Les routes sont résolues dynamiquement via `api/get-page.php` et le contenu des pages est chargé en utilisant JavaScript.
- **Navigation Historique** : Utilisation de l'API History pour gérer la navigation avant/arrière du navigateur.
- **Réponses JSON** : Les réponses de l'API sont envoyées au format JSON.

## Installation

1. Clonez le dépôt :
    ```sh
    git clone https://github.com/OchoKOM/ocho-spa
    ```

2. Placez le projet dans votre serveur web (par exemple, `htdocs` pour XAMPP ou `www` pour Wamp).

3. Assurez-vous que le module `mod_rewrite` d'Apache est activé pour que les règles de réécriture dans .htaccess fonctionnent correctement.

4. Configuration d’un VirtualHost (Recommandé)
Pour une expérience optimale, configurez un VirtualHost pour pointer directement sur le répertoire du projet.

5. Accédez à l'application via votre navigateur à l'adresse `http://localhost/` ou l'adresse de votre virtualhost.

- **Remarque** : Il est possible que l'application ne fonctionne pas correctement sans virtualhost ou s'il n'est pas a la racine de localhost (*non recommandé*: `http://localhost/app` ou `http://mon-virtualhost/app`, *recommandé*: `http://localhost` ou `http://mon-virtualhost`), il est récommandé d'en créer un ou directement utiliser la racine de votre serveur web (`htdocs` ou `www` comme racine du projet). 

## Utilisation

- **Pages** : Créez des dossiers dans le répertoire `pages` pour chaque page de votre application et mettez y des fichiers `page.php` ou `layout.php` pour y mettre votre contenu HTML.
- **Navigation** : Cliquez sur les liens pour naviguer entre les pages. Le contenu sera chargé dynamiquement sans rechargement complet de la page.
- **API** : Les requêtes vers l'API sont gérées par `OchoClient` dans `ocho-api.js`.
- **Note** : Pour plus d'informations sur le fonctionnement de l'api veuillez visiter la documentation sur la page [Github](https://github.com/OchoKOM/xhr) du projet.

## Exemple d'Utilisation de l'API

```js
import { apiClient } from "./app/js/ocho-api.js";

apiClient.get('api/get-page.php?route=about')
  .then(response => {
    console.log(response.data);
  })
  .catch(error => {
    console.error(error);
  });
```
## Fonctionnalités Clés

### 1. Système de Métadonnées Hiérarchique
Chaque répertoire peut contenir un fichier `metadata.json` :
```json
{
  "title": "À Propos",
  "description": "Page de présentation de l'entreprise",
  "styles": ["/app/css/about.css"]
}
```
- Le titre et la description sont hérités du répertoire parent le plus proche
- Les styles sont cumulatifs (parent → enfant)

### 2. Layouts Dynamiques
Exemple de `layout.php` :
```php
<header><!-- Navigation --></header>

<main><?= $content ?></main>

<footer><!-- Contenu global --></footer>
```

### 3. Gestion des Styles
Les feuilles de style sont :
1. Chargées dynamiquement via les métadonnées
2. Empilées hiérarchiquement (styles globaux → spécifiques)
3. Actualisées à chaque navigation

### 4. API Intégrée
Réponse JSON standard :
```json
{
  "content": "<h1>Bienvenue</h1>",
  "metadata": {
    "title": "Accueil",
    "description": "Site de démonstration"
  },
  "styles": [
    "/app/css/global.css",
    "/app/css/home.css"
  ]
}
```

## Utilisation de l'API Client

Exemple complet avec gestion des métadonnées :
```javascript
import { apiClient } from "./ocho-api.js";

async function loadPage(route) {
  try {
    const response = await apiClient.get(`/api/get-page.php?route=${route}`);
    
    // Mise à jour du DOM
    document.getElementById('app').innerHTML = response.data.content;
    
    // Métadonnées dynamiques
    document.title = response.data.metadata.title;
    document.querySelector('meta[name="description"]')
      .setAttribute('content', response.data.metadata.description);
    
    // Gestion des styles
    response.data.styles.forEach(styleUrl => {
      if (!document.querySelector(`link[href="${styleUrl}"]`)) {
        const link = document.createElement('link');
        link.rel = 'stylesheet';
        link.href = styleUrl;
        document.head.appendChild(link);
      }
    });
    
  } catch (error) {
    // Mise à jour du DOM
    document.getElementById('app').innerHTML = `
      <h1>Erreur de chargement de la page</h1>
      <p>Voir la console pour plus de détails.</p>
    `;
    console.error('Erreur de chargement:', error);
  }
}
```

## Configuration Avancée

### Priorité des Fichiers
1. `page.php` dans le répertoire courant
2. `layout.php` le plus proche
3. Liste des sous-répertoires (si aucun fichier trouvé)

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

1. Organiser les styles par fonctionnalité
2. Utiliser les métadonnées pour le SEO
3. Structurer les layouts de manière modulaire
4. Valider les fichiers JSON avec :
   ```bash
   php -l metadata.json
   ```

## Contribuer

Les contributions sont les bienvenues ! Veuillez soumettre une pull request ou ouvrir une issue pour discuter des changements que vous souhaitez apporter.

## Licence

Ce projet est sous licence MIT. Voir le fichier LICENSE pour plus de détails.

Voici la mise à jour du README intégrant les nouvelles fonctionnalités :

# SPA avec Gestion de Métadonnées et Styles Dynamiques

Application web monopage (SPA) avec routage dynamique, gestion hiérarchique de métadonnées et chargement automatique de styles CSS.