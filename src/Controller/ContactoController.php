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


        $listContactos= $contactoRepository->findAll();

        $listJson = array();

        foreach ($listContactos as $contacto) {
            $contactoDto = $converters->contactoToDto($contacto);
            $json = $utils->toJson($contactoDto, null);
            $listJson[] = json_decode($json);
        }

        return new JsonResponse($listJson,200,[],false);




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
        $userRepository = $em->getRepository(Usuario::class);


        //Obtener Json del body y pasarlo a DTO
        $json = json_decode($request-> getContent(), true);

        //Obtenemos los parámetros del JSON
        $nombre = $json['nombre'];
        $nombreUsuario = $json['nombreUsuario'];
        $telefono = $json['telefono'];
        $usuario = $json['usuario'];


        //CREAR NUEVO USUARIO A PARTIR DEL JSON
        if($nombre != null and $nombreUsuario != null) {
            $contactoNuevo = new Contacto();
            $contactoNuevo->setNombre($nombre);
            $contactoNuevo->setNombreUsuario($nombreUsuario);
            $contactoNuevo->setTelefono($telefono);

            //GESTION DEL ROL
            if ($nombre == null) {
                //Obtenemos el rol de usuario por defecto
                $contactoUser = $userRepository->findOneByUsername("");
                $contactoNuevo->setIdUsuario($contactoUser);

            } else {
                $usuario1 = $userRepository->findOneByUsername($usuario);
                $contactoNuevo->setIdUsuario($usuario1);
            }


            //GUARDAR
            $contactoRepository->save($contactoNuevo, true);


            return new JsonResponse("Contacto creado correctamente", 200, [], true);
        }else{
            return new JsonResponse("No ha indicado nombre y apellidos", 101, [], true);
        }

    }


    #[Route('/api/contacto/delete', name: 'app_contacto_delete', methods: ['GET'])]
    #[OA\Tag(name: 'Usuarios')]
    public function eliminar(Request $request): JsonResponse
    {

        //CARGA DATOS
        $em = $this-> doctrine->getManager();
        $contactoRepository = $em->getRepository(Contacto::class);
        $apiKeyRepository = $em->getRepository(ApiKey::class);



        //Obtener Json del body y pasarlo a DTO
        $json = json_decode($request-> getContent(), true);
        $contactoid = $json['id'];
        $contacto = $contactoRepository-> findOneBy(array('id' =>$contactoid));
        $apikey = $apiKeyRepository->findOneBy(array('usuario' => $contacto));


        //CREAR NUEVO USUARIO A PARTIR DEL JSON
        if($contacto != null) {
            if ($contactoid == $contacto->getId()){
                $apiKeyRepository -> remove($apikey , true);
                $contactoRepository -> remove($contacto , true);
                return new JsonResponse("{mensaje : Contacto eliminado correctamente }", 200, [], true);
            }
        }

        return new JsonResponse("{mensaje : El contacto que intenta eliminar no existe }", 409,[], true);

    }


}