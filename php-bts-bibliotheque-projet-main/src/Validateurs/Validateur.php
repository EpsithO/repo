<?php

namespace App\Validateurs;

use App\entity\Adherent;
use App\entity\Emprunt;
use App\entity\Livre;
use App\entity\Magazine;
use App\entity\Media;
use App\entity\StatutMedia;
use App\UserStories\CreerAdherent\CreerAdherent;
use App\UserStories\CreerAdherent\CreerAdherentRequete;
use App\UserStories\CreerLivre\CreerLivreRequete;
use App\UserStories\CreerMagazine\CreerMagazineRequete;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use function PHPUnit\Framework\throwException;

class Validateur
{
    public function mailDispo(CreerAdherentRequete $adherent, EntityManager $entityManager): bool
    {
        $repository = $entityManager->getRepository(Adherent::class);
        if ($repository->findOneBy(['mailAdherent' => $adherent->email])) {
            throw new \Exception("Le mail est deja utilise");
        } else {
            return true;
        }
    }

    public function numAdherentUtilise(string $numAdherent, EntityManager $entityManager): bool
    {

        $repository = $entityManager->getRepository(Adherent::class);
        if ($repository->findOneBy(['numeroAdherent' => $numAdherent])) {
            throw new \Exception("Ce numero d'adherent est deja utilise");
        }
        return false;

    }

    public function isbnUtilise(CreerLivreRequete $livre, EntityManager $entityManager): bool
    {
        $repository = $entityManager->getRepository(Livre::class);
        if ($repository->findOneBy(['isbn' => $livre->isbn])) {
            throw new \Exception("Cet isbn est déjà utilisé");
        }
        return false;
    }

    public function numeroMagazineUtilise(CreerMagazineRequete $magazine, EntityManager $entityManager): bool
    {
        $repository = $entityManager->getRepository(Magazine::class);
        if ($repository->findOneBy(['numero' => $magazine->numero])) {
            throw new \Exception("Ce numero de magazine est deja utilise");
        }
        return false;
    }

    public function mediaPasNouveau(EntityManager $entityManager, int $idMedia): bool
    {
        $repository = $entityManager->getRepository(Media::class);
        $media = $repository->find($idMedia);
        if ($media->getStatut() != StatutMedia::NOUVEAU) {
            throw new \Exception("Seul un média sous statut 'Nouveau' peut être rendu disponible");
        }
        return true;
    }

    public function mediaExistePas(EntityManager $entityManager, int $idMedia): bool
    {
        $repository = $entityManager->getRepository(Media::class);
        $media = $repository->find($idMedia);
        if (empty($media)) {
            throw new \Exception("Le média n'existe pas");
        }
        return false;
    }

    public function adherentExistePas(EntityManager $entityManager, string $numAdherent): bool
    {
        $repository = $entityManager->getRepository(Adherent::class);
        $adherent = $repository->findOneBy(['numeroAdherent'=>$numAdherent]);
        if (empty($adherent)) {
            throw new \Exception("L'adhérent n'existe pas");
        }
        return false;
    }

    public function mediaPasDisponible(EntityManager $entityManager, int $idMedia): bool
    {
        $repository = $entityManager->getRepository(Media::class);
        $media = $repository->find($idMedia);
        if ($media->getStatut() != StatutMedia::DISPONIBLE) {
            throw new \Exception("Le média n'est pas disponible à l'emprunt");
        }
        return false;
    }

    public function adhesionPasValable(EntityManager $entityManager, string $numAdherent): bool
    {
        $repository=$entityManager->getRepository(Adherent::class);
        $adherent=$repository->findOneBy(['numeroAdherent'=>$numAdherent]);
        if (empty($adherent->getDateAdhesion())){
            throw new \Exception("L'adhésion de l'adhérent n'est plus valable");
        }
        return false;
    }
    public function numEmpruntExistant(EntityManager $entityManager,string $numEmprunt):bool
    {
        $repo =$entityManager->getRepository(Emprunt::class);
        if (empty($repo->findOneBy(['numeroEmprunt'=>$numEmprunt]))){
            throw new \Exception("Ce numéro d'emprunt n'éxiste pas");
        }
        return true;
}


}