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
