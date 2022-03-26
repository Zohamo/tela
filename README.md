# Tela

Cette application est basée sur le projet **Tela**, un framework PHP MVC influencé par [github.com/daveh/php-mvc](https://github.com/daveh/php-mvc) pour la structure, [github.com/tiagopaes/php-dao](https://github.com/tiagopaes/php-dao) pour le modèle DAO et [Laravel](https://laravel.com/) pour la syntaxe.

## Configuration

## Configuration requise pour le serveur

- Une version supérieure ou égale à **PHP 7.0** est nécessaire.

- Le **module de réécriture d'URL** doit être activé dans la configuration Apache : décommenter la ligne `LoadModule rewrite_module "{APACHEPATH}/modules/mod_rewrite.so"`.

- Apache doit permettre au fichier **.htaccess** d'outrepasser sa configuration : dans le fichier _/etc/httpd/conf/httpd.conf_, modifier le `AllowOverride` du répertoire concerné à `AllowOverride All`.

- **PDO** doit être installé :

```
yum install php-pdo
yum install php-pdo_mysql
service httpd restart
```

- **php-json** : `dnf install php-json.x86_64`

### Configuration de l'application

Le fichier **composer.json** contient certaines informations relatives à l'application, notamment :

- la **version** de l'application
- les **librairies tierces** utilisées

### Configuration de l'environnement

Le fichier **[.env](.env)** contient toutes les constantes de configuration globale du projet :

- **ENV_NAME** : Le nom de l'environnement courant
- **APP_TITLE** : Le nom de l'application
- **APP_URL** : L'URL absolue vers l'application
- **APP_ROOT** : Le chemin absolu du serveur vers l'application

_note_ : Le fichier **.env** contient les constantes de l'environnement courant, les autres éventuels fichiers _.env.dev_, _.env.test_, _.env.integration_ et _.env.production_ ne sont que des sauvegardes des configurations des autres environnements, pour qu'elles soient prises en compte, il faudra renommer le fichier de configuration correspondant à l'environnement **.env** et **supprimer les autres fichiers .env par mesure de sécurité**.

## Workflow

### Conventions de nommage

- Changelog : [tenez un changelog](https://keepachangelog.com/fr/1.0.0/)
- Commits : [Conventional Commits](https://www.conventionalcommits.org/fr/v1.0.0/)
- Versioning : [Semantic Versioning](https://semver.org/lang/fr/)

### Après toute modification

- Notifier tout changement avec les versions précédentes dans le fichier **[CHANGELOG.md](CHANGELOG.md)** en suivant le formatage de [tenez un changelog](https://keepachangelog.com/fr/1.0.0/).
- **Ajouter** les modifications avec _git_. _ex:_ `git add .`
- **Enregistrer** ces modifications avec _git_. _ex:_ `git commit -m "feat(demande): ajout de la page de détails"` en suivant la spécification de [Conventional Commits](https://www.conventionalcommits.org/fr/v1.0.0/).

### Avant de déployer l'application

- Vérifier les **configurations des environnements** dans les fichiers _.env\*_.
- Incrémenter la **version** dans le fichier **[composer.json](composer.json)**.
  _ex:_ `"version": "1.2.14"` en utilisant la convention [Semantic Versioning](https://semver.org/lang/fr/).
- Mettre à jour la **version** et la **date** de cette version dans le fichier **[CHANGELOG.md](CHANGELOG.md)**.
  _ex:_ `## [1.2.14] - 2038-07-30`.
- **Enregistrer** ces changements avec **_git_**.
  _ex:_ `git commit -m "::: VERSION 1.2.14 - Fixe la gestion des paniers"`.

### Déploiement

Le script de déploiement devra supprimer tous les fichiers _.env\*_ sauf celui de l'environnement où déployer et renommer ce fichier _.env_.

_ex:_ Sur l'environnement TEST on supprime _.env_, _.env.dev_, _.env.integration_, _.env.production_ et on préserve _.env.test_ que l'on renomme en _.env_.

### Suite à une Mise En Production

Mettre à jour la branche **_master_** :

- Se placer sur la branche _master_ : `git checkout master`
- Récupérer les commits de la branche _develop_ : `git merge develop` et résoudre les éventuels conflits.

**La branche _master_ doit toujours correspondre à l'état de l'application en Production !**

## Tests

La librairie Cypress permet de jouer des scénarios de tests bout à bout (end-to-end).

### Installation de Cypress

Le fichier _package.json_ inclut déjà Cypress, pour l'installer il faut d'abord configurer le proxy pour npm (remplacer "MATRICULE" et "MOTDEPASSE") :

```
npm config set proxy http://MATRICULE:MOTDEPASSE@dsiproxymairie.toulouse.intra:80
npm config set https-proxy https://MATRICULE:MOTDEPASSE@dsiproxymairie.toulouse.intra:80
```

puis `npm install cypress`.

### Lancer les tests

_Avant de lancer les tests il est préférable d'enlever le mode de débogage (`APP_DEBUG=false` dans .env)._

Pour ouvrir Cypress : `npx cypress open`.

Puis choisir les tests à lancer.

## Librairies

Les librairies externes utilisées :

| Librairie                                                   | Langage | Version | Description                                                                                                                                                                                                                                                            |
| ----------------------------------------------------------- | :-----: | :-----: | ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| [Bootstrap](https://getbootstrap.com/)                      | CSS/JS  |  5.0.2  | Collection d'outils utiles à la création du design de sites et d'applications web. C'est un ensemble qui contient des codes HTML et CSS, des formulaires, boutons, outils de navigation et autres éléments interactifs, ainsi que des extensions JavaScript en option. |
| [Cypress](https://www.cypress.io/)                          |   JS    |  9.5.0  | Outil permettant de jouer des scénarios de tests bout à bout (end-to-end).                                                                                                                                                                                             |
| [DataTables](https://datatables.net/)                       |   JS    | 1.11.5  | Plugin JQuery dédié aux tableaux, permettant la recherche, la pagination et le tri des données.                                                                                                                                                                        |
| [Font Awesome](https://fontawesome.com/)                    |   CSS   | 5.15.4  | Police d'écriture et un outil d'icônes qui se base sur CSS, LESS et SASS.                                                                                                                                                                                              |
| [jQuery](https://jquery.com/)                               |   JS    |  3.6.0  | Bibliothèque JavaScript libre et multiplateforme créée pour faciliter l'écriture de scripts côté client dans le code HTML des pages web.                                                                                                                               |
| [Moment.js](https://momentjs.com/)                          |   JS    | 2.29.1  | Outil de manipulation de dates et du temps.                                                                                                                                                                                                                            |
| [jasig/phpcas](https://packagist.org/packages/jasig/phpcas) |   PHP   |  1.4.0  | API permettant l'authentification des utilisateurs avec un serveur CAS.                                                                                                                                                                                                |
| [vlucas/phpdotenv](https://github.com/vlucas/phpdotenv)     |   PHP   |  5.4.1  | Permet de définir et récupérer les variables d'environnement facilement.                                                                                                                                                                                               |

## Erreurs

Voici une liste d'erreurs récurrentes et leur solution.

### Erreur : `Parse error: syntax error, unexpected ':', expecting '{' in ...\vendor\symfony\polyfill-php80\bootstrap.php on line 23`

Vérifier que la version de PHP soit >= 7.0.
