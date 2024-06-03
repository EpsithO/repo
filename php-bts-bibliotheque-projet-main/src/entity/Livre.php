<?php

namespace App\entity;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;

#[Entity]
class Livre extends Media
{
#[Column(type: "string")]
private string $isbn;
#[Column(type: "string")]
private string $auteur;
#[Column(type: "integer")]
private int $nbPages;







public function __construct()
{
    parent::__construct();
}

    public function getIsbn(): string
    {
        return $this->isbn;
    }

    public function setIsbn(string $isbn): void
    {
        $this->isbn = $isbn;
    }

    public function getAuteur(): string
    {
        return $this->auteur;
    }

    public function setAuteur(string $auteur): void
    {
        $this->auteur = $auteur;
    }

    public function getNbPages(): int
    {
        return $this->nbPages;
    }

    public function setNbPages(int $nbPages): void
    {
        $this->nbPages = $nbPages;
    }


    function getType(): string
    {
        return strtolower((new \ReflectionClass($this))->getShortName());
    }



}