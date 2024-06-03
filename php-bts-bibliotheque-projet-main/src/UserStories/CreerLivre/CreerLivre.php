<?php

namespace App\UserStories\CreerLivre;

use App\entity\Livre;
use App\entity\StatutMedia;
use App\Validateurs\Validateur;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Exception;

use Symfony\Component\Validator\Validator\ValidatorInterface;


/**
 * # CreerLivre
 */
class CreerLivre
{
    /**
     * # La classe Créer Livre
     * Etapes pour créer un livre et l'insérer:
     * * 1: Créer un objet de Creer Livre après avoir declaré toutes les dépendances
     * * 2: Créer la requête grace à la classe CreerLivreRequete
     * * 3: Réaliser un execute(requête) sur l'objet créé avec la requête en entrée
     * @var EntityManagerInterface
     * Declaration de l'entity manager
     */
    private EntityManagerInterface $entityManager;
    /**
     * @var ValidatorInterface
     *  Declaration du validateur d'insertion des données dans la reqûete afin de vérifier les données insérées dans la requête
     */
    private ValidatorInterface $validator;
    /**
     * @var Validateur
     * Declaration du validateur qui vérifie les données déjà insérées dans la base de données et celles dans la requête afin d'éviter certains cas
     */
    private Validateur $validateurBDD;

    /**
     *  ## Constructeur la classe qui permet l'injection de dépendances
     * @param EntityManagerInterface $entityManager
     * @param ValidatorInterface $validator
     * @param Validateur $validateurBDD
     */
    public function __construct(EntityManagerInterface $entityManager, ValidatorInterface $validator, Validateur $validateurBDD)
    {
        $this->entityManager = $entityManager;
        $this->validator = $validator;
        $this->validateurBDD = $validateurBDD;
    }

    /**
     * ### Fonction qui permet de créer le livre et l'insérer dans la base de données.
     *  Elle prend en paramètre une requête qui est une classe dans laquelle est inséré un jeu de donnée lié au livre:
     * @param CreerLivreRequete $requete
     * @return bool
     * @throws Exception
     */
    public function execute(CreerLivreRequete $requete): bool
    {
        // Vérification que les données saisies sont valides
        $erreurs = $this->validator->validate($requete);
        if (count($erreurs) == 0) {
            // Vérification si l'ISBN est unique
            $this->validateurBDD->isbnUtilise($requete, $this->entityManager);
            // Création du livre
            $livre = new Livre();
            $livre->setAuteur($requete->auteur);
            $livre->setTitre($requete->titre);
            $livre->setIsbn($requete->isbn);
            $livre->setNbPages($requete->nbPages);
            $livre->setStatut(StatutMedia::NOUVEAU);
            $date = new DateTime();
            $livre->setDateCreation($date);
            $livre->setDureeEmprunt(21);
            // Persister en BDD
            $this->entityManager->persist($livre);
            $this->entityManager->flush();
            return true;

        }
        $errors = [];
        foreach ($erreurs as $erreur) {
            $errors[] = $erreur->getMessage();
        }

        throw new \Exception(implode('SE', $errors));


    }


}