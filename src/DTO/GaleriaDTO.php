<?php

namespace App\DTO;

class GaleriaDTO
{

    private int $id;
    private string $imagen;
    private string $descripcion;
    private UserDTO $userDTO;



    public function __construct()
    {
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getImagen(): string
    {
        return $this->imagen;
    }

    /**
     * @param string $imagen
     */
    public function setImagen(string $imagen): void
    {
        $this->imagen = $imagen;
    }

    /**
     * @return string
     */
    public function getDescripcion(): string
    {
        return $this->descripcion;
    }

    /**
     * @param string $descripcion
     */
    public function setDescripcion(string $descripcion): void
    {
        $this->descripcion = $descripcion;
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

    /**
     * @return string
     */





}
