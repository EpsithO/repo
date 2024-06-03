# User Stories
#### Code source
* [Class principale](../../src/UserStories/ListerNouveauxMedias)
* [Class des informations du média renvoyé](../../src/UserStories/ListerNouveauxMedias/MediaFront.php)


## Lister nouveaux médias
``` Voici comment lister les nouveaux médias présents dans la base de données ```
### Description
``
Cette User Story consiste à lister les médias qui ont pour statut 'Nouveau'
``
### Critères d'acceptation
``Pour chaque média présent dans la liste, seules les informations suivantes devront être retournées :
``
* l’id du média
* le titre du média
* le statut du média
* la date de création du média (date à laquelle il a été créé dans le BD)
* le type du média (livre, bluray ou magazine)
* La liste sera triée par date de création décroissante.

### Paramètres en entrée de la Class principale. Il y aura une injection de dépendances
Ces paramètres sont les dépendances nécessaires pour lister les nouveaux médias
```php
    private EntityManagerInterface $entityManager;
```

### Sortie
``
A la fin de l'éxécution de l'user Story sera retourné le tableau des nouveaux médias présents dans la base de données
``

### Etapes de création
* 1. Créer un objet ListerNouveauxMedias avec en entrée l'entity Manager
* 2. Réaliser un execute sur cet objet


### Procédures (après declaration des variables des dépendances)
* 1.
```php

    $listeMedias=new ListerNouveauxMedias(EntityManagerInterface $entityManager);
```
* 2.

```php
    $listeMedias=new ListerNouveauxMedias(EntityManagerInterface $entityManager);
    // Réalisation de la fonction principale
    $medias=$listeMedias->execute();

```



Afin de lister les nouveaux médias une commande est possible: `php app.php listerNouveauxMedias`










