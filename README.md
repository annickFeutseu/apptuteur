# Tuteur App - Gestion des étudiants et visites

Ce projet est une application Symfony permettant à un tuteur de gérer ses étudiants et leurs visites. L'application inclut la gestion des visites (ajout, modification, suppression, changement de statut) et des comptes-rendus, ainsi qu’un tableau de bord.

## Fonctionnalités principales

- Authentification des tuteurs
- Gestion des étudiants (ajout, modification, suppression)
- Gestion des visites des étudiants :
  - Ajouter, modifier, supprimer une visite
  - Changer le statut d’une visite (prevue, realisee, annulee)
  - Saisie d’un compte-rendu pour chaque visite
- Tableau de bord :
  - Liste des étudiants
  - Liste des prochaines visites avec possibilité de changer leur statut
- Export PDF des comptes-rendus

## Prérequis

- Docker et Docker Compose
- Git
- Symfony CLI (optionnel, pour tester en local hors Docker)

## Installation et test

1. **Cloner le projet :**

```bash
git clone <URL_DE_TON_PROJET>
cd <NOM_DU_REPO>
```

2. **Copier le fichier d'environnement et configurer la base de données :**
```bash
cp .env.example .env
```

Modifier .env si nécessaire pour la configuration Docker/MySQL.


3. **Lancer les conteneurs Docker :**
```bash
docker compose up -d
```

4. **Installer les dépendances PHP :**
```bash
docker compose exec php composer install
```

5. **Créer la base de données et exécuter les migrations :**
```bash
docker compose exec php php bin/console doctrine:database:create
docker compose exec php php bin/console doctrine:migrations:migrate
```

6. **Accéder à l’application :**
```bash
Ouvrir dans le navigateur : http://localhost:8000
```
Comme specifie dans le tp, vous pouvez creer ce compte en utilisant les api(swagger: localhost:8000/api):
{ 
    "nom": "Durand", 
    "prenom": "Alice", 
    "email": "alice.durand@example.com", 
    "telephone": "0601020304" 
}
Connectez vous avec cet email.

7. **Connexion :**
```bash
Un tuteur doit être présent dans la base ou créé via un fixture / admin panel pour tester l'application.
```

8. **Notes**
```bash
Les étudiants peuvent être ajoutés, modifiés ou supprimés.

Les visites peuvent être gérées avec le changement de statut et export de comptes-rendus en PDF.

La pagination est active sur la liste des étudiants dans le tableau de bord.

Structure du projet

src/Controller : contrôleurs Symfony (Les controller suivant concerne les TPs avant le Projet: MainController, TuteurController, FormController)

src/Entity : entités Doctrine

templates/ : vues Twig

migrations/ : fichiers de migration Doctrine

docker/ ou docker-compose.yml : configuration Docker
```
