# Documentation Ocho SPA

## SPA avec API et Routage Dynamique

**Repo GitHub:** [https://github.com/OchoKOM/ocho-spa](https://github.com/OchoKOM/ocho-spa)
**Documentation for non french speakers:** [https://ochokom.github.io/ocho-spa-docs](https://ochokom.github.io/ocho-spa-docs)

Ce projet est une application web monopage (SPA) utilisant PHP pour le backend et JavaScript pour le frontend. Il permet de charger dynamiquement des pages et de gérer les routes via une API.

## Structure du Projet

```
root/
├─ .htaccess                # Configuration pour les URL propres
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

- **`.htaccess`** : Configure Apache pour rediriger les requêtes vers `index.php` et gérer les URLs propres.
- **`api/get-page.php`** : Script PHP pour résoudre les routes et renvoyer le contenu HTML des pages.
- **`api/json-response.php`** : Fonction PHP pour envoyer des réponses au format JSON.
- **`api/spa-helpers.php`** : Fonctions utilitaires pour la gestion des redirections et autres fonctionnalités SPA.
- **`app/css/style.css`** : Fichier de style principal de l'application.
- **`app/js/app.js`** : Script JavaScript pour gérer la navigation et le chargement dynamique des pages.
- **`app/js/ocho-api.js`** : Classe JavaScript pour effectuer des requêtes HTTP vers l'API.
- **`index.php`** : Point d'entrée principal de l'application, servant de layout pour la SPA.
- **`pages/`** : Répertoire contenant les pages de l'application, chaque page ayant son propre répertoire.

## Fonctionnalités

- **Routage Dynamique** : Résolution dynamique des routes via `api/get-page.php`, avec chargement du contenu des pages en JavaScript.
- **Navigation Historique** : Utilisation de l'API History pour gérer la navigation avec le bouton précédent/suivant du navigateur.
- **Réponses JSON** : Les API retournent des réponses structurées au format JSON.
- **Gestion des Redirections** : Système de redirection intégré pour une navigation fluide sans rechargement de page.

## Installation

1. Clonez le dépôt :
    ```bash
    git clone https://github.com/OchoKOM/ocho-spa
    ```
2. Placez le projet dans votre serveur web (exemple : `htdocs` pour XAMPP ou `www` pour WAMP).
3. Assurez-vous que le module Apache `mod_rewrite` est activé pour que `.htaccess` fonctionne.
4. Configurez un VirtualHost (recommandé) pour pointer directement sur le répertoire du projet.
5. Accédez à l'application via votre navigateur à l'adresse `http://localhost/` ou l'adresse de votre VirtualHost.

**Note Importante :** L'application nécessite une configuration racine (ex: `http://localhost`). Les sous-répertoires peuvent causer des problèmes de routage.

## Utilisation

- **Pages** : Ajoutez des dossiers dans `pages/` pour chaque page de l'application. Insérez-y un fichier `page.php` ou `layout.php` pour le contenu HTML.
- **Navigation** : Cliquez sur les liens pour naviguer. Les pages se chargeront dynamiquement sans rechargement complet.
- **API** : Les requêtes à l'API sont gérées par `OchoClient` dans `ocho-api.js`. Pour plus d'informations visiter le repo [https://github.com/OchoKOM/xhr](https://github.com/OchoKOM/xhr).
- **Redirections** : Utilisez la fonction `spa_redirect()` pour rediriger vers une autre route.

## Exemple d'Utilisation de l'API

```javascript
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

Exemple de structure de `layout.php` :

```php
<header><!-- Navigation --></header>
<main>
<?php 
    echo $pageContent; /* La variable $pageContent affiche le contenu des page.php du repertoire courant et des sous répertoires qui n'ont pas de layout.php */ 
?>
</main>
<footer><!-- Pied de page --></footer>
```

### Gestion des Styles

- Les feuilles de style sont chargées dynamiquement via les métadonnées.
- Elles sont appliquées de manière hiérarchique (global → spécifique).
- Les styles sont actualisés à chaque navigation.

#### Exemple de metadonnées avec style

Placez le fichier `metadata.json` dans le repertoire de votre page qui dois appliquer les style :

```json
{
    "title": "Styles",
    "description": "Page avec style",
    "styles": ["/chemin/vers/style.css", "/chemin/vers/style-2.css"]
}
```

### API integrée

Exemple de reponse json de l'API géré par `apiClient` :

```json
{
    "content": "<h1>Bienvenue</h1>",
    "metadata": {
        "title": "Styles",
        "description": "Page avec style"
    },
    "styles": ["/chemin/vers/style.css", "/chemin/vers/style-2.css"]
}
```

- **Contenu final (converti par apiClient)** : 
    Vous pouvez gerer cette reponse selon votre propre logique.

    ```json
    {
        "data": {
            "content": "<h1>Bienvenue</h1>",
            "metadata": {
                "title": "Accueil",
                "description": "Site de démonstration"
            },
            "styles": [
                "/app/css/style.css"
            ]
        },
        "status": 200,
        "statusText": "OK",
        "headers": {
            "header-1": "Header-1-value"
        }
    }
    ```

### Gestion des Redirections

Exemple de redirection dans un fichier `page.php` ou `layout.php` :

```php
<?php
if (!user_is_logged_in()) {
    spa_redirect('/login');
}
```

### Chargement Dynamique avec Métadonnées

Exemple de script de chargement de page :

```javascript
async function fetchPageContent(route) {
  return await new Promise(async (resolve, reject) => {
    try {
      const response = await apiClient.get(`/api/get-page.php?route=${route}`);
      
      // Modifiez la partie de gestion des redirections :
      if (response.status >= 300 && response.status < 400) {
        const location = response.headers['x-spa-redirect'] || response.data.location;
        
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

### Priorité des Fichiers

1. `page.php` dans le répertoire courant
2. `layout.php` dans le répertoire parent le plus proche
3. Liste des sous-répertoires (si aucun fichier n'est trouvé)

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

Ce projet est sous licence `MIT`. Consultez le fichier `LICENSE` pour les détails.