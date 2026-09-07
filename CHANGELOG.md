# Changelog

## v0.0.0 - 2026-09-05

### Ajouté

- Initialisation du dépôt Git
- Création de la branche `main`
- Ajout du fichier `.gitignore`
- Création du fichier `README.md`
- Création du fichier `CHANGELOG.md`

## v0.2.0 - 2026-09-06

### Ajouté

- Configuration des variables d'environnement avec Dotenv
- Connexion MySQL avec Capsule et Eloquent
- Création des tables `salles` et `reservations`

## v0.3.0 - 2026-09-06

### Ajouté

- Création des modèles Eloquent `Salle` et `Reservation`
- Association explicite des modèles avec leurs tables
- Configuration des attributs assignables avec `$fillable`
- Configuration des conversions de types avec `$casts`
- Ajout de la relation entre les salles et les réservations

## v0.4.0 - 2026-09-06

### Ajouté

- Création du script `database/seed.php`
- Ajout de cinq salles initiales
- Prévention des doublons avec `firstOrCreate`