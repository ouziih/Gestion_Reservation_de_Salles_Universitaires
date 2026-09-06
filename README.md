# Gestion des réservations de salles universitaires

Application PHP orientée objet permettant de consulter les salles
universitaires et de gérer leurs réservations.

## État du projet

Projet en cours de développement.

## Technologies prévues

- PHP orienté objet
- MySQL
- Composer
- Eloquent
- FastRoute
- PHP-DI
- Respect/Validation

## Configuration locale

Créer un fichier `.env` à partir de `.env.example`, puis renseigner les
paramètres de connexion à MySQL.

Le fichier `.env` reste local et ne doit pas être versionné.

## Création des tables

Après avoir créé la base `reservation_salles`, exécuter les migrations :

```bash
php database/migrations/001_create_salles_table.php
php database/migrations/002_create_reservations_table.php
```

Ces scripts créent les tables `salles` et `reservations` avec Eloquent.