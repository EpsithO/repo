<?php

require_once "bootstrap.php";

use App\UserStories\CreerLivre\CreerLivre;
use App\UserStories\CreerLivre\CreerLivreRequete;
use App\UserStories\CreerMagazine\CreerMagazine;
use App\UserStories\CreerMagazine\CreerMagazineRequete;
use App\UserStories\ListerNouveauxMedias\ListerNouveauxMedias;
use App\UserStories\rendreDisponibleMedia\RendreDispoMedia;
use App\Validateurs\Validateur;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Validator\Validation;

$app = new \Silly\Application();

$app->command('creerLivre', function (SymfonyStyle $io) use ($entityManager) {

    $io->title('Créer un livre');
    $io->note("Il est nécessaire de remplir chaque champ avec les valeurs corrects");
    $io->text("Voici l'interface de création et d'insertion dans la base de données d'un livre");
    $titre = $io->ask("Entrez le titre du livre");
    if (empty($titre)){
        $titre="";
    }

    $isbn = $io->ask("Entrez l'isbn du livre");
    if (empty($isbn)){
        $isbn="";
    }

    $auteur = $io->ask("Entrez l'auteur du livre");
    if (empty($auteur)){
        $auteur="";
    }

    $nbPages = $io->ask("Entrez le nombre de pages du livre");
    if (empty($nbPages)){
        $nbPages=-1;
    }



    $validateur = Validation::createValidatorBuilder()->enableAttributeMapping()->getValidator();
    $validateurBDD = new Validateur();
    $requete = new CreerLivreRequete($titre, $isbn, $auteur, $nbPages);
    $creerLivre = new CreerLivre($entityManager,$validateur, $validateurBDD);
    try {
        $creerLivre->execute($requete);
    }catch (Exception $e){}
    if (isset($e)){
        $io->error(explode("SE",$e->getMessage()));
    }else {
        $io->success("Un livre a bien été inséré dans la base de données");
    }
});


$app->command('creerMag', function (SymfonyStyle $io) use ($entityManager) {


    $io->title('Créer un Magazine');
    $io->note("Il est nécessaire de remplir chaque champ avec les valeurs corrects");
    $io->text("Voici l'interface de création et d'insertion dans la base de données d'un magazine");
    $titre = $io->ask("Entrez le titre du magazine");
    if (empty($titre)){
        $titre='';
    }

    $numero = $io->ask("Entrez le numéro du magazine");
    if (empty($numero)){
        $numero=-1;
    }

    $datePublication=$io->ask("Veuillez saisir la date de publication du magazine au format jj/mm/AAAA");
    $datePubli=DateTime::createFromFormat("d/m/Y",$datePublication);


    $validateur = Validation::createValidatorBuilder()->enableAttributeMapping()->getValidator();
    $validateurBDD = new Validateur();
    $requete = new CreerMagazineRequete($titre,$numero,$datePubli);
    $creerMagazine = new CreerMagazine($entityManager,$validateur, $validateurBDD);

    try {
        $creerMagazine->execute($requete);
    }catch (Exception $e){}
    if (isset($e)){
        $io->error(explode("SE",$e->getMessage()));
    }else {
        $io->success("Un livre a bien été inséré dans la base de données");
    }

});

$app->command('listerMedias',function (SymfonyStyle $io,OutputInterface $output)use ($entityManager){

   $creerListe=new ListerNouveauxMedias($entityManager);
   $medias=$creerListe->execute();
   $table=new \Symfony\Component\Console\Helper\Table($output);
   $table->setHeaderTitle("Liste des nouveaux médias");
   $table->setHeaders(['id','titre','statut','dateCreation','typeMedia']);
  foreach ($medias as $media){
     $table->addRow([$media->getId(),$media->getTitre(),$media->getStatut(),$media->getDateCreation(),$media->getType()]);

  }
   $table->setStyle("borderless");
   $table->render();

});

$app->command('rendreDispo',function (SymfonyStyle $io)use($entityManager){
    $changerStatut=new RendreDispoMedia($entityManager,new Validateur());
    $io->title('Rendre un média disponible');
    $idMedia=$io->ask("Saisir l'id du livre à rendre disponible");
    $changerStatut->execute($idMedia);
    $io->success("Le statut du média a bien été changé de 'Nouveau' à 'Disponible' !");

});
$app->command('creerEmprunt',function (SymfonyStyle $io)use($entityManager){
    $creerEmprunt=new \App\UserStories\CreerEmprunt\CreerEmprunt($entityManager,new Validateur(),new \App\Services\GenerateurNumeroEmprunt());
    $io->title('Créer un emprunt');
    $idMedia=$io->ask("Saisir l'id du média à emprunter");
    $numAdherent=$io->ask("Saisir le numéro de l'adhérent qui va emprunter");
    try {
        $creerEmprunt->execute($idMedia,$numAdherent);
    }catch (Exception $e){}
    if (isset($e)){
        $io->error(explode("SE",$e->getMessage()));
    }else {
        $io->success("Un magazine a bien été inséré dans la base de données");
    }

});
$app->command('restituerEmprunt',function (SymfonyStyle $io)use($entityManager){
    $restituerEmprunt=new \App\UserStories\RetournerEmprunt\RetournerEmprunt($entityManager,new Validateur());
    $io->title('Restituer un emprunt');
    $numEmprunt=$io->ask("Saisir le numéro d'emprunt à restituer");
    try {
        $restituerEmprunt->execute($numEmprunt);
    }catch (Exception $e){}
    if (isset($e)){
        $io->error(explode("SE",$e->getMessage()));
    }else {
        $io->success("L'emprunt numéro $numEmprunt a bien été restitué");
    }

});

$app->run();

