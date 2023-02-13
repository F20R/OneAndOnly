<?php

namespace App\Controller;

use App\DTO\ContactoDTO;
use App\DTO\CreateUserDto;
use App\DTO\DtoConverters;
use App\DTO\UserDTO;
use App\Entity\ApiKey;
use App\Entity\Contacto;
use App\Entity\Rol;
use App\Entity\Usuario;
use App\Repository\ContactoRepository;
use App\Repository\UsuarioRepository;
use App\Utilidades\Utils;
use Doctrine\Persistence\ManagerRegistry;
use JsonMapper;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ContactoController extends AbstractController
{

    private ManagerRegistry $doctrine;

    public function __construct(ManagerRegistry $managerRegistry)
    {
        $this-> doctrine = $managerRegistry;
    }


    #[OA\Tag(name: 'Usuarios')]
    #[Route('/api/usuario', name: 'app_usuario', methods: ["GET"])]
    public function index(): JsonResponse
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/UsuarioController.php',
        ]);
    }

    #[Route('/api/contacto/list', name: 'app_contacto_listar', methods: ['GET'])]
    #[OA\Tag(name: 'Contactos')]
    #[Security(name: "apikey")]
    #[OA\Response(response:200,description:"successful operation" ,content: new OA\JsonContent(type: "array", items: new OA\Items(ref:new Model(type: ContactoDTO::class))))]
    public function listar(ContactoRepository $contactoRepository, DtoConverters $converters, Utils $utils, Request $request): JsonResponse
    {

        if($utils->comprobarPermisos($request, "USER")){
            $listContactos= $contactoRepository->findAll();

            $listJson = array();

            foreach($listContactos as $user){
                $usarioDto = $converters-> usuarioToDto($user);
                $json = $utils->toJson($usarioDto,null);
                $listJson[] = json_decode($json);
            }


            return new JsonResponse($listJson, 200,[],false);
        }else{
            return new JsonResponse("{ message: Unauthorized}", 401,[],false);

        }




    }




    #[Route('/api/contacto/save', name: 'app_contacto_crear', methods: ['POST'])]
    #[OA\Tag(name: 'Contactos')]
    #[OA\RequestBody(description: "Dto del contacto", required: true, content: new OA\JsonContent(ref: new Model(type:ContactoDTO::class)))]
    #[OA\Response(response: 200,description: "Contacto creado correctamente")]
    #[OA\Response(response: 101,description: "No ha indicado nombre de usuario")]
    public function save(Request $request, Utils $utils): JsonResponse
    {

        //CARGA DATOS
        $em = $this-> doctrine->getManager();
        $contactoRepository = $em->getRepository(Contacto::class);


        //Obtener Json del body y pasarlo a DTO
        $json = json_decode($request-> getContent(), true);

        //Obtenemos los parámetros del JSON
        $nombre = $json['nombre'];
        $nombreUsuario = $json['nombreUsuario'];


        //CREAR NUEVO USUARIO A PARTIR DEL JSON
        if($nombre != null and $nombreUsuario != null) {
            $contactoNuevo = new Contacto();
            $contactoNuevo->setNombre($nombre);
            $contactoNuevo->setNombreUsuario($nombreUsuario);



            //GUARDAR
            $contactoRepository->save($contactoNuevo, true);



            return new JsonResponse("Contacto creado correctamente", 200, [], true);
        }else{
            return new JsonResponse("No ha indicado nombre de usuario", 101, [], true);
        }

    }







}