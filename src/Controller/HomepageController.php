<?php

namespace App\Controller;

use App\Entity\Characters;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;

class HomepageController extends AbstractController
{
    #[Route('/homepage', name: 'app_homepage')]
    public function index(ManagerRegistry $doctrine): JsonResponse
    {
        $repository = $doctrine->getRepository(Characters::class);
        $characters = $repository->findAll();
        $response=[];
        foreach($characters as $character) {
            $response[]=[
                'name'=>$character->getName(),
                'mass'=>$character->getMass(),
                'height'=>$character->getHeight(),
                'genter'=>$character->getGender(),
                //'picture'=>$character->getPicture()
            ];
        }
        return $this->json($response);
    }
}
