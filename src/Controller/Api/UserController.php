<?php


namespace App\Controller\Api;

use League\Flysystem\FilesystemOperator;
use App\Entity\UsuarioBD;
use App\Form\Model\UsuarioDto;
use App\Form\Type\UsuarioFT;
use App\Repository\UsuarioBDRepository;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;

class UserController extends AbstractFOSRestController{

    /**
     * @Rest\Get(path="api/usuarios")
     * @Rest\View(serializerGroups={"usuario"}, serializerEnableMaxDepthChecks=true)
     */
    public function getActions(UsuarioBDRepository $uBD){
        return $uBD->findAll();
    }


    /**
     * @Rest\Post(path="api/usu")
     * @Rest\View(serializerGroups={"usuario"}, serializerEnableMaxDepthChecks=true)
     */

    public function crearUsuario(EntityManagerInterface $em, Request $request, FilesystemOperator $defaultStorage ){
        $usuarioDto = new UsuarioDto(); //BD A DTO (solo en el "new")
        $form = $this->createForm(UsuarioFT::class, $usuarioDto);
        $form -> handleRequest($request);
        if($form -> isSubmitted()&& $form ->isValid()) {
            $extension = explode('/', mime_content_type($usuarioDto->base64Image))[1];
            $data = explode(',', $usuarioDto->base64Image);
            $filename = sprintf('/%s.%s', uniqid('usuario_', true), $extension);
            $defaultStorage->write($filename, base64_decode($data[1]));
            $usuario = new UsuarioBD();
            $usuario ->setNombre($usuarioDto->nombre);
            $usuario ->setImagen($filename);
            $em->persist($usuario);
            $em->flush();
            return $usuario;
        }
        return $form;
    }
}


/*
 *   $usuario = new UsuarioBD();
        $form = $this->createForm(UsuarioFT::class, $usuario);
        $form -> handleRequest($request);
        if($form -> isSubmitted()&& $form ->isValid()) {
            $em->persist($usuario);
            $em->flush();
            return $usuario;
        }
        return $form;
 */