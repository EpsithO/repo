<?php

namespace App\UserStories\RendreDisponibleMedia;

use App\entity\Media;
use App\entity\StatutMedia;
use App\Validateurs\Validateur;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use function PHPUnit\Framework\throwException;

class RendreDispoMedia
{
    private EntityManagerInterface $entityManager;
    private Validateur $validateurBDD;

    public function __construct(EntityManagerInterface $entityManager,Validateur $validateurBDD)
    {
        $this->entityManager=$entityManager;
        $this->validateurBDD=$validateurBDD;
    }

    /**
     * @throws Exception
     */
    public function execute(int $idMedia):true
    {
        $repository=$this->entityManager->getRepository(Media::class);
        $media=$repository->find($idMedia);

        // Test si le média existe bien
        $this->validateurBDD->mediaExistePas($this->entityManager,$idMedia);
        // Test si le média est a bien comme statut 'Nouveau'
        $this->validateurBDD->mediaPasNouveau($this->entityManager,$idMedia);
        $media->setStatut(StatutMedia::DISPONIBLE);
        $this->entityManager->flush();
        return true;
    }

}