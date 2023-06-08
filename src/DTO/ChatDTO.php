<?php

namespace App\DTO;

class ChatDTO
{

    private int $id;
    private  string $mensaje;
    private string $fecha  ;
    private bool $es_mio;
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
     * @return string
     */
    public function getFecha(): string
    {
        return $this->fecha;
    }

    /**
     * @param string $fecha
     */
    public function setFecha(string $fecha): void
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

    /**
     * @return bool
     */
    public function isEsMio(): bool
    {
        return $this->es_mio;
    }

    /**
     * @param bool $es_mio
     */
    public function setEsMio(bool $es_mio): void
    {
        $this->es_mio = $es_mio;
    }
    





}