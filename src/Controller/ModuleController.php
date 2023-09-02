<?php

namespace App\Controller;

use App\Entity\LuminosityData;
use App\Entity\Module;
use App\Form\LuminosityDataType;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\ModuleType;
use Symfony\Component\HttpFoundation\Response;

class ModuleController extends AbstractController
{

    /**
     * @Route("/", name="index")
     */
    public function index(): Response
    {
        return $this->render('index.html.twig',); // Affiche la page d'accueil localhost:8000
    }

    /**
     * @Route("/module/ajouter", name="module_ajouter")
     */
    public function ajouterModule(Request $request)
    {
        $module = new Module();

        $form = $this->createForm(ModuleType::class, $module);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($module);
            $entityManager->flush();

            // Générer et enregistrer des données de luminosité simulées
            $luminosityData = new LuminosityData();
            $luminosityData->setModule($module);
            $luminosityData->setTimestamp(new \DateTime()); // Date actuelle
            $luminosityData->setValue(random_int(0, 100)); // Valeur aléatoire pour la luminosité
            $entityManager->persist($luminosityData);
            $entityManager->flush();

            return $this->redirectToRoute('module_list');
        }

        return $this->render('module/ajouter.html.twig', [
            'form' => $form->createView(),
        ]);
    }



    /**
     * @Route("/module", name="module_list")
     */
    public function listModules() // Affiche la liste des modules
    {
        $modules = $this->getDoctrine()->getRepository(Module::class)->findAll();

        return $this->render('module/list.html.twig', [
            'modules' => $modules,
        ]);
    }

    /**
     * @Route("/module/{id}", name="module_details")
     */
    public function moduleDetails($id) // Affiche les détails d'un module
    {
        $module = $this->getDoctrine()->getRepository(Module::class)->find($id);

        if (!$module) {
            throw $this->createNotFoundException('Module not found');
        }

        return $this->render('module/details.html.twig', [
            'module' => $module,
        ]);
    }

    /**
     * @Route("/module/{id}/luminosity", name="module_luminosity")
     */
    public function luminosityData($id, Request $request) // Affiche les données de luminosité détaillé d'un module
    {
        $entityManager = $this->getDoctrine()->getManager();
        $module = $entityManager->getRepository(Module::class)->find($id);

        if (!$module) {
            throw $this->createNotFoundException('Module introuvable');
        }

        $luminosityData = $module->getLuminosityData();

        $newLuminosityData = new LuminosityData();
        $luminosityForm = $this->createForm(LuminosityDataType::class, $newLuminosityData);

        $luminosityForm->handleRequest($request);
 
        // Si le formulaire est soumis et valide, enregistrez les données de luminosité et pousser vers la BDD
        if ($luminosityForm->isSubmitted() && $luminosityForm->isValid()) {
            $newLuminosityData->setModule($module);
            $entityManager->persist($newLuminosityData);
            $entityManager->flush();

            return $this->redirectToRoute('module_luminosity', ['id' => $id]);
        }

        // Afficher la page avec les données de luminosité, le formulaire pour ajouter des nouvelles données, et les variables pour le graphique
        return $this->render('module/luminosity_data.html.twig', [
            'module' => $module,
            'luminosityData' => $luminosityData,
            'luminosityForm' => $luminosityForm->createView(),
            'timestamps' => $this->getTimestamps($luminosityData),
            'values' => $this->getValues($luminosityData),
        ]);
    }

    private function getTimestamps($luminosityData)
    {
        $timestamps = [];
        foreach ($luminosityData as $data) {
            $timestamps[] = $data->getTimestamp()->format('Y-m-d H:i:s');
        }
        return json_encode($timestamps);
    }

    private function getValues($luminosityData)
    {
        $values = [];
        foreach ($luminosityData as $data) {
            $values[] = $data->getValue();
        }
        return json_encode($values);
    }

    /**
     * @Route("/module/{id}/generate-random-data", name="generate_random_data")
     */
    public function generateRandomData(Request $request, $id)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $module = $entityManager->getRepository(Module::class)->find($id);

        if (!$module) {
            throw $this->createNotFoundException('Module not found');
        }

        $randomValue = random_int(0, 100); // Génère une valeur aléatoire entre 0 et 100
        $randomTimestamp = new \DateTime();
        $randomTimestamp->modify('-' . random_int(1, 365) . ' days'); // Génère une date aléatoire dans les 365 derniers jours

        // Enregistrez les données de luminosité dans la BDD et push
        $luminosityData = new LuminosityData();
        $luminosityData->setModule($module);
        $luminosityData->setTimestamp($randomTimestamp);
        $luminosityData->setValue($randomValue);
        $entityManager->persist($luminosityData);
        $entityManager->flush();

        return $this->redirectToRoute('module_luminosity', ['id' => $id]);
    }

    /**
     * @Route("/module/{module_id}/luminosity/{data_id}/delete", name="delete_luminosity_data")
     */
    public function deleteLuminosityData($module_id, $data_id)
    {
        $entityManager = $this->getDoctrine()->getManager();
        
        $module = $entityManager->getRepository(Module::class)->find($module_id);
        if (!$module) {
            throw $this->createNotFoundException('Module introuvable');
        }
        
        $luminosityData = $entityManager->getRepository(LuminosityData::class)->find($data_id);
        if (!$luminosityData) {
            throw $this->createNotFoundException('Donnée de luminosité introuvable');
        }
        
        $entityManager->remove($luminosityData);
        $entityManager->flush();
        
        return $this->redirectToRoute('module_luminosity', ['id' => $module_id]);
    }

    /**
     * @Route("/module/{id}/turn-off-random", name="module_turn_off_random")
     */
    public function turnOffRandomModule($id)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $module = $entityManager->getRepository(Module::class)->find($id);

        if (!$module) {
            throw $this->createNotFoundException('Module not found');
        }

        // Générez aléatoirement l'état "Inactif" ou "Actif"
        $randomStatus = rand(0, 1) == 1 ? 'inactif' : 'actif';

        // Mettre à jour l'état du module
        $module->setStatus($randomStatus);
        $entityManager->flush();

        return $this->redirectToRoute('module_details', ['id' => $id]);
    }

}
