<?php

namespace App\DTO;

use App\Entity\Chat;
use App\Entity\Contacto;
use App\Entity\Galeria;
use App\Entity\Mensaje;
use App\Entity\Perfil;
use App\Entity\Usuario;

class DtoConverters
{

    /**
     * @param Perfil $perfil
     */
    public function perfilToDto(Perfil $perfil):PerfilDto
    {

        $perfilDto = new PerfilDto($perfil->getId(),$perfil->getNombre(),
            $perfil->getApellidos(),$perfil->getEdad(),$perfil->getSexo(),$perfil->getUsuario()->getUsername());

        return $perfilDto;
    }


    /**
     * @param Usuario $usuario
     */
    public function usuarioToDto(Usuario $usuario):UserDTO
    {
        $usuarioDto = new UserDTO();
        $usuarioDto->setId($usuario->getId());
        $usuarioDto->setUsername($usuario->getUsername());
        $usuarioDto->setRolName($usuario->getRol()->getDescripcion());
        if($usuario->getPerfil()!=null){
            $usuarioDto->setPerfilDto($this->perfilToDto($usuario->getPerfil()));
        }

        return $usuarioDto;

    }

    public function chatToDto(Chat $chat):ChatDTO
    {
        $chatDto = new ChatDTO();
        $chatDto->setId($chat->getId());
        $chatDto->setMensaje($chat->getMensaje());
        $chatDto->setFecha($chat->getFecha());
        if($chat->getIdEmisor()!=null){
            $chatDto->setUserDTO($this->usuarioToDto($chat->getIdEmisor()));
        }
        if($chat->getIdReceptor()!=null){
            $chatDto->setUserDTO($this->usuarioToDto($chat->getIdReceptor()));
        }

        return $chatDto;

    }

    /**
     * @param Galeria $galeria
     */
    public function galeriaToDto(Galeria $galeria):GaleriaDTO
    {
        $galeriaDto = new GaleriaDTO();
        $galeriaDto->setId($galeria->getId());
        $galeriaDto->setImagen($galeria->getImagen());
        $galeriaDto->setDescripcion($galeria->getDescripcion());
        if($galeria->getIdUsuario()!=null){
            $galeriaDto->setUserDTO($this->usuarioToDto($galeria->getIdUsuario()));
        }

        return $galeriaDto;

    }

    /**
     * @param Contacto $contacto
     */

    public function contactoToDto(Contacto $contacto):ContactoDTO
    {
        $contactoDto = new ContactoDTO();
        $contactoDto->setId($contacto->getId());
        $contactoDto->setNombre($contacto->getNombre());
        $contactoDto->setNombreUsuario($contacto->getNombreUsuario());
        $contactoDto->setTelefono($contacto->getTelefono());

        return $contactoDto;

    }

    public function mensajeToDto(Mensaje $mensaje):MensajeDTO
    {
        $mensajeDto = new MensajeDTO();
        $mensajeDto->setId($mensaje->getId());
        $mensajeDto->setDescripcion($mensaje->getDescripcion());
        $mensajeDto->setFecha($mensaje->getFecha());

        return $mensajeDto;

    }

}