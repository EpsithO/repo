<?php

use App\UserStories\rendreDisponibleMedia\RendreDispoMedia;
use App\Validateurs\Validateur;

require_once "bootstrap.php";
$validateurBDD=new Validateur();
$retournerEmprunt=new \App\UserStories\RetournerEmprunt\RetournerEmprunt($entityManager,$validateurBDD);
$retournerEmprunt->execute("EM-050057");

