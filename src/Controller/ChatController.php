<?php

namespace App\Controller;

use App\DTO\ChatDTO;
use App\DTO\CreateUserDto;
use App\DTO\DtoConverters;
use App\DTO\UserDTO;
use App\Entity\ApiKey;
use App\Entity\Chat;
use App\Entity\Contacto;
use App\Entity\Rol;
use App\Entity\Usuario;
use App\Repository\ChatRepository;
use App\Repository\UsuarioRepository;
use App\Utilidades\Utils;
use Doctrine\Persistence\ManagerRegistry;
use JsonMapper;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Attributes as OA;
use ReallySimpleJWT\Token;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ChatController extends AbstractController
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

    #[Route('/api/chat/list', name: 'app_chat_listar', methods: ['GET'])]
    #[OA\Tag(name: 'Chat')]
    #[Security(name: "apikey")]
    #[OA\Response(response:200,description:"successful operation" ,content: new OA\JsonContent(type: "array", items: new OA\Items(ref:new Model(type: ChatDTO::class))))]
    public function listar(ChatRepository $chatRepository, DtoConverters $converters, Utils $utils): JsonResponse
    {

        $listChats = $chatRepository->findAll();

        $listJson = array();

        foreach ($listChats as $chat) {
            $chatDTO = $converters->chatToDto($chat);
            $json = $utils->toJson($chatDTO, null);
            $listJson[] = json_decode($json);
        }

        return new JsonResponse($listJson,200,[],false);

    }

    public function chatToJson(mixed $listaChats, DtoConverters $converters, Utils $utils): JsonResponse
    {
        $listJson = array();

        foreach ($listaChats as $chats){
            $chatDto = $converters->chatToDto($chats);
            $chatDto->setFecha($chats->getFecha()->format('Y-m-d H:i:s'));

            $json = $utils->toJson($chatDto,null);
            $listJson[] = json_decode($json, true);
        }
        return new JsonResponse($listJson,200,[], false);
    }

    #[Route('/api/chat/list/id', name: 'app_chat_listar', methods: ['GET'])]
    #[OA\Tag(name: 'Chat')]
    #[Security(name: "apikey")]
    #[OA\Response(response:200,description:"successful operation" ,content: new OA\JsonContent(type: "array", items: new OA\Items(ref:new Model(type: ChatDTO::class))))]
    public function listarPorReceptor(ChatRepository $chatRepository, Request $request,DtoConverters $converters, Utils $utils): JsonResponse
    {

        $em = $this->doctrine->getManager();
        $chatRepository = $em->getRepository(Chat::class);

        $token = $request->headers->get('token');
        $valido = $utils->esApiKeyValida($token,null);

        if (!$valido){
            return $this->json(['message' =>'El token de sesion ha caducado'], 400);
        } else {
            $id_usuario = Token::getPayload($token)["user_id"];

            $listaReceptor = $chatRepository ->findByReceptor($id_usuario);
            if ($listaReceptor){
                return $this->chatToJson($listaReceptor,$converters,$utils);
            }else{
                return $this->json(['message' =>'No hay mensaje'],400);
            }
        }

    }

    #[Route('/api/chat/list/id2', name: 'app_chat_listar', methods: ['GET'])]
    #[OA\Tag(name: 'Chat')]
    #[Security(name: "apikey")]
    #[OA\Response(response:200,description:"successful operation" ,content: new OA\JsonContent(type: "array", items: new OA\Items(ref:new Model(type: ChatDTO::class))))]
    public function listarPorEmisor(ChatRepository $chatRepository, Request $request,DtoConverters $converters, Utils $utils): JsonResponse
    {

        $em = $this->doctrine->getManager();
        $chatRepository = $em->getRepository(Chat::class);

        $token = $request->headers->get('token');
        $valido = $utils->esApiKeyValida($token,null);

        if (!$valido){
            return $this->json(['message' =>'El token de sesion ha caducado'], 400);
        } else {
            $id_usuario = Token::getPayload($token)["user_id"];

            $listaEmisor = $chatRepository ->findByEmisor($id_usuario);
            if ($listaEmisor){
                return $this->chatToJson($listaEmisor,$converters,$utils);
            }else{
                return $this->json(['message' =>'No hay mensaje'],400);
            }
        }

    }



    #[Route('/api/usuario/buscar', name: 'app_usuario_buscar', methods: ['GET'])]
    #[OA\Tag(name: 'Usuarios')]
    #[OA\Parameter(name: "nombre", description: "Nombre de usuario que vas a buscar", in: "query", required: true, schema: new OA\Schema(type: "string") )]
    #[OA\Response(response:200,description:"successful operation" ,content: new OA\JsonContent(type: "array", items: new OA\Items(ref:new Model(type: UserDTO::class))))]
    public function buscarPorNombre(UsuarioRepository $usuarioRepository,
                                    Utils $utils,
                                    Request $request,
                                    DtoConverters $converters): JsonResponse
    {

        $em = $this->doctrine->getManager();
        $usuarioRepository = $em->getRepository(Usuario::class);

        $username = $request-> query->get("username");
        $user = $usuarioRepository ->findOneBy(array("username" => $username));

        $listJson = array();

        if ($user){
            $userDTO = $converters->usuarioToDto($user);
            $json = $utils->toJson($userDTO, null);
            $listJson[] = json_decode($json);
            return new JsonResponse($listJson , 200,[], false);
        }

        return new JsonResponse($listJson,200,[],false);

    }


    #[Route('/api/chat/save', name: 'app_chat_crear', methods: ['POST'])]
    #[OA\Tag(name: 'Chat')]
    #[OA\RequestBody(description: "Dto del chat", required: true, content: new OA\JsonContent(ref: new Model(type:ChatDTO::class)))]
    public function save(Request $request, Utils $utils): JsonResponse
    {

        //CARGA DATOS
        $em = $this-> doctrine->getManager();
        $userRepository = $em->getRepository(Usuario::class);
        $apiKeyRepository = $em->getRepository(ApiKey::class);
        $chatRepository = $em->getRepository(Chat::class);


        //Obtener Json del body y pasarlo a DTO
        $json = json_decode($request-> getContent(), true);

        //Obtenemos los parámetros del JSON
        $mensaje = $json['mensaje'];
        $emisorID = $json['emisor'];
        $receptorID = $json['receptor'];


        //CREAR NUEVO USUARIO A PARTIR DEL JSON
        if($mensaje != null and $emisorID != null) {
            $chatNuevo = new Chat();
            $chatNuevo->setMensaje($mensaje);
            $now = new \DateTime("now");
            $chatNuevo->setFecha($now);



            //GESTION DEL ROL
            if ($emisorID == null) {
                //Obtenemos el rol de usuario por defecto
                $emisorUser = $userRepository->findOneByUsername("");
                $chatNuevo->setIdEmisor($emisorUser);

            } else {
                $rol = $userRepository->findOneByUsername($emisorID);
                $chatNuevo->setIdEmisor($rol);
            }

            if ($receptorID == null) {
                //Obtenemos el rol de usuario por defecto
                $receptorUser = $userRepository->findOneByUsername("");
                $chatNuevo->setIdReceptor($receptorUser);

            } else {
                $rol = $userRepository->findOneByUsername($receptorID);
                $chatNuevo->setIdReceptor($rol);
            }

            //GUARDAR
            $chatRepository->save($chatNuevo, true);

            return new JsonResponse("Mensaje enviado correctamente", 200, [], true);
        }else{
            return new JsonResponse("No ha indicado mensaje ni receptor", 101, [], true);
        }

    }

    #[Route('/api/chat/save1', name: 'app_chat_crear', methods: ['PUT'])]
    #[OA\Tag(name: 'Chat')]
    #[OA\RequestBody(description: "Dto del chat", required: true, content: new OA\JsonContent(ref: new Model(type:ChatDTO::class)))]
    public function put(Request $request, Utils $utils): JsonResponse
    {

        //CARGA DATOS
        $em = $this-> doctrine->getManager();
        $userRepository = $em->getRepository(Usuario::class);
        $apiKeyRepository = $em->getRepository(ApiKey::class);
        $chatRepository = $em->getRepository(Chat::class);


        //Obtener Json del body y pasarlo a DTO
        $json = json_decode($request-> getContent(), true);

        //Obtenemos los parámetros del JSON
        $mensaje = $json['mensaje'];
        $emisorID = $json['emisor'];
        $receptorID = $json['receptor'];


        //CREAR NUEVO USUARIO A PARTIR DEL JSON
        if($mensaje != null and $emisorID != null) {
            $chatNuevo = new Chat();
            $chatNuevo->setMensaje($mensaje);
            $now = new \DateTime("now");
            $chatNuevo->setFecha($now);



            //GESTION DEL ROL
            if ($emisorID == null) {
                //Obtenemos el rol de usuario por defecto
                $emisorUser = $userRepository->findOneByUsername("");
                $chatNuevo->setIdEmisor($emisorUser);

            } else {
                $rol = $userRepository->findOneByUsername($emisorID);
                $chatNuevo->setIdEmisor($rol);
            }

            if ($receptorID == null) {
                //Obtenemos el rol de usuario por defecto
                $receptorUser = $userRepository->findOneByUsername("");
                $chatNuevo->setIdReceptor($receptorUser);

            } else {
                $rol = $userRepository->findOneByUsername($receptorID);
                $chatNuevo->setIdReceptor($rol);
            }

            //GUARDAR
            $chatRepository->save($chatNuevo, true);

            return new JsonResponse("Mensaje enviado correctamente", 200, [], true);
        }else{
            return new JsonResponse("No ha indicado mensaje ni receptor", 101, [], true);
        }

    }

    #[Route('/api/usuario/delete', name: 'app_usuario_delete', methods: ['GET'])]
    #[OA\Tag(name: 'Usuarios')]
    public function eliminar(Request $request): JsonResponse
    {

        //CARGA DATOS
        $em = $this-> doctrine->getManager();
        $userRepository = $em->getRepository(Usuario::class);
        $apiKeyRepository = $em->getRepository(ApiKey::class);



        //Obtener Json del body y pasarlo a DTO
        $json = json_decode($request-> getContent(), true);
        $userid = $json['username'];
        $usuario = $userRepository-> findOneBy(array('username' =>$userid));
        $apikey = $apiKeyRepository->findOneBy(array('usuario' => $usuario));


        //CREAR NUEVO USUARIO A PARTIR DEL JSON
        if($usuario != null) {
            if ($userid == $usuario->getUsername()){
                $apiKeyRepository -> remove($apikey , true);
                $userRepository -> remove($usuario , true);
                return new JsonResponse("{mensaje : Usuario eliminado correctamente }", 200, [], true);
            }
        }

        return new JsonResponse("{mensaje : El usuario que intenta eliminar no existe }", 409,[], true);

    }


}