# User Stories
#### Code source
* [Class de création d'un emprunt'](../../src/UserStories/CreerEmprunt/CreerEmprunt.php)
* [Class mère du média](../../src/entity/Media.php)

## Créer Emprunt
``` Voici comment créer un emprunt et l'insérer dans la base de données ```
### Description
``
Cette User Story va  permettre de créer un emprunt et l'insérer dans la base de données
``
### Critères d'acceptations

* Le média doit exister dans la base de données
* Le média doit être disponible
* L’adhérent doit exister dans la base de données
 * L’adhésion de l’adhérent doit être valide
 * L’emprunt doit être correctement enregistré dans la base de données. 
 * La date de retour prévue doit être correctement initialisée en fonction du média emprunté (livre, bluray ou magazine) ainsi que la date d’emprunt.
 * A l’issue de l’enregistrement de l’emprunt, le statut du média doit être à “Emprunte”.

### Paramètres en entrée de la Class principale. Il y aura une injection de dépendances
Ces paramètres sont les dépendances nécessaires à la création de l'emprunt
```php
    private EntityManagerInterface $entityManager;
    private GenerateurNumeroEmprunt $numeroEmprunt;
    private Validateur $validateurBDD;
```

### Sortie
``
A la fin de l'éxécution de l'user Story sera retourné soit une exception si erreur il y'a, soit l'emprunt' sera inséré en base de données et il y'aura un booléen de retourné
``

### Etapes de création
* 1. Créer un objet de la Class principale avec les dépendances nécessaires
* 2. bUtiliser la fonction execute avec en entrée la requête créée précedemment
* 3. **L'emprunt' est crée et mis dans la base de données**

### Procédures (après declaration des variables des dépendances)

* 1.

```php
    
    $CreerEmprunt = new CreerEmprunt(EntityManagerInterface entityManager,Validateur $validateurBDD,GenerateurNumeroEmprunt $generateurNumeroEmprunt);
```
La Class [Validateur](../../src/Validateurs/Validateur.php) va ici être utilisée dans la fonction ci-dessous comme dépendance afin de valider les critères d'acceptations

* 3.

```php
    $CreerEmprunt = new CreerEmprunt(EntityManagerInterface entityManager,Validateur $validateurBDD,GenerateurNumeroEmprunt $generateurNumeroEmprunt);
    $CreerEmprunt->execute(int $idMedia, int $idAdherent);
```
* 4.
**_L'emprunt sera crée et envoyé dans la base de donnée grace à l'entityManager_**

Afin de créer l'emprunt'une commande est possible: `php app.php creerEmprunt`








