<?php

namespace App\entity;


use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\ManyToOne;

#[Entity]
class Emprunt
{
    #[id]
    #[Column(type: "string",length: 12)]
    private string $numeroEmprunt;
    #[Column(type: "datetime")]
    private \DateTime $dateEmprunt;
    #[Column(type: "datetime")]
    private \DateTime $dateRetourEstimee;
    #[Column(type: "datetime",nullable: true)]
    private ?\DateTime $dateRetour=null;
    #[ManyToOne(targetEntity: Adherent::class)]
    private Adherent $adherent;
    #[ManyToOne(targetEntity: \App\entity\Media::class)]
    private \App\entity\Media $media;

    public function __construct()
    {

    }

    public function estEnCours():bool{
        if (empty($this->dateRetour)){
            return true;
        }else{
            return false;
        }
    }


    public function setDateEmprunt(\DateTime $dateEmprunt): void
    {

        $this->dateEmprunt = $dateEmprunt;
    }

    public function setDateRetourEstimee(\DateTime $dateRetourEstimee): void
    {

        $this->dateRetourEstimee = $dateRetourEstimee;
    }


    public function estRetard():bool{
        //Faire si la date de retour est set et qu'elle est inférieure à la date de retour
        $dateActuelle=\DateTime::createFromFormat("d/m/Y",date("d/m/Y"));
        if ($dateActuelle>$this->dateRetourEstimee && empty($this->dateRetour)or($this->dateRetour > $this->dateRetourEstimee)){
            return true;
        }else{
            return false;
        }
    }

    public function getDateRetour(): \DateTime
    {
        return $this->dateRetour;
    }

    public function setDateRetour(\DateTime $dateRetour): void
    {
        $this->dateRetour = $dateRetour;
    }

    public function getMediaEmprunte(): Media
    {
        return $this->media;
    }

    public function setMediaEmprunte(Media $mediaEmprunte): void
    {
        $this->media = $mediaEmprunte;
    }

    public function getAdherent(): Adherent
    {
        return $this->adherent;
    }

    public function setAdherent(Adherent $emprunteur): void
    {
        $this->adherent = $emprunteur;
    }

    public function setNumeroEmprunt(string $numeroEmprunt): void
    {
        $this->numeroEmprunt = $numeroEmprunt;
    }


}