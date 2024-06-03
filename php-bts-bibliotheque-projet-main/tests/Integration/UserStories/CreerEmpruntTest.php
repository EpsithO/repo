<?php

namespace tests\integration\UserStories;

use App\entity\Adherent;
use App\entity\Emprunt;
use App\entity\Livre;
use App\Services\GenerateurNumeroAdherent;
use App\Services\GenerateurNumeroEmprunt;
use App\UserStories\CreerAdherent\CreerAdherent;
use App\UserStories\CreerAdherent\CreerAdherentRequete;
use App\UserStories\CreerEmprunt\CreerEmprunt;
use App\UserStories\CreerLivre\CreerLivre;
use App\UserStories\CreerLivre\CreerLivreRequete;
use App\UserStories\RendreDisponibleMedia\RendreDispoMedia;
use App\Validateurs\Validateur;
use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\ORMSetup;
use Doctrine\ORM\Tools\SchemaTool;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CreerEmpruntTest extends TestCase
{
    private EntityManagerInterface $entityManager;
    private Validateur $validateurBDD;
    private ValidatorInterface$validator;
    private GenerateurNumeroEmprunt $generateurNumeroEmprunt;
    private GenerateurNumeroAdherent $generateurNumeroAdherent;
    protected function setUp() : void
    {

        // Configuration de Doctrine pour les tests
        $config = ORMSetup::createAttributeMetadataConfiguration(
            [__DIR__.'/../../../src/'],
            true
        );

        // Configuration de la connexion à la base de données
        // Utilisation d'une base de données SQLite en mémoire
        $connection = DriverManager::getConnection([
            'driver' => 'pdo_sqlite',
            'path' => ':memory:'
        ], $config);

        // Création de l'entity manager
        $this->entityManager = new EntityManager($connection, $config);
        $this->validateurBDD=new Validateur();
        $this->validator=Validation::createValidatorBuilder()->enableAnnotationMapping()->getValidator();
        $this->generateurNumeroEmprunt=new GenerateurNumeroEmprunt();
        $this->generateurNumeroAdherent=new GenerateurNumeroAdherent();
        // Création du schema de la base de données
        $schemaTool = new SchemaTool($this->entityManager);
        $metadata = $this->entityManager->getMetadataFactory()->getAllMetadata();
        $schemaTool->createSchema($metadata);
    }
    #[Test]
    public function creerEmprunt_Succes_True()
    {
        //Arrange
        $requete=new CreerLivreRequete("Spider-man","148-DFDZSS","Stan Lee",456);
        $creerLivre=new CreerLivre($this->entityManager,$this->validator,$this->validateurBDD);
        $creerLivre->execute($requete);
        $dispo=new RendreDispoMedia($this->entityManager,$this->validateurBDD);


        $repositoryLivre=$this->entityManager->getRepository(Livre::class);
        $livre=$repositoryLivre->findOneBy(['isbn'=>'148-DFDZSS']);
        $dispo->execute($livre->getId());

        $requete2=new CreerAdherentRequete("Arturs","Mednis",'artursmednis@gmail.com');
        $creerAdherent=new CreerAdherent($this->entityManager,$this->generateurNumeroAdherent,$this->validator,$this->validateurBDD);
        $creerAdherent->execute($requete2);
        $repositoryAdherent=$this->entityManager->getRepository(Adherent::class);
        $adherent=$repositoryAdherent->findOneBy(['mailAdherent'=>'artursmednis@gmail.com']);


        $creerEmprunt=new CreerEmprunt($this->entityManager,$this->validateurBDD,$this->generateurNumeroEmprunt);
        // Act
        $emprunt=$creerEmprunt->execute($livre->getId(),$adherent->getNumeroAdherent());
        //Assert
        $this->assertTrue($emprunt);

    }
    #[Test]
    public function creerEmprunt_MediaNonExistant_Excpetion()
    {
        //Arrange

        $requete2=new CreerAdherentRequete("Arturs","Mednis",'artursmednis@gmail.com');
        $creerAdherent=new CreerAdherent($this->entityManager,$this->generateurNumeroAdherent,$this->validator,$this->validateurBDD);
        $creerAdherent->execute($requete2);
        $repositoryAdherent=$this->entityManager->getRepository(Adherent::class);
        $adherent=$repositoryAdherent->findOneBy(['mailAdherent'=>'artursmednis@gmail.com']);


        $creerEmprunt=new CreerEmprunt($this->entityManager,$this->validateurBDD,$this->generateurNumeroEmprunt);
        // Act Assert
        $this->expectException(\Exception::class);
        $emprunt=$creerEmprunt->execute(0,$adherent->getNumeroAdherent());



    }
    #[Test]
    public function creerEmprunt_AdherentNonExistant_Exception()
    {
        //Arrange
        $requete=new CreerLivreRequete("Spider-man","148-DFDZSS","Stan Lee",456);
        $creerLivre=new CreerLivre($this->entityManager,$this->validator,$this->validateurBDD);
        $creerLivre->execute($requete);
        $dispo=new RendreDispoMedia($this->entityManager,$this->validateurBDD);


        $repositoryLivre=$this->entityManager->getRepository(Livre::class);
        $livre=$repositoryLivre->findOneBy(['isbn'=>'148-DFDZSS']);
        $dispo->execute($livre->getId());



        $creerEmprunt=new CreerEmprunt($this->entityManager,$this->validateurBDD,$this->generateurNumeroEmprunt);
        // Act  Assert
        $this->expectException(\Exception::class);
        $emprunt=$creerEmprunt->execute($livre->getId(),'AD-177817');


    }
    #[Test]
    public function creerEmprunt_MediaNonDisponible_Exception()
    {
        //Arrange
        $requete=new CreerLivreRequete("Spider-man","148-DFDZSS","Stan Lee",456);
        $creerLivre=new CreerLivre($this->entityManager,$this->validator,$this->validateurBDD);
        $creerLivre->execute($requete);



        $repositoryLivre=$this->entityManager->getRepository(Livre::class);
        $livre=$repositoryLivre->findOneBy(['isbn'=>'148-DFDZSS']);


        $requete2=new CreerAdherentRequete("Arturs","Mednis",'artursmednis@gmail.com');
        $creerAdherent=new CreerAdherent($this->entityManager,$this->generateurNumeroAdherent,$this->validator,$this->validateurBDD);
        $creerAdherent->execute($requete2);
        $repositoryAdherent=$this->entityManager->getRepository(Adherent::class);
        $adherent=$repositoryAdherent->findOneBy(['mailAdherent'=>'artursmednis@gmail.com']);


        $creerEmprunt=new CreerEmprunt($this->entityManager,$this->validateurBDD,$this->generateurNumeroEmprunt);
        // Act Assert
        $this->expectException(\Exception::class);
        $emprunt=$creerEmprunt->execute($livre->getId(),$adherent->getNumeroAdherent());



    }

}