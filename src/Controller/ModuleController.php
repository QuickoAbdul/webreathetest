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
        

class ModuleController extends AbstractController
{
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
            $luminosityData->setTimestamp(new \DateTime());
            $luminosityData->setValue(random_int(0, 100)); // Valeur aléatoire pour la luminosité
            $entityManager->persist($luminosityData);
            $entityManager->flush();

            return $this->redirectToRoute('accueil'); // redirigez où vous voulez
        }

        return $this->render('module/form.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    

    public function listModules()
    {
        $modules = $this->getDoctrine()->getRepository(Module::class)->findAll();

        return $this->render('module/list.html.twig', [
            'modules' => $modules,
        ]);
    }

    public function moduleDetails($id)
    {
        $module = $this->getDoctrine()->getRepository(Module::class)->find($id);

        if (!$module) {
            throw $this->createNotFoundException('Module not found');
        }

        return $this->render('module/details.html.twig', [
            'module' => $module,
        ]);
    }

    public function luminosityData($id, Request $request)
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

    if ($luminosityForm->isSubmitted() && $luminosityForm->isValid()) {
        $newLuminosityData->setModule($module);
        $newLuminosityData->setTimestamp(new \DateTime()); // Utilisez l'heure actuelle
        $entityManager->persist($newLuminosityData);
        $entityManager->flush();

        return $this->redirectToRoute('module_luminosity', ['id' => $id]);
    }

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
    public function showChart($id)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $module = $entityManager->getRepository(Module::class)->find($id);

        if (!$module) {
            throw $this->createNotFoundException('Module introuvable');
        }

        $luminosityData = $module->getLuminosityData();

        $timestamps = [];
        $values = [];

        foreach ($luminosityData as $data) {
            $timestamps[] = $data->getTimestamp()->format('Y-m-d H:i:s');
            $values[] = $data->getValue();
        }

        return $this->render('module/chart.html.twig', [
            'module' => $module,
            'timestamps' => json_encode($timestamps),
            'values' => json_encode($values),
        ]);
    }

    
}
