# Projet mini-Pinterest


## 📸 À propos :

Ceci est un projet réalisé dans le cadre de l'enseignement Base de Données et Programmation Web à l'université Claude Bernard Lyon 1, au printemps 2021. Ce projet consiste à développer une petite application web permettant à un utilisateur d'accéder et de manipuler le contenu d'une base de données de photos, elles-mêmes classées en différentes catégories. L'utilisateur pourra également se connecter sur l'application.

## 🌐 Comment accéder à l'application ? 

Il n'est plus possible d'y accéder car le serveur de l'université sur lequel était hébergé l'application a été supprimé.

## 💻 L'environnement de travail :

Le projet a été codé en **PHP** pour manipuler la base de données et en **HTML/CSS** pour afficher le site web. Pour gérer notre base de données nous avons utilisé **PhpMyAdmin** en faisant des requêtes **SQL**.

La base de données est schématisée de cette façon :
> Categorie(*catId*, nomCat)  
> Photo(*photoId*, nomFich, description, #catId, pseudo)  
> Utilisateur(*pseudo*, mdp, etat, role)

## 🔖 Organisation de l'archive : 
```
css/
├─ Fichiers de style CSS
doc/
├─ Documentation du projet avec les consignes, la présentation, etc...
image/
├─ Images utilisées de base
php/
├─ Fonctions php utilisées sur plusieurs pages
sql/
├─ Données initiales de notre base de données
README.md
ajouter.php
connexion.php
detail.php
index.php
inscription.php
profilAdmin.php
profilUtilisateur.php
```

## 📚 Documentation :
* Affichage de la galerie d'images de [manière responsive](https://masonry.desandro.com/) (index.php)
* Utilisation de la fonction [onClick](https://developer.mozilla.org/fr/docs/Web/API/GlobalEventHandlers/onclick) pour certains boutons
* [Bibliothèque d'icons (fontawesome)](https://fontawesome.com/)

## 👨‍🎓👩‍🎓 Étudiants : 

* A. V.
* Cécilia NGUYEN
