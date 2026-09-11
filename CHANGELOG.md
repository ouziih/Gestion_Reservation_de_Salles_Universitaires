# Changelog

## refactor-v0.2.0 - 2026-09-10

### Ajouté

- Configuration Eloquent et migrations MySQL
- Vérification PHP/Composer à chaque push GitHub
- Publication Docker Hub sur les tags `refactor-v*`

## v0.0.0 - 2026-09-05

### Ajouté

- Initialisation du dépôt Git
- Création de la branche `main`
- Ajout du fichier `.gitignore`
- Création du fichier `README.md`
- Création du fichier `CHANGELOG.md`
<<<<<<< HEAD
=======

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

## v0.5.0 - 2026-09-08

### Ajouté

- Création du contrat `ValidatorInterface`
- Création de `ValidationResult`
- Validation des salles avec `SalleValidator`
- Validation des réservations avec `ReservationValidator`
- Utilisation de Respect/Validation pour les règles de validation
- Vérification du format, de l’ordre et de la durée des dates
<<<<<<< HEAD
>>>>>>> refactor-v0.1.0/feature/05-validation
=======

## v0.6.0 - 2026-09-08

### Ajouté

- Création de `SalleDto` et `ReservationDto`
- Création de leurs builders respectifs
- Construction progressive des DTOs après validation
- Normalisation des dates de réservation en `DateTimeImmutable`
>>>>>>> feature/06-dto
