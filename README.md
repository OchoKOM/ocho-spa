# SPA avec API et Routage Dynamique

Ce projet est une application web monopage (SPA) utilisant PHP pour le backend et JavaScript pour le frontend. Il permet de charger dynamiquement des pages et de gérer les routes via l'API.

## Structure du Projet

```
route/
├─ .htaccess
├─ api/
│  ├─ get-page.php
│  └─ json-response.php
├─ app/
│  ├─ css/
│  │  └─ style.css
│  └─ js/
│     ├─ app.js
│     └─ ocho-api.js
├─ index.php
├─ pages/
│  ├─ about/
│  │  └─ page.php
│  ├─ courses/
│  │  └─ page.php
│  ├─ **other direcrories here**
│  └─ layout.php
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
    git clone <url-du-repo>
    ```

2. Placez le projet dans votre serveur web (par exemple, `htdocs` pour XAMPP ou `www` pour Wamp).

3. Assurez-vous que le module `mod_rewrite` d'Apache est activé pour que les règles de réécriture dans 

.htaccess

 fonctionnent correctement.

4. Accédez à l'application via votre navigateur à l'adresse `http://localhost/<nom-du-projet>`.

- **Remarque** : Il est possible que l'application ne fonctionne pas correctement sans virtualhost, il est récommandé d'en créer un ou directement utiliser la racine de votre serveur web (`htdocs` ou `www` comme racine du projet). 

## Utilisation

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

## Contribuer

Les contributions sont les bienvenues ! Veuillez soumettre une pull request ou ouvrir une issue pour discuter des changements que vous souhaitez apporter.

## Licence

Ce projet est sous licence MIT. Voir le fichier LICENSE pour plus de détails.