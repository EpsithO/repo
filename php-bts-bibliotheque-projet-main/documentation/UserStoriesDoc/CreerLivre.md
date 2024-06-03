# User Stories
#### Code source
* [Class de création de livre](../../src/UserStories/CreerLivre/CreerLivre.php)
* [Class de requête](../../src/UserStories/CreerLivre/CreerLivreRequete.php)
* [Class mère du média](../../src/entity/Media.php)
* [Class de l'objet livre](../../src/entity/Livre.php)

## Créer livre
``` Voici comment créer un livre et l'insérer dans la base de données ```
### Description
``
Cette User Story va  permettre de créer un livre et de l'insérer en base de données.
Les conditions d'insertion sont les suivantes: l'ISBN du livre doit être unique et donc 
ne pas déjà être en base de données, et le livre inséré aura comme statut "Nouveau".
``
### Paramètres en entrée de la Class principale. Il y aura une injection de dépendances
_Ces paramètres sont les dépendances nécessaires à la création du magazine_
```php
    private EntityManagerInterface $entityManager;
    private ValidatorInterface $validator;
    private Validateur $validateurBDD;
```
### Paramètres en entrée par l'utilisateur dans la requête
_Ces paramètres sont ceux entrés par l'utilisateur et qui vont donc être insérés dans la Class de la requête_
````php
    public string $titreLivre;
    public string $isbnLivre;
    public string $auteurLivre;
    public int $nombrePagesLivre;
    public string $dateParutionLivre;

````
### Sortie
``
A la fin de l'éxécution de l'user Story sera retourné soit une exception si erreur il y'a, soit le livre sera inséré en base de données
``

### Etapes de création
* 1. Créer la requête grace à la Class de requête
* 2. Créer un objet de la Class principale avec les dépendances nécessaires
* 3. Utiliser la fonction execute avec en entrée la requête créée précedemment
* 4. **Le Livre est crée et mis dans la base de données**

### Procédures (après declaration des variables des dépendances)
* 1.
```php
    $requete = new CreerLivreRequete(string $titre, string $isbn, string $auteur, int $nombrePages, string $dateParution);
```
* 2.

```php
    $requete = new CreerLivreRequete(string $titre, string $isbn, string $auteur, int $nombrePages, string $dateParution);
    $CreerLivre = new CreerLivre(EntityManagerInterface $entityManager, ValidatorInterface $validator, Validateur $validateurBDD);
```
La Class [Validateur](../../src/Validateurs/Validateur.php) va ici être utilisée dans la fonction ci-dessous comme dépendance afin de valider le fait que l'ISBN du livre n'est pas déjà utilisé: `isbnUtilise(CreerLivreRequete $livre,EntityManager $entityManager)`

* 3.

```php
    $requete = new CreerLivreRequete(string $titre, string $isbn, string $auteur, int $nombrePages, string $dateParution);
    $CreerLivre = new CreerLivre(EntityManagerInterface $entityManager, ValidatorInterface $validator, Validateur $validateurBDD);
    $CreerLivre->execute($requete);
```
* 4.
**_Le Livre sera crée et envoyé dans la base de donnée grace à l'entityManager_**

Afin de créer le livre une commande est possible: `php app.php creerLivre`








