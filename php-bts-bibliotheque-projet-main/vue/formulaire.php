<?php
require "../vendor/autoload.php";
require "../bootstrap.php";

if ($_SERVER["REQUEST_METHOD"]=="POST" ){
    $validateur=\Symfony\Component\Validator\Validation::createValidatorBuilder()->enableAnnotationMapping()->getValidator();
    $validateurBDD=new \App\Services\GenerateurNumeroAdherent();

    try {
        $requete=new \App\UserStories\CreerAdherent\CreerAdherentRequete($_POST["prenom"],$_POST["nom"],$_POST["mail"]);
        $adherent=new \App\UserStories\CreerAdherent\CreerAdherent($entityManager,$validateurBDD,$validateur,new \App\Validateurs\Validateur());
        $status=$adherent->execute($requete);
    } catch (Exception $e) {
        $message=$e->getMessage();
    }
}
?>

<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Formulaire</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h1 class="mb-4">Création d'un adhérent</h1>
    <form action="" method="post">
        <div class="form-group">
            <label for="prenom">Prénom</label>
            <input type="text" name="prenom" id="prenom" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="nom">Nom</label>
            <input type="text" name="nom" id="nom" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="mail">E-mail adhérent</label>
            <input type="email" name="mail" id="mail" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Valider</button>
    </form>
    <?php if (isset($message)) { ?>
        <div class="alert alert-danger mt-4"><?= $message ?></div>
    <?php } ?>
</div>

<!-- Bootstrap JS and dependencies -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
