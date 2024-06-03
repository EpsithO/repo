<?php

namespace App\UserStories\ListerNouveauxMedias;

use App\entity\Media;
use App\entity\StatutMedia;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Yaml\Exception\ExceptionInterface;

class ListerNouveauxMedias
{
    private EntityManagerInterface $entityManager;

    /**
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    public function execute():array|false{
        $mediasFormates=[];
        $repository=$this->entityManager->getRepository(Media::class);
        $medias=$repository->findBy(['statut'=>StatutMedia::NOUVEAU],['dateCreation'=>'desc']);
        foreach ($medias as $media){
            $mediaFront=new MediaFront($media->getId(),$media->getTitre(),$media->getStatut(),$media->getDateCreation(),$media->getType());
            $mediasFormates[]=$mediaFront;
        }
        return $mediasFormates;
    }
}