# User Stories
#### Code source
* [Class principale](../../src/UserStories/CreerMagazine/CreerMagazine.php)
* [Class de la requête](../../src/UserStories/CreerMagazine/CreerMagazineRequete.php)
* [Class mère du média](../../src/entity/Media.php)
* [Class de l'objet Magazine](../../src/entity/Magazine.php)

## Créer magazine
``` Voici comment créer un magazine et l'insérer dans la base de données ```
### Description
``
Cette User Story consiste à créer un magazine et à l'insérer directement dans une base de données.
Les vérifications réalisées seront les suivantes: le numéro du magazine créé ne devra aucunement être déjà dans la base
de données. Si c'est le cas une exception sera renvoyée.
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
    public string $titreMagazine;
    public int $numeroMagazine;
    public DateTime $datePublication;
````
### Sortie
``
A la fin de l'éxécution de l'user Story sera retourné soit une exception si erreur il y'a, soit le magazine sera inséré en base de données
``

### Etapes de création
* 1. Créer la requête grace à la Class de requête
* 2. Créer un objet de la Class principale avec les dépendances nécessaires
* 3. Utiliser la fonction execute avec en entrée la requête créée précedemment
* 4. **Le magazine est crée et mis dans la base de données**

### Procédures (après declaration des variables des dépendances)
* 1.
```php

    $requete = new CreerMagazineRequete(string $titre,int $numero,DateTime $datePublication)
```
* 2. 

```php
    $requete = new CreerMagazineRequete(string $titre,int $numero)
    // Création de l'objet qui va permettre de créer un magazine
    $CreerMagazine = new CreerMagazine(EntityManagerInterface $entityManager, ValidatorInterface $validator, Validateur $validateurBDD);

```
La Class [Validateur](../../src/Validateurs/Validateur.php) va ici être utilisée lors de l'exécution de la fonction ci-dessous comme dépendance afin de valider le fait que le numéro du magazine n'est pas déjà utilisé: `numeroMagazineUtilise(CreerMagazineRequete $magazine,EntityManager $entityManager)`

* 3. 
```php
    $requete = new CreerMagazineRequete(string $titre,int $numero)
     // Création de l'objet qui va permettre de créer un magazine
    $CreerMagazine = new CreerMagazine(EntityManagerInterface $entityManager, ValidatorInterface $validator, Validateur $validateurBDD);
    $CreerMagazine->execute($requete);
```
* 4.
**_Le Magazine sera crée et envoyé dans la base de donnée grace à l'entityManager_**


Afin de créer le magazine une commande est possible: `php app.php creerMagazine`










