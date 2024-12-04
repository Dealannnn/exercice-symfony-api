# Exercice Symfony API

## Description
Ce projet est une API Symfony réalisée dans le cadre de l'exercice 1 du test de recrutement Teach'r 2024. Il permet de gérer des produits et des catégories via une API RESTful.

## Installation

### Prérequis
- PHP 8.1 ou version supérieure
- Composer
- Symfony CLI (optionnel, mais recommandé)

### Étapes d'installation

1. Clonez le repository :
   ```bash
   git clone https://github.com/Dealannnn/exercice-symfony-api.git
   cd exercice-symfony-api
Installez les dépendances avec Composer :
composer install

Configurez votre base de données dans le fichier .env :
- Modifiez la variable DATABASE_URL pour correspondre à votre base de données.
  
Créez les tables de la base de données :
-php bin/console doctrine:migrations:migrate

Lancer le serveur Symfony :
-symfony server:start

-L'API est maintenant accessible à l'adresse suivante :
http://localhost:8000
