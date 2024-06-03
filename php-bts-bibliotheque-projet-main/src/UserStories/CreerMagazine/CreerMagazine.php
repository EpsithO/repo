<?php

namespace App\UserStories\CreerMagazine;

use App\entity\Magazine;
use App\entity\Media;
use App\entity\StatutMedia;
use App\Validateurs\Validateur;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * # CreerMagazine
 */
class CreerMagazine
{
    /**
     * # La classe Créer Magazine
     * Etapes pour créer un magazine et l'insérer
     * * 1: Créer un objet de CreerMagazine après avoir declaré toutes les dépendances
     * * 2: Créer la requête grace à la classe CreerMagazineRequete
     * * 3: Réaliser un execute(requête) sur l'objet crée avec la requête en entrée
     * @var EntityManagerInterface
     * Declaration de l'entity manager
     */
    private EntityManagerInterface $entityManager;
    /**
     * @var ValidatorInterface
     * Declaration du validateur de saisie
     */
    private ValidatorInterface $validator;
    /**
     * @var Validateur
     * Declaration du validateur qui vérifie les données déjà insérées dans la BDD et celles de la requête afin d'éviter certains cas
     */
    private Validateur $validateurBDD;

    /**
     *  ## Constructeur qui permet l'injection de dépendances
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
     *  ### Fonction qui permet de créer le magazine et l'insérer dans la base de données
     * Elle prend en paramètre une requête qui est une classe dans laquelle est inséré un jeu de donnée lié au livre:
     * @param CreerMagazineRequete $requete
     * @return bool
     * @throws Exception
     */
    public function execute(CreerMagazineRequete $requete):bool{
        $erreurs=$this->validator->validate($requete);
        if (count($erreurs)==0){
            // Vérification si le numéro magazine est déjà utilisé
            $this->validateurBDD->numeroMagazineUtilise($requete,$this->entityManager);
            // Création du Magazine
            $magazine=new Magazine();
            $magazine->setTitre($requete->titre);
            $magazine->setNumero($requete->numero);
            $magazine->setDatePublication($requete->datePublication);
            $magazine->setDateCreation(new \DateTime());
            $magazine->setDureeEmprunt(10);
            $magazine->setStatut(StatutMedia::NOUVEAU);
            // Persister en BDD
            $this->entityManager->persist($magazine);
            $this->entityManager->flush();
            return true;

        }
        $errors=[];

        foreach ($erreurs as $erreur){
            $errors[]=$erreur->getMessage();
        }
        throw new Exception(implode('SE',$errors));
    }


}