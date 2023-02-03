<?php

namespace App\Controller;


use App\Repository\UsuarioRepository;
use App\Utilidades\Utils;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use App\DTO\DtoConverters;
use App\DTO\UserDto;

use Nelmio\ApiDocBundle\Annotation\Model;

use OpenApi\Attributes as OA;

use Symfony\Component\HttpFoundation\Request;


class ApiUserController extends AbstractController
{
    #[Route('/api/user', name: 'app_api_user')]
    public function list(UsuarioRepository $usuarioRepository, Utils $utils): JsonResponse
    {
        $listUsers = $usuarioRepository->findAll();

        $json = $utils->toJson($listUsers);

        return new JsonResponse($json, 200, [], true);

    }


}
