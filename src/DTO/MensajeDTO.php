<?php

namespace App\DTO;

class MensajeDTO
{

    private $id;
    private  $descripcion;
    private $fecha  ;
    private UserDTO $userDTO;


    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id): void
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getDescripcion()
    {
        return $this->descripcion;
    }

    /**
     * @param mixed $descripcion
     */
    public function setDescripcion($descripcion): void
    {
        $this->descripcion = $descripcion;
    }

    /**
     * @return mixed
     */
    public function getFecha()
    {
        return $this->fecha;
    }

    /**
     * @param mixed $fecha
     */
    public function setFecha($fecha): void
    {
        $this->fecha = $fecha;
    }

    /**
     * @return UserDTO
     */
    public function getUserDTO(): UserDTO
    {
        return $this->userDTO;
    }

    /**
     * @param UserDTO $userDTO
     */
    public function setUserDTO(UserDTO $userDTO): void
    {
        $this->userDTO = $userDTO;
    }






}