<?php

namespace App\Controller;

use App\DTO\CreateUserDto;
use App\DTO\PerfilDto;
use App\Entity\ApiKey;
use App\Entity\Contacto;
use App\Entity\Perfil;
use App\Entity\Rol;
use App\Entity\Usuario;
use App\Utilidades\Utils;
use Doctrine\Persistence\ManagerRegistry;
use Nelmio\ApiDocBundle\Annotation\Model;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Attributes as OA;

class PerfilController extends AbstractController
{

    private ManagerRegistry $doctrine;

    public function __construct(ManagerRegistry $managerRegistry)
    {
        $this-> doctrine = $managerRegistry;
    }


    #[Route('/perfil', name: 'app_perfil')]
    public function index(): JsonResponse
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/PerfilController.php',
        ]);
    }


    #[Route('/api/perfil/save', name: 'app_perfil_crear', methods: ['POST'])]
    #[OA\Tag(name: 'Perfil')]
    #[OA\RequestBody(description: "Dto del perfil", required: true, content: new OA\JsonContent(ref: new Model(type:PerfilDto::class)))]
    #[OA\Response(response: 200,description: "Perfil creado correctamente")]
    #[OA\Response(response: 101,description: "No ha indicado nombre y apellidos")]
    public function save(Request $request, Utils $utils): JsonResponse
    {


        //CARGA DATOS
        $em = $this-> doctrine->getManager();
        $userRepository = $em->getRepository(Usuario::class);
        $perfilRepository = $em->getRepository(Perfil::class);



        //Obtener Json del body y pasarlo a DTO
        $json = json_decode($request-> getContent(), true);

        //Obtenemos los parámetros del JSON
        $nombre = $json['nombre'];
        $apellidos = $json['apellidos'];
        $edad = $json['edad'];
        $sexo = $json['sexo'];
        $usuario = $json['usuario'];


        //CREAR NUEVO USUARIO A PARTIR DEL JSON
        if($nombre != null and $apellidos != null) {
            $perfilNuevo = new Perfil();
            $perfilNuevo->setNombre($nombre);
            $perfilNuevo->setApellidos($apellidos);
            $perfilNuevo->setEdad($edad);
            $perfilNuevo->setSexo($sexo);


            //GESTION DEL ROL
            if ($usuario == null) {
                //Obtenemos el rol de usuario por defecto
                $perfilUser = $userRepository->findOneByUsername("antoniogp");
                $perfilNuevo->setUsuario($perfilUser);

            } else {
                $usuario1 = $userRepository->findOneByUsername($usuario);
                $perfilNuevo->setUsuario($usuario1);
            }


            //GUARDAR
            $perfilRepository->save($perfilNuevo, true);


            return new JsonResponse("Perfil creado correctamente", 200, [], true);
        }else{
            return new JsonResponse("No ha indicado nombre y apellidos", 101, [], true);
        }

    }


}