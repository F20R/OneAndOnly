<?php

namespace App\Controller;

use App\Entity\UsuarioBD;
use App\Form\Type\UsuarioFT;
use App\Repository\UsuarioBDRepository;
use App\Utilidades\Utils;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

class ApiUserController extends AbstractController
{
    #[Route('/api/user', name: 'app_api_user')]
    public function list(UsuarioBDRepository $usuarioBDRepository, Utils $utils): JsonResponse
    {
        $listUsers = $usuarioBDRepository->findAll();

        $json = $utils->toJson($listUsers);

        return  new JsonResponse($json, 200, [], true);

    }


    #[Route('/api/usercrear', name: 'app_api_user_crear')]
    public function postear(EntityManagerInterface $em, Utils $utils, HttpFoundation\Request $request)
    {
        /*
        $usuario = new UsuarioBD();
        $usuario->setNombre("EL MEJOR");
        $json = $utils->toJson($usuario);
        $em->persist($usuario);
        $em->flush();
        return new JsonResponse($json, 200, [], true);
        */

        $usuario = new UsuarioBD();
        $form = $this->createForm(UsuarioFT::class, $usuario);
        $form -> handleRequest($request);
        if($form -> isSubmitted()&& $form ->isValid()) {
            $json = $utils->toJson($usuario);
            $em->persist($usuario);
            $em->flush();
            return $json;
        }
        return new JsonResponse($form);
    }

}
