<?php

namespace App\Controller;

use App\Entity\ApiKey;
use App\Entity\Contacto;
use App\Entity\Mensaje;
use App\Repository\MensajeRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;



class MensajeController extends AbstractController
{

    public function __construct(private ManagerRegistry $doctrine) {}



    #[Route('/mensaje', name: 'app_mensaje')]
    public function index(): JsonResponse
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/MensajeController.php',
        ]);
    }

    #[Route('/mensaje/list', name: 'app_mensaje_listar')]
    public function listar(Request $request, MensajeRepository $mensajeRepository): JsonResponse
    {
        $listMensajes = $mensajeRepository->findAll();

        $listJson = $this->toJson($listMensajes);

        return new JsonResponse($listJson, 200, [], true);

    }


    #[Route('/mensaje/save', name: 'app_mensaje_save', methods: ['POST'])]
    public function save(Request $request):JsonResponse
    {
        $json = json_decode($request->getContent(), true);

        $mensaje = new Mensaje();
        $mensaje->setDescripcion($json['descripcion']);
        $now = new \DateTime("now");
        $mensaje->setFecha($now);

        $em = $this->doctrine->getManager();
        $em->persist($mensaje);
        $em->flush();

        return new JsonResponse("{ mensaje: Mensaje creado correctamente}", 200, [], true);

    }

    #[Route('/api/mensaje/delete', name: 'app_mensaje_delete', methods: ['GET'])]
    #[OA\Tag(name: 'Mensajes')]
    public function eliminar(Request $request): JsonResponse
    {

        //CARGA DATOS
        $em = $this-> doctrine->getManager();
        $mensajeRepository = $em->getRepository(Mensaje::class);




        //Obtener Json del body y pasarlo a DTO
        $json = json_decode($request-> getContent(), true);
        $mensajeid = $json['id'];
        $mensaje = $mensajeRepository-> findOneBy(array('id' =>$mensajeid));


        //CREAR NUEVO USUARIO A PARTIR DEL JSON
        if($mensaje != null) {
            if ($mensajeid == $mensaje->getId()){
                $mensajeRepository -> remove($mensaje , true);
                return new JsonResponse("{mensaje : Mensaje eliminado correctamente }", 200, [], true);
            }
        }

        return new JsonResponse("{mensaje : El mensaje que intenta eliminar no existe }", 409,[], true);

    }


    public function toJson($data): string
    {
        //Inicialización de serializador
        $encoders = [new XmlEncoder(), new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizers, $encoders);

        //Conversion a JSON
        $json = $serializer->serialize($data, 'json');

        return $json;
    }

}