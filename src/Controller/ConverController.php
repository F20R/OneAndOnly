<?php

namespace App\Controller;

use App\DTO\ConversacionDTO;
use App\DTO\CreateUserDto;
use App\DTO\DtoConverters;
use App\DTO\UserDTO;
use App\Entity\ApiKey;
use App\Entity\Rol;
use App\Entity\Usuario;
use App\Repository\ConversacionRepository;
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

class ConverController extends AbstractController
{

    private ManagerRegistry $doctrine;

    public function __construct(ManagerRegistry $managerRegistry)
    {
        $this-> doctrine = $managerRegistry;
    }



    #[Route('/api/conversacion/list', name: 'app_conversacion_listar', methods: ['GET'])]
    #[OA\Tag(name: 'Conversacion')]
    #[Security(name: "apikey")]
    #[OA\Response(response:200,description:"successful operation" ,content: new OA\JsonContent(type: "array", items: new OA\Items(ref:new Model(type: ConversacionDTO::class))))]
    public function listar(ConversacionRepository $conversacionRepository, DtoConverters $converters, Utils $utils): JsonResponse
    {

        $listConversaciones = $conversacionRepository->findAll();

        $listJson = array();

        foreach ($listConversaciones as $conver) {
            $converDTO = $converters->converToDto($conver);
            $json = $utils->toJson($converDTO, null);
            $listJson[] = json_decode($json);
        }

        return new JsonResponse($listJson,200,[],false);

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
        $nombre = $request->query->get("nombre");

        $parametrosBusqueda = array(
            'username' => $nombre
        );

        $listUsuarios = $usuarioRepository->findBy($parametrosBusqueda);

        $listJson = array();

        foreach($listUsuarios as $user){
            $usarioDto = $converters-> usuarioToDto($user);
            $json = $utils->toJson($usarioDto,null);
            $listJson[] = json_decode($json);
        }

        return new JsonResponse($listJson, 200,[],false);
    }

    #[Route('/api/usuario/save', name: 'app_usuario_crear', methods: ['POST'])]
    #[OA\Tag(name: 'Usuarios')]
    #[OA\RequestBody(description: "Dto del usuario", required: true, content: new OA\JsonContent(ref: new Model(type:CreateUserDto::class)))]
    #[OA\Response(response: 200,description: "Usuario creado correctamente")]
    #[OA\Response(response: 101,description: "No ha indicado usario y contraseña")]
    public function save(Request $request, Utils $utils): JsonResponse
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
                $rolUser = $rolRepository->findOneByIdentificador("R_02");
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

    #[Route('/usuario/remove/{id}', name: 'app_usuario_remove', methods: ['DELETE'])]
    public function remove(UsuarioRepository $usuarioRepository, int $id):JsonResponse
    {

        $criteria = array('id' => $id);
        $usuarioEliminar = $usuarioRepository-> findBy($criteria);

        if($usuarioEliminar != null){
            $em = $this->doctrine->getManager();
            $em->remove($usuarioEliminar[0]);
            $em->flush();
            return new JsonResponse("{ mensaje: usuario eliminado correctamente}", 200, [], true);
        } else {
            return new JsonResponse("{ mensaje: No se ha encontrado el usuario}", 151, [], true);
        }


    }







}