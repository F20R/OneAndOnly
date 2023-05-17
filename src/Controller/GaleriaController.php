<?php

namespace App\Controller;

use App\DTO\CreateUserDto;
use App\DTO\DtoConverters;
use App\DTO\GaleriaDTO;
use App\DTO\UserDTO;
use App\Entity\ApiKey;
use App\Entity\Contacto;
use App\Entity\Galeria;
use App\Entity\Rol;
use App\Entity\Usuario;
use App\Repository\ContactoRepository;
use App\Repository\GaleriaRepository;
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

class GaleriaController extends AbstractController
{

    private ManagerRegistry $doctrine;

    public function __construct(ManagerRegistry $managerRegistry)
    {
        $this-> doctrine = $managerRegistry;
    }



    #[Route('/api/galeria/list', name: 'app_galeria_listar', methods: ['GET'])]
    #[OA\Tag(name: 'Galeria')]
    #[Security(name: "apikey")]
    #[OA\Response(response:200,description:"successful operation" ,content: new OA\JsonContent(type: "array", items: new OA\Items(ref:new Model(type: GaleriaDTO::class))))]
    public function listar(GaleriaRepository $galeriaRepository, DtoConverters $converters, Utils $utils): JsonResponse
    {

        $listGaleria = $galeriaRepository->findAll();

        $listJson = array();

        foreach ($listGaleria as $galeria) {
            $galeriaDTO = $converters->galeriaToDto($galeria);
            $json = $utils->toJson($galeriaDTO, null);
            $listJson[] = json_decode($json);
        }

        return new JsonResponse($listJson,200,[],false);

    }

    #[Route('/api/galeria/list/id', name: 'app_chat_listar', methods: ['GET'])]
    #[OA\Tag(name: 'Chat')]
    #[Security(name: "apikey")]
    #[OA\Response(response:200,description:"successful operation" ,content: new OA\JsonContent(type: "array", items: new OA\Items(ref:new Model(type: GaleriaDTO::class))))]
    public function listarPorUsuario(ContactoRepository $contactoRepository, Request $request,DtoConverters $converters, Utils $utils): JsonResponse
    {

        $em = $this->doctrine->getManager();
        $galeriaRepository = $em->getRepository(Galeria::class);

        $token = $request->headers->get('token');
        $valido = $utils->esApiKeyValida($token,null);

        if (!$valido){
            return $this->json(['message' =>'El token de sesion ha caducado'], 400);
        } else {
            $id_usuario = Token::getPayload($token)["user_id"];

            $listaUsuario = $galeriaRepository ->findByUsuario($id_usuario);
            if ($listaUsuario){
                return $this->galeriaToJson($listaUsuario,$converters,$utils);
            }else{
                return $this->json(['message' =>'Imagen no existe'],400);
            }
        }

    }

    public function galeriaToJson(mixed $listaImagenes, DtoConverters $converters, Utils $utils): JsonResponse
    {
        $listJson = array();

        foreach ($listaImagenes as $imagenes){
            $galeriaDto = $converters->galeriaToDto($imagenes);

            $json = $utils->toJson($galeriaDto,null);
            $listJson[] = json_decode($json, true);
        }
        return new JsonResponse($listJson,200,[], false);
    }



    #[Route('/api/galeria/save', name: 'app_galeria_crear', methods: ['POST'])]
    #[OA\Tag(name: 'Galeria')]
    #[OA\RequestBody(description: "Dto de Galeria", required: true, content: new OA\JsonContent(ref: new Model(type:GaleriaDTO::class)))]
    public function save(Request $request, Utils $utils): JsonResponse
    {

        //CARGA DATOS
        $em = $this-> doctrine->getManager();
        $userRepository = $em->getRepository(Usuario::class);
        $galeriaRepository = $em->getRepository(Galeria::class);


        //Obtener Json del body y pasarlo a DTO
        $json = json_decode($request-> getContent(), true);

        //Obtenemos los parámetros del JSON
        $imagen = $json['url'];
        $descripcion = $json['descripcion'];
        $usuario = $json['usuario'];


        //CREAR NUEVO USUARIO A PARTIR DEL JSON
        if($imagen != null and $descripcion != null) {
            $galeriaNuevo = new Galeria();
            $galeriaNuevo->setImagen($imagen);
            $galeriaNuevo->setDescripcion($descripcion);



            //GESTION DEL ROL
            if ($usuario == null) {
                //Obtenemos el rol de usuario por defecto
                $galeriaUser = $userRepository->findOneByUsername("");
                $galeriaNuevo->setIdUsuario($galeriaUser);

            } else {
                $usuario = $userRepository->findOneByUsername($usuario);
                $galeriaNuevo->setIdUsuario($usuario);
            }

            //GUARDAR
            $galeriaRepository->save($galeriaNuevo, true);


            return new JsonResponse("Imagen creada correctamente", 200, [], true);
        }else{
            return new JsonResponse("No ha indicado imagen y descripción", 101, [], true);
        }

    }
    #[Route('/api/galeria/delete', name: 'app_galeria_delete', methods: ['GET'])]
    #[OA\Tag(name: 'Galeria')]
    public function eliminar(Request $request): JsonResponse
    {

        //CARGA DATOS
        $em = $this-> doctrine->getManager();
        $galeriaRepository = $em->getRepository(Galeria::class);
        $apiKeyRepository = $em->getRepository(ApiKey::class);



        //Obtener Json del body y pasarlo a DTO
        $json = json_decode($request-> getContent(), true);
        $imagenid = $json['id'];
        $galeria = $galeriaRepository-> findOneBy(array('id' =>$imagenid));
        $apikey = $apiKeyRepository->findOneBy(array('usuario' => $galeria));


        //CREAR NUEVO USUARIO A PARTIR DEL JSON
        if($galeria != null) {
            if ($imagenid == $galeria->getId()){
                $apiKeyRepository -> remove($apikey , true);
                $galeriaRepository -> remove($galeria , true);
                return new JsonResponse("{mensaje : Imagen eliminada correctamente }", 200, [], true);
            }
        }

        return new JsonResponse("{mensaje : La imagen que intenta eliminar no existe }", 409,[], true);

    }



}