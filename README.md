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

<<<<<<< HEAD
## Base de données et migrations

Les valeurs de connexion sont fournies aux conteneurs par `env_file: .env` dans
`docker-compose.yml`. Copiez d'abord le modèle local :

```bash
cp .env.example .env
docker compose up -d mysql
docker compose run --rm php php database/migrate.php
```

Les migrations sont idempotentes : elles peuvent être relancées sans recréer
les tables `salles` et `reservations`.

Le lanceur `bin/database` exécute les fichiers selon leur préfixe numérique :

```bash
./bin/database migrate all
./bin/database migrate 001_create_salles_table
```

## Publication Docker Hub

Après avoir configuré les secrets GitHub `DOCKERHUB_USERNAME` et
`DOCKERHUB_TOKEN`, poussez un tag au format `refactor-vX.Y.Z`. GitHub Actions
construit alors l'image et publie le même tag dans `ouziih/reservation-salles`.

```bash
git tag refactor-v0.2.0
git push origin refactor-v0.2.0
```
=======
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

## Données initiales

Pour insérer les cinq salles initiales sans créer de doublons :

```bash
php database/seed.php
```

Le script peut être exécuté plusieurs fois grâce à `firstOrCreate`.

## Validation

Les données d’une salle ou d’une réservation sont validées avec les classes
du dossier `src/Validation/`. Chaque validateur retourne un
`ValidationResult` :

```php
$resultat = $validator->validate($data);

if (!$resultat->isValid()) {
    $errors = $resultat->errors();
}
```

`SalleValidator` vérifie les champs d’une salle. `ReservationValidator`
vérifie les champs d’une réservation, le format des dates, leur ordre et la
durée maximale de quatre heures.
<<<<<<< HEAD
>>>>>>> refactor-v0.1.0/feature/05-validation
=======

## DTO et builders

Les données validées peuvent être transformées en objets de transfert :

- `SalleDto` avec `SalleDtoBuilder` ;
- `ReservationDto` avec `ReservationDtoBuilder`.

Les builders construisent progressivement les DTOs et refusent de créer un
objet si un champ obligatoire manque. Les DTOs sont ensuite transmis aux
couches métier sans dépendre directement des données HTTP.
>>>>>>> feature/06-dto
