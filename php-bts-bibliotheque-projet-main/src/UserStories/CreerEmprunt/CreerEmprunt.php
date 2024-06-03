<?php

namespace App\UserStories\CreerEmprunt;

use App\entity\Adherent;
use App\entity\Emprunt;
use App\entity\Media;
use App\entity\StatutMedia;
use App\Services\GenerateurNumeroEmprunt;
use App\Validateurs\Validateur;
use Doctrine\ORM\EntityManagerInterface;

class CreerEmprunt
{
    private EntityManagerInterface $entityManager;
    private Validateur $validateurBDD;
    private GenerateurNumeroEmprunt $numeroEmprunt;

    /**
     * @param EntityManagerInterface $entityManager
     * @param Validateur $validateurBDD
     */
    public function __construct(EntityManagerInterface $entityManager, Validateur $validateurBDD,GenerateurNumeroEmprunt $numeroEmprunt)
    {
        $this->entityManager = $entityManager;
        $this->validateurBDD = $validateurBDD;
        $this->numeroEmprunt=$numeroEmprunt;
    }

    public function execute(int $idMedia, string $numAdherent)
    {
        $emprunt=new Emprunt();
        // Vérifications

        $this->validateurBDD->adherentExistePas($this->entityManager, $numAdherent);
        $this->validateurBDD->adhesionPasValable($this->entityManager,$numAdherent);
        $this->validateurBDD->mediaExistePas($this->entityManager, $idMedia);
        $this->validateurBDD->mediaPasDisponible($this->entityManager,$idMedia);

        $repositoryAd=$this->entityManager->getRepository(Adherent::class);
        $adherent=$repositoryAd->findOneBy(['numeroAdherent'=>$numAdherent]);
        $media=$this->entityManager->find(Media::class,$idMedia);
        $media->setStatut(StatutMedia::EMPRUNTE);
        $emprunt->setNumeroEmprunt($this->numeroEmprunt->generer());
        // Date emprunt générée automatiquement
        $date=new \DateTime();
        $emprunt->setDateEmprunt($date);
        $emprunt->setAdherent($adherent);
        $emprunt->setMediaEmprunte($media);
        $dateRetour=new \DateTime();
        $dateRetour->modify("+".$media->getDureeEmprunt()."days");
        $emprunt->setDateRetourEstimee($dateRetour);

        $this->entityManager->persist($emprunt);
        $this->entityManager->flush();

        return true;

    }

}