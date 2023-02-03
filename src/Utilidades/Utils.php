<?php

namespace App\Utilidades;

use App\Entity\ApiKey;
use App\Entity\Usuario;
use App\Repository\ApiKeyRepository;
use App\Repository\UsuarioRepository;
use DateTime;
use Doctrine\Persistence\ManagerRegistry;
use ReallySimpleJWT\Token;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\PasswordHasherFactory;
use Symfony\Component\Serializer\Context\Normalizer\ObjectNormalizerContextBuilder;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;


class Utils
{

    private ManagerRegistry $doctrine;

    public function __construct(ManagerRegistry $managerRegistry)
    {
        $this-> doctrine = $managerRegistry;
    }


    public function toJson($data): string
    {
        //Inicialización de serializador
       $encoders = [new XmlEncoder(), new JsonEncoder()];
       $normalizers = [new ObjectNormalizer()];
       $serializer = new Serializer($normalizers, $encoders);

            $json = $serializer->serialize($data, 'json');

       return $json;
   }



}