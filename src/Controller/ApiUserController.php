<?php

namespace App\Controller;

use App\Repository\UsuarioBDRepository;
use App\Utilidades\Utils;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ApiUserController extends AbstractController
{
    #[Route('/api/user', name: 'app_api_user')]
    public function list(UsuarioBDRepository $usuarioBDRepository, Utils $utils): JsonResponse
    {
        $listUsers = $usuarioBDRepository->findAll();

        $json = $utils->toJson($listUsers);

        return  new JsonResponse($json, 200, [], true);

    }



}
