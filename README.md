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

## DTO et builders

Les données validées peuvent être transformées en objets de transfert :

- `SalleDto` avec `SalleDtoBuilder` ;
- `ReservationDto` avec `ReservationDtoBuilder`.

Les builders construisent progressivement les DTOs et refusent de créer un
objet si un champ obligatoire manque. Les DTOs sont ensuite transmis aux
couches métier sans dépendre directement des données HTTP.