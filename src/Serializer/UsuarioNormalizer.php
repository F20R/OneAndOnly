<?php

namespace App\Serializer;

use App\Entity\UsuarioBD;
use Symfony\Component\Serializer\Normalizer\ContextAwareNormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class UsuarioNormalizer implements ContextAwareNormalizerInterface
{
    private $normalizer;
    public function __construct(
        ObjectNormalizer $normalizer
    ){
        $this->normalizer = $normalizer;
    }

    public function normalize($usuario, $format = null, array $context = [])
    {
        $data = $this->normalizer->normalize($usuario, $format, $context);

        $data ['message'] = 'Hola Mundo';

        return $data;
    }

    public function supportsNormalization($data, $format = null, array $context = []):bool
    {
        return $data instanceof UsuarioBD;
    }
}