<?php

namespace App\DTO;

class ChatDTO
{

    private int $id;
    private  string $mensaje;
    private \DateTime $fecha  ;
    private UserDTO $emisorDTO;
    private UserDTO $receptorDTO;

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
    public function getMensaje(): string
    {
        return $this->mensaje;
    }

    /**
     * @param string $mensaje
     */
    public function setMensaje(string $mensaje): void
    {
        $this->mensaje = $mensaje;
    }

    /**
     * @return \DateTime
     */
    public function getFecha(): \DateTime
    {
        return $this->fecha;
    }

    /**
     * @param \DateTime $fecha
     */
    public function setFecha(\DateTime $fecha): void
    {
        $this->fecha = $fecha;
    }

    /**
     * @return UserDTO
     */
    public function getEmisorDTO(): UserDTO
    {
        return $this->emisorDTO;
    }

    /**
     * @param UserDTO $emisorDTO
     */
    public function setEmisorDTO(UserDTO $emisorDTO): void
    {
        $this->emisorDTO = $emisorDTO;
    }

    /**
     * @return UserDTO
     */
    public function getReceptorDTO(): UserDTO
    {
        return $this->receptorDTO;
    }

    /**
     * @param UserDTO $receptorDTO
     */
    public function setReceptorDTO(UserDTO $receptorDTO): void
    {
        $this->receptorDTO = $receptorDTO;
    }







}