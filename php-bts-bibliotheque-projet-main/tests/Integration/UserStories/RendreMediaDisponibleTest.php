<?php

namespace tests\integration\UserStories;

use App\entity\Livre;
use App\entity\Media;
use App\entity\StatutMedia;
use App\UserStories\CreerLivre\CreerLivre;
use App\UserStories\CreerLivre\CreerLivreRequete;
use App\UserStories\rendreDisponibleMedia\RendreDispoMedia;
use App\Validateurs\Validateur;
use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\ORMSetup;
use Doctrine\ORM\Tools\SchemaTool;
use mysql_xdevapi\Exception;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class RendreMediaDisponibleTest extends TestCase
{
    private EntityManagerInterface $entityManager;
    private Validateur $validateurBDD;
    protected ValidatorInterface $validator;

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

        // Création du schema de la base de données
        $schemaTool = new SchemaTool($this->entityManager);
        $metadata = $this->entityManager->getMetadataFactory()->getAllMetadata();
        $schemaTool->createSchema($metadata);
    }
    #[Test]
    public function rendreMediaDisponible_Succes_True()
    {
        $repository=$this->entityManager->getRepository(Livre::class);
        $changerStatut=new RendreDispoMedia($this->entityManager,$this->validateurBDD);
        $requete=new CreerLivreRequete("Spider-man","148-DFDZSS","Stan",456);
        $creerLivre=new CreerLivre($this->entityManager,$this->validator,$this->validateurBDD);
        $creerLivre->execute($requete);

        $media=$repository->findOneBy(['titre'=>'Spider-man']);


        $changerStatut->execute($media->getId());

        $this->assertEquals('Disponible',$media->getStatut());

    }
    #[Test]
    public function rendreMediaDisponible_MediasNouveauNonPresentEnBDD_Empty(){
        //Arrange
        $lister=new \App\UserStories\ListerNouveauxMedias\ListerNouveauxMedias($this->entityManager);
        //Act
        $medias=$lister->execute();
        //Assert
        $this->assertEmpty($medias);

    }
    #[Test]
    public function rendreMediaDisponible_StautPasNouveau_Excpetion()
    {
        $repository=$this->entityManager->getRepository(Livre::class);
        $changerStatut=new RendreDispoMedia($this->entityManager,$this->validateurBDD);
        $requete=new CreerLivreRequete("Spider-man","148-DFDZSS","Stan",456);
        $creerLivre=new CreerLivre($this->entityManager,$this->validator,$this->validateurBDD);
        $creerLivre->execute($requete);

        $media=$repository->findOneBy(['titre'=>'Spider-man']);
        $media->setStatut(StatutMedia::NON_DISPONIBLE);


        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Seul un média sous statut 'Nouveau' peut être rendu disponible");
        $changerStatut->execute($media->getId());


    }
    

}