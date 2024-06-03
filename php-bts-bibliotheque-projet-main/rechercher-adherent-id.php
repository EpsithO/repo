<?php
require "bootstrap.php";
$id=1;
$adherentParId=$entityManager->getRepository(\App\entity\Adherent::class)->findOneBy(["id"=>$id]);
dump($adherentParId);
