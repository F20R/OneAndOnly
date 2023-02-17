<?php

namespace App\DTO;

use App\Entity\Contacto;
use App\Entity\Galeria;
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

        return $contactoDto;

    }

}