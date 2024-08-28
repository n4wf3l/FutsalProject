# Plateforme de Club de Futsal/Football

## Introduction

Bienvenue sur la plateforme de gestion de club de futsal/football. Cette application web, développée avec **Laravel 11** et **Tailwind CSS**, est conçue pour répondre aux besoins des clubs de futsal/football en permettant à la fois aux fans et aux membres du staff du club de suivre et de mettre à jour toutes les informations importantes du club. La plateforme offre une expérience personnalisée et dynamique, avec une multitude de fonctionnalités pour gérer et afficher les données du club.

## Fonctionnalités

### Page d'Accueil Dynamique
- **Messages personnalisés** : Affichage de messages personnalisés pour les utilisateurs.
- **Personnalisation du frontend** : Possibilité de personnaliser l'apparence de la page d'accueil selon les besoins du club.

### API de Température
- **Température actuelle** : Affichage de la température actuelle de la ville où se situe le club.

### News et Médias
- **News** : Publication et gestion des actualités du club.
- **Vidéos** : Intégration et affichage des vidéos relatives au club.
- **Communiqués** : Gestion et affichage des communiqués officiels du club.
- **Galerie de Photos** : Upload et gestion des photos par le photographe du club.

### Page À Propos
- **Sections personnalisables** : Affichage des informations à propos du club sous forme de sections personnalisables.
- **API de Localisation** : Intégration de Leaflet OpenStreetMap pour afficher la localisation du club.
- **Téléchargement de PDF** : Possibilité de télécharger des documents PDF (réglementation, présentation du club, etc.) ajoutés par l'administrateur.

### Page Compétition
- **Automatisation API en direct** : Gestion et planification des matchs avec mise à jour automatique du classement.
- **Ajout d'équipes** : Possibilité d'ajouter des équipes dans la compétition, avec leur logo respectif.
- **Filtre des matchs** : Filtrer les prochains matchs et les anciens matchs du club local.
- **Classement du championnat** : Possibilité de fetch le classement d'autres projets.

### Page Équipe
- **Équipe professionnelle et équipe B** : Gestion des joueurs, entraîneurs, et staff technique & médical avec des opérations CRUD.

### Page Sponsors
- **CRUD Sponsors** : Gestion des sponsors du club.

### Fanshop et Billetterie
- **Gestion des tribunes** : Ajout de tribunes avec leurs prix et photos.
- **Vente de billets** : Achat de billets avec redirection vers Stripe pour le paiement.
- **Confirmation de paiement** : Génération d'une page de succès avec code QR, document PDF, et envoi d'un email de confirmation.

### Personnalisation du Site
- **Configuration manuelle** : Personnalisation des couleurs, nom du site, logo, emplacement du club, réseaux sociaux, etc.
- **Images de fond** : Possibilité d'ajouter des images de fond dans le background des pages.
- **Gestion des inscriptions** : Activation ou désactivation de l'inscription pour les visiteurs.

### Multilingue
- **Langues** : La plateforme est disponible en plusieurs langues avec l'anglais comme langue principale.

## Technologies Utilisées

- **Framework Backend** : Laravel 11
- **Framework Frontend** : Tailwind CSS
- **Base de Données** : MySQL
- **API** : Intégration avec l'API OpenStreetMap, API de météo, et autres services nécessaires.
- **Paiement** : Intégration avec Stripe pour le paiement des billets.
- **Multilingue** : Gestion de la traduction et de l'internationalisation.

## Installation

1. Clonez le dépôt : `git clone [URL du dépôt]`
2. Installez les dépendances : `composer install` et `npm install`
3. Configurez les variables d'environnement : Créez un fichier `.env` en vous basant sur le `.env.example`.
4. Compilez les assets front-end : `npm run dev` ou `npm run build` pour la production.
5. Lancez le serveur de développement : `php artisan serve`
6. Accédez à la plateforme via `http://localhost:8000` ou l'URL configurée.

## Utilisation

1. **Connexion Administrateur** : Connectez-vous avec les identifiants administrateurs pour gérer la plateforme.
2. **Personnalisation** : Accédez à la section de configuration pour personnaliser le site selon les besoins du club.
3. **Gestion des Pages** : Utilisez les différentes sections du back-office pour gérer les équipes, matchs, sponsors, galeries, etc.

## Contribution

Les contributions sont les bienvenues ! Si vous souhaitez contribuer à ce projet, veuillez suivre les étapes suivantes :
1. Forkez le dépôt.
2. Créez une branche pour votre fonctionnalité : `git checkout -b ma-nouvelle-fonctionnalité`.
3. Commitez vos modifications : `git commit -m 'Ajouter une nouvelle fonctionnalité'`.
4. Poussez la branche : `git push origin ma-nouvelle-fonctionnalité`.
5. Créez une Pull Request.

## Licence

Ce projet est sous licence MIT. Veuillez consulter le fichier LICENSE pour plus de détails.

## Remerciements

Merci à toutes les personnes ayant contribué au développement de cette plateforme.
