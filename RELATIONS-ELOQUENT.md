# Cours : les relations avec Eloquent

## 1. Pourquoi les relations existent

Une base relationnelle répartit les informations dans plusieurs tables.

Exemple :

```text
users
  id | name

posts
  id | title | user_id
```

La colonne `posts.user_id` indique quel utilisateur a écrit chaque article.

Une relation exprime donc un lien entre des enregistrements de tables
différentes.

Eloquent représente ce lien par une méthode dans un modèle PHP.

```text
table users      ↔ modèle User
table posts      ↔ modèle Post
```

Une relation Eloquent sert à retrouver les objets liés. Elle ne remplace pas
la clé étrangère SQL et ne contient pas les règles métier.

## 2. Vocabulaire indispensable

### Modèle

Une classe PHP représentant généralement une table.

```php
class User extends Model
{
}
```

### Clé primaire

Une colonne identifiant une ligne de manière unique.

```text
users.id
```

### Clé étrangère

Une colonne contenant l'identifiant d'une ligne située dans une autre table.

```text
posts.user_id
```

### Modèle parent et modèle enfant

Dans une relation, le modèle parent est généralement celui dont l'identifiant
est référencé. Le modèle enfant contient généralement la clé étrangère.

```text
User  -> parent
Post  -> enfant
```

## 3. Comment Eloquent déclare une relation

Une relation se déclare dans une méthode du modèle :

```php
public function posts()
{
    return $this->hasMany(Post::class);
}
```

Cette méthode ne retourne pas directement les articles. Elle retourne une
description de la relation, que le système Eloquent pourra utiliser pour
construire une requête.

Une fois la relation déclarée, Eloquent permet généralement deux usages :

```php
$user->posts();
$user->posts;
```

### Avec les parenthèses

```php
$user->posts()
    ->where('published', true)
    ->get();
```

On récupère l'objet de relation et on peut continuer à construire une requête.

### Sans les parenthèses

```php
$user->posts;
```

Eloquent interprète cet accès comme une demande des données liées.

```text
posts()
  -> définition de la relation et requête personnalisable

posts
  -> résultats liés
```

## 4. `hasOne` : un-à-un

### Définition

`hasOne` signifie :

> Un enregistrement possède un seul enregistrement lié.

Exemple : un utilisateur possède un seul profil.

Tables :

```text
users
  id

profiles
  id
  user_id
```

### Déclaration

Dans le modèle `User` :

```php
public function profile()
{
    return $this->hasOne(Profile::class);
}
```

### Utilisation

```php
$profile = $user->profile;
```

Eloquent cherche le profil dont `user_id` correspond à l'identifiant du user.

SQL conceptuel :

```sql
SELECT *
FROM profiles
WHERE user_id = 1
LIMIT 1;
```

### Relation inverse

Dans `Profile` :

```php
public function user()
{
    return $this->belongsTo(User::class);
}
```

La phrase devient :

```text
User hasOne Profile
Profile belongsTo User
```

## 5. `belongsTo` : appartenir à un autre modèle

### Définition

`belongsTo` signifie :

> L'enregistrement actuel est rattaché à un seul enregistrement d'un autre
> modèle.

La clé étrangère se trouve généralement dans la table du modèle actuel.

Exemple :

```text
posts.user_id -> users.id
```

### Déclaration

Dans `Post` :

```php
public function user()
{
    return $this->belongsTo(User::class);
}
```

### Utilisation

```php
$author = $post->user;
```

Eloquent lit `posts.user_id`, puis cherche la ligne correspondante dans
`users`.

SQL conceptuel :

```sql
SELECT *
FROM users
WHERE id = 1
LIMIT 1;
```

### Erreur fréquente

Il ne faut pas choisir `hasOne` ou `belongsTo` en regardant seulement le
français de la phrase. Il faut regarder où se trouve la clé étrangère.

```text
profiles.user_id -> Profile belongsTo User
users.id         -> User hasOne Profile
```

## 6. `hasMany` : un-à-plusieurs

### Définition

`hasMany` signifie :

> Un enregistrement peut être lié à plusieurs enregistrements d'un autre
> modèle.

Exemple :

```text
Un utilisateur peut écrire plusieurs articles.
```

Tables :

```text
users
  id | name

posts
  id | title | user_id
```

Données :

```text
users
1 | Awa

posts
10 | Article PHP | 1
11 | Article SQL | 1
```

L'utilisateur `1` possède les articles `10` et `11`.

### Déclaration

Dans `User` :

```php
public function posts()
{
    return $this->hasMany(Post::class);
}
```

### Utilisation

```php
$posts = $user->posts;
```

SQL conceptuel :

```sql
SELECT *
FROM posts
WHERE user_id = 1;
```

Le résultat est une collection. Elle peut être vide, contenir un élément ou
en contenir plusieurs.

### Relation inverse

Dans `Post` :

```php
public function user()
{
    return $this->belongsTo(User::class);
}
```

La paire complète est :

```text
User hasMany Post
Post belongsTo User
```

## 7. `belongsToMany` : plusieurs-à-plusieurs

### Définition

`belongsToMany` signifie :

> Plusieurs enregistrements du premier modèle peuvent être liés à plusieurs
> enregistrements du second modèle.

Exemple :

```text
Un étudiant suit plusieurs cours.
Un cours est suivi par plusieurs étudiants.
```

### Pourquoi une table pivot est nécessaire

Tables principales :

```text
students
  id | name

courses
  id | name
```

Un étudiant peut suivre plusieurs cours et un cours peut avoir plusieurs
étudiants. Une seule clé étrangère ne suffit donc pas.

On ajoute une table pivot :

```text
course_student
  student_id
  course_id
```

Exemple :

| student_id | course_id |
|---:|---:|
| 1 | 10 |
| 1 | 11 |
| 2 | 10 |

Lecture :

```text
L'étudiant 1 suit les cours 10 et 11.
L'étudiant 2 suit le cours 10.
```

### Déclaration

Dans `Student` :

```php
public function courses()
{
    return $this->belongsToMany(Course::class);
}
```

Dans `Course` :

```php
public function students()
{
    return $this->belongsToMany(Student::class);
}
```

### Utilisation

```php
$courses = $student->courses;
$students = $course->students;
```

### Table pivot non conventionnelle

Si le nom de la table pivot ne suit pas les conventions, on peut le préciser :

```php
return $this->belongsToMany(
    Course::class,
    'inscriptions',
    'student_id',
    'course_id'
);
```

Les arguments indiquent :

```text
1. le modèle lié ;
2. la table pivot ;
3. la clé du modèle actuel dans la pivot ;
4. la clé du modèle lié dans la pivot.
```

## 8. `hasOneThrough` : un-à-un indirect

### Définition

`hasOneThrough` signifie :

> Un modèle possède un seul modèle lié en passant par un modèle intermédiaire.

Exemple :

```text
Un mécanicien possède une voiture par l'intermédiaire d'un propriétaire.
```

Structure conceptuelle :

```text
Mechanic -> Owner -> Car
```

Déclaration :

```php
public function car()
{
    return $this->hasOneThrough(Car::class, Owner::class);
}
```

Cette relation évite de charger manuellement les deux relations intermédiaires.

## 9. `hasManyThrough` : plusieurs indirect

### Définition

`hasManyThrough` signifie :

> Un modèle possède plusieurs modèles en passant par un modèle intermédiaire.

Exemple :

```text
Un pays possède plusieurs articles écrits par ses auteurs.
```

Structure :

```text
Country -> Authors -> Posts
```

Déclaration :

```php
public function posts()
{
    return $this->hasManyThrough(Post::class, Author::class);
}
```

Eloquent traverse les auteurs pour retrouver les articles du pays.

## 10. Relations polymorphiques

### Définition

Une relation polymorphique permet à une même table de se rattacher à
plusieurs types de modèles.

Exemple :

```text
Un commentaire peut appartenir à un article ou à une vidéo.
```

La table `comments` contient généralement :

```text
commentable_id
commentable_type
```

Exemple :

| id | commentable_id | commentable_type |
|---:|---:|---|
| 1 | 5 | Article |
| 2 | 8 | Video |

Le type indique quelle table ou quel modèle doit être recherché.

## 11. `morphTo` : côté polymorphique qui appartient

Dans `Comment` :

```php
public function commentable()
{
    return $this->morphTo();
}
```

Utilisation :

```php
$parent = $comment->commentable;
```

Le résultat peut être un `Article` ou une `Video`, selon la valeur de
`commentable_type`.

## 12. `morphOne` et `morphMany`

### `morphOne`

Un modèle possède un seul élément polymorphique.

Exemple :

```text
Un article ou une vidéo possède une image principale.
```

Dans `Article` :

```php
public function image()
{
    return $this->morphOne(Image::class, 'imageable');
}
```

### `morphMany`

Un modèle possède plusieurs éléments polymorphiques.

Exemple :

```text
Un article ou une vidéo possède plusieurs commentaires.
```

Dans `Article` et `Video` :

```php
public function comments()
{
    return $this->morphMany(Comment::class, 'commentable');
}
```

La table `comments` est partagée par plusieurs types de modèles.

## 13. `morphToMany` et `morphedByMany`

Ces relations représentent un plusieurs-à-plusieurs polymorphique.

Exemple :

```text
Un article ou une vidéo possède plusieurs tags.
Un tag peut être utilisé sur plusieurs articles et vidéos.
```

Dans `Post` :

```php
public function tags()
{
    return $this->morphToMany(Tag::class, 'taggable');
}
```

Dans `Tag` :

```php
public function posts()
{
    return $this->morphedByMany(Post::class, 'taggable');
}
```

La table pivot contient généralement :

```text
tag_id
taggable_id
taggable_type
```

## 14. Modifier les clés utilisées

Eloquent utilise des conventions. Si les noms sont différents, on peut fournir
les clés manuellement.

```php
return $this->hasMany(
    Post::class,
    'author_identifier',
    'identifier'
);
```

Pour `hasMany`, les paramètres sont :

```text
1. modèle lié ;
2. clé étrangère dans la table liée ;
3. clé locale dans la table actuelle.
```

Pour `belongsTo` :

```php
return $this->belongsTo(
    User::class,
    'author_identifier',
    'identifier'
);
```

Les paramètres sont :

```text
1. modèle parent ;
2. clé étrangère dans le modèle actuel ;
3. clé référencée dans le modèle parent.
```

## 15. Relation Eloquent et clé étrangère SQL

Ce sont deux mécanismes différents.

La migration :

```php
$table->foreignId('user_id')->constrained('users');
```

crée la colonne et la contrainte dans la base.

Le modèle :

```php
public function user()
{
    return $this->belongsTo(User::class);
}
```

indique à Eloquent comment naviguer entre les objets.

```text
Migration
  -> structure et intégrité dans MySQL

Relation Eloquent
  -> navigation entre modèles PHP
```

Une relation Eloquent ne remplace donc pas une contrainte SQL.

## 16. Chargement différé

Le chargement différé signifie que la relation est récupérée lorsqu'on y
accède.

```php
$user = User::find(1);
$posts = $user->posts;
```

Première demande :

```sql
SELECT *
FROM users
WHERE id = 1
LIMIT 1;
```

Puis, lors de l'accès à `posts` :

```sql
SELECT *
FROM posts
WHERE user_id = 1;
```

La relation n'est donc pas chargée tant qu'elle n'est pas demandée.

## 17. Chargement anticipé avec `with`

Le chargement anticipé demande une relation au moment où les modèles
principaux sont récupérés :

```php
$users = User::with('posts')->get();
```

`with('posts')` signifie :

> Récupère les utilisateurs et prépare aussi leurs articles liés.

Sans chargement anticipé, une boucle peut produire :

```text
1 requête pour les users
1 requête pour les posts du user 1
1 requête pour les posts du user 2
1 requête pour les posts du user 3
```

Pour trois users :

```text
1 + 3 = 4 requêtes
```

Avec le chargement anticipé, Eloquent peut regrouper les articles liés :

```text
1 requête pour les users
1 requête pour tous les posts concernés
```

Le total est alors généralement de deux requêtes.

Le premier cas est le problème **N+1** :

```text
1 requête principale + N requêtes liées
```

`with()` ne crée pas la relation. Il contrôle le moment où elle est chargée.

## 18. Relations imbriquées

On peut demander plusieurs niveaux de relations :

```php
$users = User::with('posts.comments')->get();
```

Cela signifie :

```text
charger les users
charger leurs posts
charger les commentaires de ces posts
```

La relation doit exister dans chaque modèle traversé.

## 19. Filtrer selon une relation

Pour demander les utilisateurs qui possèdent au moins un article publié :

```php
$users = User::whereHas('posts', function ($query) {
    $query->where('published', true);
})->get();
```

`whereHas` filtre le modèle principal en fonction de l'existence de données
dans une relation.

Différence :

```text
with
  -> charger une relation avec les résultats

whereHas
  -> filtrer les résultats selon une relation
```

## 20. Les relations dans le projet de réservation

La base contient :

```text
salles.id
reservations.salle_id
```

Une salle peut posséder plusieurs réservations :

```php
public function reservations()
{
    return $this->hasMany(Reservation::class);
}
```

Une réservation appartient à une salle :

```php
public function salle()
{
    return $this->belongsTo(Salle::class);
}
```

Cette relation est un-à-plusieurs, pas plusieurs-à-plusieurs :

```text
Salle 1 -> Reservation 10
       -> Reservation 11

Reservation 10 -> Salle 1 uniquement
```

Il n'y a donc pas besoin de table pivot.

Les relations ne vérifient pas :

```text
si la salle est active
si la date est future
si la durée dépasse quatre heures
si une période chevauche une autre
```

Ces règles appartiennent au service métier.

## 21. Tableau récapitulatif

| Relation | Signification | Structure habituelle |
|---|---|---|
| `hasOne` | un possède un | clé étrangère dans l'autre table |
| `belongsTo` | un appartient à un | clé étrangère dans la table actuelle |
| `hasMany` | un possède plusieurs | clé étrangère dans la table liée |
| `belongsToMany` | plusieurs possèdent plusieurs | table pivot |
| `hasOneThrough` | un possède un via un intermédiaire | relation indirecte |
| `hasManyThrough` | un possède plusieurs via un intermédiaire | relation indirecte |
| `morphTo` | appartient à plusieurs types possibles | id et type polymorphiques |
| `morphOne` | possède un élément polymorphique | id et type polymorphiques |
| `morphMany` | possède plusieurs éléments polymorphiques | id et type polymorphiques |
| `morphToMany` | plusieurs-à-plusieurs polymorphique | table pivot polymorphique |
| `morphedByMany` | inverse d'une relation polymorphique multiple | table pivot polymorphique |

## 22. Méthode pour choisir une relation

Pour choisir la relation, pose ces questions :

```text
1. Combien d'éléments A peuvent être liés à B ?
2. Combien d'éléments B peuvent être liés à A ?
3. Où se trouve la clé étrangère ?
4. Faut-il une table pivot ?
5. Les deux modèles sont-ils du même type ou plusieurs types différents ?
```

Exemples :

```text
Un user possède un profil
  -> hasOne / belongsTo

Un user possède plusieurs posts
  -> hasMany / belongsTo

Un étudiant suit plusieurs cours et inversement
  -> belongsToMany

Un commentaire appartient à un article ou une vidéo
  -> relation polymorphique
```
