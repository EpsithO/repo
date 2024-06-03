# User Stories
#### Code source
* [Class principale](../../src/UserStories/rendreDisponibleMedia)



## Rendre média disponible
``` Voici comment rendre un média qui est sous statut 'Nouveau' à 'Disponible' ```
### Description
``
Cette User Story consiste à rendre un média disponible
``
### Critères d'acceptation
``Voici les critères d'acceptation pour cette UserStory
``
* Le média que l’on souhaite rendre disponible doit exister
* Seul un média ayant le statut “Nouveau” peut-être rendu disponible.
* Le changement de statut du média doit être correctement enregistré dans la base de données.
* Des messages d’erreur explicites doivent être retournés en cas d’informations manquantes ou incorrectes.


### Paramètres en entrée de la Class principale. Il y aura une injection de dépendances
Ces paramètres sont les dépendances nécessaires pour lister les nouveaux médias
```php
    private EntityManagerInterface $entityManager;
    private Validateur $validateurBDD;
```

### Sortie
``
A la fin de l'éxécution de l'user Story sera retourné un booleen confirmant l'éxécution de la commande
``

### Etapes de création
* 1. Créer un objet RendreDispoMedia avec en entrée l'entity Manager et le validateur de base de données
* 2. Réaliser un execute sur cet objet avec en entrée l'id du média que l'on veut rendre disponible


### Procédures (après declaration des variables des dépendances)
* 1.
```php

    $rendreDispo=new RendreDispoMedia($entityManager,$validateurBDD);
```
* 2.

```php
    $rendreDispo=new RendreDispoMedia($entityManager,$validateurBDD);
    // Réalisation de la fonction principale
    $rendreDispo->execute(int $idDuMedia)
```



Afin de lister les nouveaux médias une commande est possible: `php app.php rendreMediaDispo`










