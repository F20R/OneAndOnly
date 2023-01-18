<?php

namespace App\Controller;

use App\Entity\UsuarioBD;
use App\Repository\UsuarioBDRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Config\Doctrine\Orm\EntityManagerConfig;

class UsuariosCTRL extends AbstractController
{
    private $logger;

    public function __construct(LoggerInterface $logger){
        $this->logger= $logger;
    }

    /**
     * @Route("/usuarios" , name="lusuarios")
     */
    public function lista(Request $request, UsuarioBDRepository $uBD){
        $usuarios = $uBD->findAll();
        $usuarios_arrays = [];
        foreach ($usuarios as $usuario){
            $usuarios_arrays[] = [
                'id' => $usuario->getId(),
                'nombre' => $usuario->getNombre(),
                'imagen' => $usuario->getImagen()
            ];
        }
        $response = new JsonResponse();
        $response->setData([
            'success' => true,
        'data' => $usuarios_arrays
        ]);
        return $response;
    }


    /**
     * @Route("/usuario/crear" , name="crear_usuario")
     */
    public function crearUsuario(Request $request, EntityManagerInterface $em ){
        $usuario = new UsuarioBD();
        $response = new JsonResponse();
        $nombre = $request->get("nombre", null);
        if (empty($nombre)){
            $response->setData([
                'success' => false,
                'error' => 'el nombre no puede estar vacio',
                'data' => null
            ]);
            return $response;
        }
        $usuario->setNombre($nombre);
        $em->persist($usuario);
        $em->flush();
        $response->setData([
            'success' => true,
            'data' => [
                [
                    'id' => $usuario->getId(),
                    'nombre' => $usuario->getNombre()
                ]
            ]
        ]);
        return $response;

    }
}