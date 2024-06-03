<?php

namespace App\UserStories\ListerNouveauxMedias;

use DateTime;

class MediaFront
{
    private int $id;
    private string $titre;
    private string $statut;
    private DateTime $dateCreation;
    private string $type;

    /**
     * @param int $id
     * @param string $titre
     * @param string $statut
     * @param DateTime $dateCreation
     * @param string $type
     */
    public function __construct(int $id, string $titre, string $statut, DateTime $dateCreation, string $type)
    {
        $this->id = $id;
        $this->titre = $titre;
        $this->statut = $statut;
        $this->dateCreation = $dateCreation;
        $this->type = $type;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getTitre(): string
    {
        return $this->titre;
    }

    public function getStatut(): string
    {
        return $this->statut;
    }

    public function getDateCreation(): string
    {
        return $this->dateCreation->format("d/m/Y");
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setTitre(string $titre): void
    {
        $this->titre = $titre;
    }

    public function setStatut(string $statut): void
    {
        $this->statut = $statut;
    }

    public function setDateCreation(DateTime $dateCreation): void
    {
        $this->dateCreation = $dateCreation;
    }

    public function setType(string $type): void
    {
        $this->type = $type;
    }



}