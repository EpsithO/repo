<?php

namespace App\UserStories\CreerLivre;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * # CreerLivreRequete
 * **Création de la requête avec: new CreerLivreRequete(string titre,string ISBN,string auteur,int nombreDePages,string DateParution))**
 */
class CreerLivreRequete
{
    /**
     * @var string
     * Declaration du titre du livre
     */
    #[Assert\NotBlank(
        message: 'Le titre est obligatoire'
    )]
    public string $titre;
    /**
     * @var string
     * Declaration de l'ISBN du livre
     */
    #[Assert\NotBlank(
        message: "L'ISBN est obligatoire"
    )]
    public string $isbn;
    /**
     * @var string
     * Declaration de l'auteur du livre
     */
    #[Assert\NotBlank(
        message: "L'auteur est obligatoire"
    )] public string $auteur;
    /**
     * @var int
     * Declaration du nombre de pages du livre
     */
    #[Assert\NotBlank(
        message: 'Le nombre de pages est obligatoire'
    )]
    #[Assert\Positive(
        message: 'Le nombre de pages doit être positif'
    )]
    public int $nbPages;



    /**
     *  ## Construction de la requête avec les informations du livre
     * @param string $titre
     * @param string $isbn
     * @param string $auteur
     * @param int $nbPages

 */
    public function __construct(string $titre, string $isbn, string $auteur, int $nbPages)
    {
        $this->titre = $titre;
        $this->isbn = $isbn;
        $this->auteur = $auteur;
        $this->nbPages = $nbPages;
    }


}