<?php

namespace App\UserStories\CreerAdherent;


use App\entity\Adherent;
use App\Services\GenerateurNumeroAdherent;
use App\Validateurs\Validateur;
use Doctrine\ORM\EntityManagerInterface;
use Dotenv\Validator;
use Exception;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use function PHPUnit\Framework\throwException;

class CreerAdherent
{
    private EntityManagerInterface $entityManager;
    private GenerateurNumeroAdherent $generateurNumeroAdherent;
    private ValidatorInterface $validateur;
    private Validateur $validateurBDD;

    /**
     * @param EntityManagerInterface $entityManager
     * @param GenerateurNumeroAdherent|null $generateurNumeroAdherent
     * @param ValidatorInterface $validateur
     * @param Validateur $validateurBDD
     */
    public function __construct(EntityManagerInterface $entityManager, ?GenerateurNumeroAdherent $generateurNumeroAdherent, ValidatorInterface $validateur, Validateur $validateurBDD)
    {
        $this->entityManager = $entityManager;
        $this->generateurNumeroAdherent = $generateurNumeroAdherent;
        $this->validateur = $validateur;
        $this->validateurBDD = $validateurBDD;
    }


    /**
     * @param CreerAdherentRequete $requete
     * @return bool|array
     * @throws Exception
     */


    public function execute(CreerAdherentRequete $requete): bool|array
    {

        // Valider les données en entrées (de la requête)
        $erreurs = $this->validateur->validate($requete);
        if (count($erreurs) == 0) {
            // Vérifier que l'email n'existe pas déjà

            $this->validateurBDD->mailDispo($requete,$this->entityManager);
            // Générer un numéro d'adhérent au format AD-999999
            $numeroAdherent = $this->generateurNumeroAdherent->generer();

                // Vérifier que le numéro n'existe pas déjà
                $this->validateurBDD->numAdherentUtilise($numeroAdherent,$this->entityManager);
                // Créer l'adhérent
                $adherent = new Adherent();
                $adherent->setNumeroAdherent($numeroAdherent);
                $adherent->setNomAdherent($requete->nom);
                $adherent->setPrenomAdherent($requete->prenom);
                $adherent->setMailAdherent($requete->email);
                $date = new \DateTime();
                $adherent->setDateAdhesion($date->format("d/m/Y"));
                // Enregistrer l'adhérent en base de données
                $this->entityManager->persist($adherent);
                $this->entityManager->flush();
                return true;

        }

        $errors=[];
        foreach ($erreurs as $erreur){
            $errors[]=$erreur->getMessage();
        }
        throw new Exception(implode(' ',$errors));

    }

}