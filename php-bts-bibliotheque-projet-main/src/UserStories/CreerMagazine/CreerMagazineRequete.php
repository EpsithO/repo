<?php

namespace App\UserStories\CreerMagazine;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * # CreerMagazineRequete
 *  **Création de la requête avec: new CreerMagazineRequete(string titre,int numero)**
 */
class CreerMagazineRequete
{

    #[Assert\NotBlank(
        message: 'Le titre est obligatoire'
    )]
    public string $titre;
    #[Assert\GreaterThan(0,message: 'Le numéro de magazine doit être supérieur à 0')]
    #[Assert\NotBlank(
        message: 'Le numero de magazine est obligatoire'
    )]

    public int $numero;
    #[Assert\NotBlank(
        message: 'La date de publication est obligatoire'
)]
    #[Assert\NotBlank(
        message: 'La date de publication doit être renseignée'
    )]
    public \DateTime $datePublication;

    /**
     *  ## Création de la requête avec les informations du magazine
     * @param string $titre
     * @param int $numero
     * @param \DateTime $datePublication
     */
    public function __construct(string $titre, int $numero,\DateTime $datePublication)
    {
        $this->titre = $titre;
        $this->numero = $numero;
        $this->datePublication=$datePublication;
    }


}