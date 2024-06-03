<?php

namespace App\UserStories\RetournerEmprunt;

use App\entity\Emprunt;
use App\entity\Media;
use App\entity\StatutMedia;
use App\Validateurs\Validateur;
use Doctrine\ORM\EntityManagerInterface;

class RetournerEmprunt
{
private EntityManagerInterface $entityManager;
private Validateur $validateurBDD;

    /**
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager,Validateur $validateurBDD)
    {
        $this->validateurBDD=$validateurBDD;
        $this->entityManager = $entityManager;
    }
    public function execute(string $numEmprunt):bool
    {
        // Vérifications des critères d'acceptation
        $this->validateurBDD->numEmpruntExistant($this->entityManager,$numEmprunt);

        $repositoryEmprunt=$this->entityManager->getRepository(Emprunt::class);
        $empruntARestituee=$repositoryEmprunt->findOneBy(['numeroEmprunt'=>$numEmprunt]);
        $repositoryMedia=$this->entityManager->getRepository(Media::class);
        $mediaArestituee=$repositoryMedia->findOneBy(['id'=>$empruntARestituee->getMediaEmprunte()->getId()]);

        if ($empruntARestituee->estEnCours()){
            $empruntARestituee->setDateRetour(new \DateTime());
            $mediaArestituee->setStatut(StatutMedia::DISPONIBLE);
            $this->entityManager->flush();
            return true;
        }
    throw new \Exception("Le média a déjà été restitué");


    }


}