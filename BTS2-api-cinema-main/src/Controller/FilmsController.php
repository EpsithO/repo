<?php

namespace App\Controller;

use App\Entity\Film;
use App\Repository\FilmRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api')]
class FilmsController extends AbstractController
{
    #[Route('/listerFilms', name: 'app_listerFilms')]
    public function index(FilmRepository $filmRepository, SerializerInterface $serializer): Response
    {
        $films = $filmRepository->listerFilmsAAfficher();
        $filmsSerialized = $serializer->serialize($films, 'json', ['groups' => 'list_films']);
        return new Response($filmsSerialized, 200, [
            'content-type' => 'application/json'
        ]);
    }

    #[Route('/film/{id}', name: 'app_detailsFilm')]
    public function detailsFilm(FilmRepository $filmRepository, SerializerInterface $serializer, int $id): Response
    {
        $film = $filmRepository->detailsFilm($id);
        $filmsSerialized = $serializer->serialize($film, 'json', ['groups' => 'details_films']);
        return new Response($filmsSerialized, 200, [
            'content-type' => 'application/json'
        ]);
    }


}
