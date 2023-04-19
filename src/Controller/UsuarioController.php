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
use App\Repository\ApiKeyRepository;
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

class UsuarioController extends AbstractController
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



    #[Route('/api/usuario/list', name: 'app_usuario_listar', methods: ['GET'])]
    #[OA\Tag(name: 'Usuarios')]
    #[Security(name: "apikey")]
    #[OA\Response(response:200,description:"successful operation" ,content: new OA\JsonContent(type: "array", items: new OA\Items(ref:new Model(type: UserDTO::class))))]
    public function listar(UsuarioRepository $usuarioRepository, DtoConverters $converters, Utils $utils): JsonResponse
    {

        $listUsuarios = $usuarioRepository->findAll();

        $listJson = array();

        foreach ($listUsuarios as $user) {
            $userDTO = $converters->usuarioToDto($user);
            $json = $utils->toJson($userDTO, null);
            $listJson[] = json_decode($json);
        }

        return new JsonResponse($listJson,200,[],false);

    }

    #[Route('/usuario/guardar', name: 'app_usuario_guardar', methods: ['POST'])]
    public function save(UsuarioRepository $usuarioRepository,
                         ApiKeyRepository  $apiKeyRepository,
                         Request           $request,
                         Utils             $utilidades): JsonResponse
    {
        //Obtener Json del body
        $json = json_decode($request->getContent(), true);

        $token = $json['token'];
        if ($token) {
            if (!$utilidades->esApiKeyValida($token, null))
                return $this->json([
                    'message' => "El token de sesión no es válido"
                ]);
            $usuario = $apiKeyRepository->findOneBy(array('token' => $token))->getUsuario();
        } else
            $usuario = new Usuario();


        //Obtenemos los parámetros del JSON
        $rol = $json['rol'];
        $username = $json['username'];
        $password = $json['password'];

        if ($token || ($username and $password)) {

            //COMPROBAR QUE EL USUARIO O EMAIL NO EXISTEN
            if ($usuario->getUsername()!=$username) {
                if ($usuarioRepository->findOneBy(array("username" => $username)) != null)
                    return $this->json([
                        'message' => "Ya existe un usuario registrado con el username " . $username,
                    ]);
                if ($usuarioRepository->findOneBy(array("username" => $username)) != null)
                    return $this->json([
                        'message' => "Ya existe un usuario registrado con el mismo usuario " . $username,
                    ]);
            }

            //CREAR NUEVO USUARIO A PARTIR DEL JSON
            if($username) $usuario->setUsername($username);
            if($password) $usuario->setPassword($password);
            if($rol) $usuario->setRol($rol);

            //GUARDAR
            $usuarioRepository->save($usuario, true);

            if ($token) $apiKeyRepository->remove($apiKeyRepository->findOneBy(array('token'=>$token)), true);
            $Nuevotoken = $utilidades->generateApiToken($usuario, $apiKeyRepository);

            return $this->json([
                'message' => "Usuario creado correctamente",
                'token' => $Nuevotoken
            ]);
        } else {
            return $this->json([
                'message' => "Faltan datos del registro",
            ]);
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


    #[Route('/api/usuario/save', name: 'app_usuario_crear', methods: ['POST'])]
    #[OA\Tag(name: 'Usuarios')]
    #[OA\RequestBody(description: "Dto del usuario", required: true, content: new OA\JsonContent(ref: new Model(type:CreateUserDto::class)))]
    #[OA\Response(response: 200,description: "Usuario creado correctamente")]
    #[OA\Response(response: 101,description: "No ha indicado usario y contraseña")]
    public function save1(Request $request, Utils $utils): JsonResponse
    {

        //CARGA DATOS
        $em = $this-> doctrine->getManager();
        $userRepository = $em->getRepository(Usuario::class);
        $rolRepository = $em->getRepository(Rol::class);
        $apiKeyRepository = $em->getRepository(ApiKey::class);


        //Obtener Json del body y pasarlo a DTO
        $json = json_decode($request-> getContent(), true);

        //Obtenemos los parámetros del JSON
        $username = $json['username'];
        $password = $json['password'];
        $rolname = $json['rol'];


        //CREAR NUEVO USUARIO A PARTIR DEL JSON
        if($username != null and $password != null) {
            $usuarioNuevo = new Usuario();
            $usuarioNuevo->setUsername($username);
            $usuarioNuevo->setPassword($utils->hashPassword($password));



            //GESTION DEL ROL
            if ($rolname == null) {
                //Obtenemos el rol de usuario por defecto
                $rolUser = $rolRepository->findOneByIdentificador("USER");
                $usuarioNuevo->setRol($rolUser);

            } else {
                $rol = $rolRepository->findOneByIdentificador($rolname);
                $usuarioNuevo->setRol($rol);
            }

            //GUARDAR
            $userRepository->save($usuarioNuevo, true);


            $utils-> generateApiToken($usuarioNuevo,$apiKeyRepository);

            return new JsonResponse("Usuario creado correctamente", 200, [], true);
        }else{
            return new JsonResponse("No ha indicado usario y contraseña", 101, [], true);
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