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

## v0.5.0 - 2026-09-08

### Ajouté

- Création du contrat `ValidatorInterface`
- Création de `ValidationResult`
- Validation des salles avec `SalleValidator`
- Validation des réservations avec `ReservationValidator`
- Utilisation de Respect/Validation pour les règles de validation
- Vérification du format, de l’ordre et de la durée des dates

## v0.6.0 - 2026-09-08

### Ajouté

- Création de `SalleDto` et `ReservationDto`
- Création de leurs builders respectifs
- Construction progressive des DTOs après validation
- Normalisation des dates de réservation en `DateTimeImmutable`

## v0.7.0 - 2026-09-08

### Ajouté

- Création des contrats `SalleRepositoryInterface` et `ReservationRepositoryInterface`
- Création des implémentations Eloquent des repositories
- Centralisation de la lecture et de l’écriture des salles et réservations
- Ajout de la recherche des réservations confirmées qui se chevauchent

## v0.8.0 - 2026-09-09

### Ajouté

- Création de `CreerReservationService`
- Création de `AnnulerReservationService`
- Création de `SalleIndisponibleException`
- Création de `ReservationIntrouvableException`
- Centralisation des règles métier de création et d’annulation

## v0.9.0 - 2026-09-09

### Ajouté

- Création de `SalleController` et `ReservationController`
- Création des vues des salles, réservations et erreurs
- Ajout des formulaires avec affichage des erreurs par champ
- Ajout des redirections après les requêtes POST réussies
- Intégration du layout commun pour le rendu des vues

## v0.10.0 - 2026-09-09

### Ajouté

- Déclaration des routes dans `routes/Routes.php`
- Création du dispatcher FastRoute dans `routes/Web.php`
- Gestion des réponses 404 et 405
- Transmission des paramètres dynamiques aux contrôleurs
- Documentation de FastRoute et réponses aux questions de l'étape 10