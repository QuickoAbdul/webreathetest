<?php
// bin/generate_luminosity_data.php

use App\Entity\LuminosityData;
use App\Kernel;
use Symfony\Component\Dotenv\Dotenv;
use Symfony\Component\ErrorHandler\Debug;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\ErrorHandler\ErrorHandler;

require dirname(__DIR__).'/vendor/autoload.php';

if ($_SERVER['APP_DEBUG']) {
    umask(0000);

    Debug::enable();
}

if (!class_exists(Dotenv::class)) {
    throw new LogicException('You need to add "symfony/dotenv" as a Composer dependency.');
}

(new Dotenv())->loadEnv(dirname(__DIR__).'/.env');

// Initialise le kernel Symfony
$kernel = new Kernel($_SERVER['APP_ENV'], (bool) $_SERVER['APP_DEBUG']);
$kernel->boot();

// Crée une requête Symfony
$request = Request::createFromGlobals();

// Obtient le conteneur de services
$container = $kernel->getContainer();

// Obtient le gestionnaire d'entité
$entityManager = $container->get('doctrine')->getManager();
// ... (code précédent)

// Obtient le repository des modules
$moduleRepository = $entityManager->getRepository(Module::class);

// Obtient la liste des modules
$modules = $moduleRepository->findAll();

// Génère des données de luminosité pour chaque module
foreach ($modules as $module) {
    $luminosityData = new LuminosityData();
    $luminosityData->setModule($module);
    $luminosityData->setTimestamp(new \DateTime());
    $luminosityData->setValue(random_int(0, 100)); // Valeur aléatoire pour la luminosité

    // Persiste et enregistre l'entité
    $entityManager->persist($luminosityData);
}

// Enregistre toutes les modifications dans la base de données
$entityManager->flush();
