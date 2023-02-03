<?php

namespace App\Controller;

use App\DTO\CreateUserDto;
use App\Dto\LoginDto;
use App\Entity\ApiKey;
use App\Entity\Perfil;
use App\Entity\Rol;
use App\Entity\Usuario;
use App\Utilidades\Utils;
use Doctrine\Persistence\ManagerRegistry;
use Nelmio\ApiDocBundle\Annotation\Model;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use OpenApi\Attributes as OA;

class RegistroController extends AbstractController
{

    private ManagerRegistry $doctrine;

    public function __construct(ManagerRegistry $managerRegistry)
    {
        $this-> doctrine = $managerRegistry;
    }

    #[Route('/usuario/guardar', name: 'app_usuario_crear', methods: ['POST'])]
    #[OA\Tag(name: 'Register')]
    #[OA\RequestBody(description: "Dto de autentificación", content: new OA\JsonContent(ref: new Model(type: CreateUserDto::class)))]
    public function crear(Request $request): JsonResponse
    {

        //Obtener Json del body
        $json = json_decode($request->getContent(), true);



        //CREAR NUEVO USUARIO A PARTIR DEL JSON
        $usuarioNuevo = new Perfil();
        $usuarioNuevo->setNombre($json['nombre']);
        $usuarioNuevo->setApellidos($json['apellidos']);
        $usuarioNuevo-> setFechaNacimiento((date_create_from_format('Y/d/m H:i:s',$json['fecha_nacimiento'])));
        $usuarioNuevo-> setUsuario();
        $usuarioNuevo-> setGenero($json['genero']);
        $usuarioNuevo-> setCorreo($json['correo']);
        $usuarioNuevo-> setImagen($json['imagen']);

        //GUARDAR
        $em = $this->doctrine->getManager();
        $em->persist($usuarioNuevo);
        $em->flush();

        return new JsonResponse("{ mensaje: Usuario creado correctamente }", 200, [], true);
    }
}