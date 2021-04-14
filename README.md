# Blog-Symfony

Projet realisé en suivant le tutoriel 1h pour Comprendre Symfony de [Lior Chamla](https://www.youtube.com/watch?v=UTusmVpwJXo)

## 💻 Le Projet

***

Création d'un blog avec une interface utilisant le thème [Flatly](https://bootswatch.com/flatly/) de Bootswatch.

Les fixtures utilisées ont été générées avec [NelmioAlice](https://github.com/nelmio/alice).

Ce projet nous permet d'aborder l'utilisation de diverses fonctionnalités de Symfony :

- la création des routes et l'envoi des variables à la vue grâce à Twig

- la création des formulaires en utilisant soit le [createFormBuilder](https://symfony.com/doc/current/forms.html#id2) soit le [maker form](https://symfony.com/doc/current/forms.html#creating-form-classes)

- la relation entre les entités

- la validation des données avec les *constraints*

- la configuration pous gérer le login/logout des utilisateurs avec les providers


## 🚀 Technologies

***

Les technologies utilisées dans ce projet :

* [Symfony](https://symfony.com/)
* [Twig](https://twig.symfony.com/)
* [Bootswatch](https://bootswatch.com/)  

##

***

## Installation

***

Pour pouvoir visualiser ou travailler sur ce projet :

1- Cloner le projet.

```sh
git clone "copier le lien github du projet"
```

2- Une fois le projet cloné, dans le dossier principal lancez la commande :

```sh
composer install
```

3- Créez un fichier .env.local où vous allez configurer votre base de données  :

```sh
DATABASE_URL="mysql://db_user:db_password@127.0.0.1:3306/db_name?serverVersion=5.7"
```

4. Lancez la commande qui va récupérer les données de la base :

```sh
bin/console doctrine:migrations:migrate
```

5. Pour remplir la base avec les fixtures :

```sh
bin/console doctrine:fixtures:load
```

Ca y est, vous êtes prêt à travailler sur le projet!



