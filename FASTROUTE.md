# FastRoute : comprendre le routage HTTP

## 1. Le problème que FastRoute résout

Une application web reçoit une requête HTTP. Cette requête contient notamment :

- une méthode HTTP : `GET`, `POST`, etc. ;
- une URI : `/salles`, `/salles/12`, `/reservations`.

PHP reçoit ces informations, mais ne sait pas automatiquement quelle méthode
de contrôleur doit être exécutée.

Par exemple, l'application doit faire correspondre :

```text
GET /salles
```

avec :

```php
SalleController::index()
```

Ou encore :

```text
GET /salles/12
```

avec :

```php
SalleController::show(12)
```

Cette association entre une requête et une action s'appelle le **routage**.

```text
requête HTTP
    ↓
routeur
    ↓
action du contrôleur
```

FastRoute est une bibliothèque qui réalise la recherche de cette
correspondance.

## 2. Ce que FastRoute fait et ne fait pas

FastRoute :

- enregistre des routes ;
- compare une méthode et un chemin avec ces routes ;
- détecte les paramètres dynamiques ;
- indique si une route existe ;
- indique si la méthode HTTP est autorisée ;
- retourne le handler correspondant.

FastRoute ne :

- construit pas les contrôleurs ;
- n'injecte pas leurs dépendances ;
- ne valide pas les données d'un formulaire ;
- n'interroge pas la base de données ;
- n'applique pas les règles métier ;
- ne génère pas les vues.

Son rôle s'arrête essentiellement à cette question :

> Quelle action correspond à cette requête HTTP ?

## 3. Une route

Une route associe trois éléments :

```text
méthode HTTP + chemin + handler
```

Exemple :

```php
[
    'GET',
    '/salles',
    [SalleController::class, 'index'],
]
```

Cette définition signifie :

```text
si la requête est GET /salles,
alors l'action est SalleController::index.
```

### La méthode HTTP

La méthode fait partie de l'identité de la route.

Ces deux routes sont différentes :

```text
GET  /salles
POST /salles
```

Dans notre application :

- `GET /salles` affiche les salles ;
- `POST /salles` enregistre une salle.

### Le chemin

Le chemin identifie la ressource demandée :

```text
/salles
/salles/create
/reservations
```

### Le handler

Le handler décrit la classe et la méthode qui devront être utilisées :

```php
[SalleController::class, 'index']
```

Il ne construit pas encore le contrôleur. C'est simplement une description :

```text
classe : SalleController
méthode : index
```

## 4. Notre fichier `routes/Routes.php`

Dans notre projet, les routes sont d'abord regroupées dans un tableau :

```php
return [
    ['GET', '/', [SalleController::class, 'index']],
    ['GET', '/salles', [SalleController::class, 'index']],
    ['POST', '/salles', [SalleController::class, 'store']],
    ['GET', '/salles/{id:\d+}', [SalleController::class, 'show']],
];
```

Ce fichier ne traite aucune requête. Il ne fait que décrire les routes.

Cette séparation évite de mélanger :

```text
la liste des routes
```

avec :

```text
le fonctionnement du dispatcher
```

## 5. Route statique et route dynamique

### Route statique

```php
['GET', '/salles', [SalleController::class, 'index']]
```

Elle correspond uniquement à :

```text
/salles
```

Elle ne correspond pas à :

```text
/salles/12
/salles/create
```

### Route dynamique

```php
['GET', '/salles/{id:\d+}', [SalleController::class, 'show']]
```

La partie :

```text
{id:\d+}
```

se lit ainsi :

```text
nom du paramètre : id
contrainte        : \d+
```

`\d+` signifie « un ou plusieurs chiffres ».

La route accepte donc :

```text
/salles/1
/salles/12
/salles/250
```

Mais elle refuse :

```text
/salles/abc
```

La contrainte permet à FastRoute d'écarter un chemin qui ne correspond pas à
la forme attendue d'un identifiant.

## 6. Pourquoi `create` doit être placé avant `{id}`

Ces deux chemins peuvent sembler proches :

```text
/salles/create
/salles/{id:\d+}
```

Grâce à la contrainte `\d+`, `create` ne peut pas être pris pour un identifiant.
Il ne correspond donc pas à la route dynamique.

La contrainte joue ici un rôle de filtrage :

```text
create → route /salles/create
12     → route /salles/{id:\d+}
```

## 7. Le dispatcher

Le **dispatcher** est l'objet qui cherche une correspondance entre :

```text
méthode HTTP
chemin demandé
```

et les routes enregistrées.

Conceptuellement :

```php
$route = $dispatcher->dispatch('GET', '/salles');
```

Le dispatcher compare la requête avec les routes connues et retourne un
résultat.

Dans notre application, `routes/Web.php` :

1. charge le tableau de `routes/Routes.php` ;
2. crée le dispatcher FastRoute ;
3. enregistre chaque route ;
4. transmet la requête au dispatcher ;
5. interprète le résultat ;
6. appelle le contrôleur lorsqu'une route est trouvée.

## 8. Enregistrer les routes avec une boucle

FastRoute reçoit les routes via un `RouteCollector`.

Une route pourrait être enregistrée directement ainsi :

```php
$routes->addRoute(
    'GET',
    '/salles',
    [SalleController::class, 'index'],
);
```

Mais notre projet stocke les définitions dans un tableau. Nous les enregistrons
donc avec une seule boucle :

```php
foreach ($routeDefinitions as [$method, $path, $handler]) {
    $routes->addRoute($method, $path, $handler);
}
```

À chaque tour :

```text
$method  → méthode HTTP
$path    → chemin
$handler → action à appeler
```

La boucle transforme nos définitions en routes connues par FastRoute.

## 9. Les trois résultats du dispatcher

FastRoute retourne un résultat dont le premier élément indique la situation.

### `FOUND`

La route existe et la méthode HTTP est autorisée.

Pour :

```text
GET /salles
```

le résultat contient notamment :

```php
[
    Dispatcher::FOUND,
    [SalleController::class, 'index'],
    [],
]
```

Le troisième élément contient les paramètres dynamiques. Il est vide ici,
car `/salles` ne contient aucun paramètre.

### `NOT_FOUND`

Le chemin demandé ne correspond à aucune route.

Exemple :

```text
GET /inconnue
```

L'application doit répondre :

```text
404 Not Found
```

Une réponse 404 signifie :

> Aucun chemin correspondant à cette ressource n'a été trouvé.

### `METHOD_NOT_ALLOWED`

Le chemin existe, mais la méthode utilisée n'est pas autorisée.

Exemple :

```text
PUT /salles
```

si seules ces méthodes sont déclarées :

```text
GET /salles
POST /salles
```

L'application doit répondre :

```text
405 Method Not Allowed
```

et ajouter :

```http
Allow: GET, POST
```

La différence est donc :

```text
404 → le chemin n'existe pas
405 → le chemin existe, mais la méthode n'est pas autorisée
```

## 10. Les paramètres dynamiques

Pour la route :

```php
['GET', '/salles/{id:\d+}', [SalleController::class, 'show']]
```

et la requête :

```text
GET /salles/12
```

FastRoute retourne conceptuellement :

```php
[
    Dispatcher::FOUND,
    [SalleController::class, 'show'],
    ['id' => '12'],
]
```

Le paramètre retourné par une URL est une chaîne de caractères :

```php
'12'
```

Le routeur le convertit en entier avant d'appeler le contrôleur :

```php
$controller->show(12);
```

Le routeur ne doit pas transmettre un tableau de paramètres au contrôleur si
la méthode attend un argument simple :

```php
// correct
$controller->show(12);

// incorrect dans notre cas
$controller->show(['id' => '12']);
```

## 11. Le handler et le conteneur

FastRoute retourne un handler :

```php
[SalleController::class, 'show']
```

FastRoute ne sait pas comment construire `SalleController`.

Or notre contrôleur possède des dépendances :

```php
public function __construct(
    SalleRepositoryInterface $salles,
    SalleValidator $validator,
) {
}
```

Le contrôleur ne peut donc pas être construit correctement avec :

```php
new SalleController();
```

Le routeur demande au conteneur :

```php
$controller = $container->get($handler[0]);
```

Le conteneur construit alors le contrôleur et injecte ses dépendances.

La chaîne est :

```text
FastRoute
    retourne le handler

conteneur
    construit le contrôleur

routeur
    appelle la méthode du contrôleur
```

FastRoute trouve l'action ; le conteneur construit l'objet nécessaire pour
l'exécuter.

## 12. Le rôle de `routes/Web.php`

Dans notre organisation :

```text
routes/Routes.php
    décrit les routes

routes/Web.php
    exécute le routage
```

`Web.php` réalise les opérations suivantes :

```text
1. charger les dépendances ;
2. créer le conteneur nécessaire ;
3. charger Routes.php ;
4. créer le dispatcher ;
5. enregistrer les routes ;
6. lire la requête ;
7. supprimer la query string du chemin ;
8. appeler dispatch() ;
9. traiter FOUND, NOT_FOUND et METHOD_NOT_ALLOWED ;
10. récupérer puis appeler le contrôleur.
```

## 13. Query string et chemin

Une URL peut contenir une query string :

```text
/reservations?salle_id=2
```

Le chemin est :

```text
/reservations
```

La query string est :

```text
salle_id=2
```

FastRoute doit recevoir seulement le chemin :

```php
/reservations
```

Il ne faut pas lui transmettre :

```php
/reservations?salle_id=2
```

La query string et les paramètres de route sont deux choses différentes :

```text
/salles/12
```

`12` est un paramètre dynamique de route.

```text
/salles?page=2
```

`page=2` est un paramètre de query string.

## 14. Le chemin complet d'une requête

Prenons :

```text
GET /salles/12
```

Le traitement est :

```text
1. Le navigateur envoie GET /salles/12.
2. public/index.php démarre l'application.
3. routes/Web.php récupère le chemin /salles/12.
4. FastRoute compare ce chemin avec Routes.php.
5. FastRoute trouve [SalleController::class, 'show'].
6. FastRoute retourne ['id' => '12'].
7. Le conteneur construit SalleController.
8. Le routeur convertit '12' en 12.
9. Le routeur appelle SalleController::show(12).
10. Le contrôleur utilise le repository.
11. Le contrôleur rend la vue.
```

Schéma :

```text
navigateur
    ↓
public/index.php
    ↓
routes/Web.php
    ↓
FastRoute
    ↓
handler + paramètres
    ↓
conteneur
    ↓
SalleController::show(12)
    ↓
repository
    ↓
vue
```

## 15. Pourquoi ne pas construire les contrôleurs dans `Routes.php` ?

Il ne faut pas écrire :

```php
new SalleController(...)
```

dans le fichier des routes.

Le fichier des routes doit seulement décrire les chemins et les actions. La
construction des objets appartient au conteneur.

Cette séparation permet :

- de garder les routes lisibles ;
- de centraliser l'injection des dépendances ;
- de remplacer une implémentation sans modifier les routes ;
- d'éviter que le fichier des routes connaisse tous les constructeurs.

## 16. Répartition des responsabilités

```text
public/index.php
    démarre l'application

Routes.php
    décrit les routes

Web.php
    organise le dispatch HTTP

FastRoute
    recherche la route correspondante

conteneur
    construit les contrôleurs

contrôleur
    coordonne la requête

service
    applique les règles métier

repository
    accède aux données

vue
    affiche le résultat
```

La phrase essentielle à retenir est :

> `Routes.php` décrit, `Web.php` orchestre, FastRoute cherche, le conteneur
> construit et le contrôleur agit.

## 17. Réponses aux questions de l'étape 10

### 1. Pourquoi FastRoute ne construit-il pas lui-même le contrôleur ?

FastRoute est un routeur. Son rôle est de trouver la route qui correspond à
la méthode HTTP et au chemin demandé. Il retourne donc un handler, par
exemple :

```php
[SalleController::class, 'show']
```

Il ne connaît pas les dépendances du contrôleur et ne sait pas comment les
construire. Dans notre projet, `SalleController` dépend notamment d'un
repository et d'un validateur. Le conteneur d'injection de dépendances est
responsable de construire le contrôleur et de lui fournir ces dépendances.

Séparer les deux responsabilités donne :

```text
FastRoute → trouve l'action
conteneur → construit le contrôleur
```

### 2. Quelle différence existe entre 404 et 405 ?

Une réponse **404 Not Found** signifie qu'aucune route ne correspond au chemin
demandé.

Exemple :

```text
GET /chemin-inconnu
```

Une réponse **405 Method Not Allowed** signifie que le chemin existe, mais que
la méthode HTTP utilisée n'est pas autorisée.

Exemple, si seules `GET /salles` et `POST /salles` sont définies :

```text
PUT /salles
```

Dans ce cas, l'application doit également envoyer l'en-tête :

```http
Allow: GET, POST
```

### 3. Pourquoi contraindre `{id}` avec `\d+` ?

Dans une route comme :

```php
/salles/{id:\d+}
```

`id` est un paramètre dynamique et `\d+` impose qu'il contienne un ou
plusieurs chiffres.

La route accepte :

```text
/salles/12
```

mais refuse :

```text
/salles/abc
```

Cette contrainte vérifie la forme de l'identifiant avant que la requête
atteigne le contrôleur. Elle ne remplace pas la recherche en base : une URL
comme `/salles/999` peut avoir une forme correcte tout en correspondant à une
salle inexistante. Le contrôleur ou le repository traitera alors cette
situation avec une réponse 404.

### 4. Quel composant doit interpréter le handler retourné ?

Le routeur de notre application, dans `routes/Web.php`, interprète le handler
retourné par FastRoute.

Pour un handler comme :

```php
[SalleController::class, 'show']
```

`Web.php` demande d'abord au conteneur l'instance de `SalleController`, puis
appelle sa méthode `show` avec les paramètres dynamiques :

```text
FastRoute → retourne le handler et les paramètres
Web.php   → demande le contrôleur au conteneur
Web.php   → appelle la méthode du handler
```

FastRoute ne construit donc pas le contrôleur et le conteneur n'effectue pas
le routage. Chacun intervient dans sa propre responsabilité.
