<?php

namespace App\DTO;

class ConversacionDTO
{
    private int $id ;
    private MensajeDTO $mensajeDTO ;
    private  UserDTO $userDTO;
    private  ContactoDTO $contactoDTO;

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
     * @return MensajeDTO
     */
    public function getMensajeDTO(): MensajeDTO
    {
        return $this->mensajeDTO;
    }

    /**
     * @param MensajeDTO $mensajeDTO
     */
    public function setMensajeDTO(MensajeDTO $mensajeDTO): void
    {
        $this->mensajeDTO = $mensajeDTO;
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
     * @return ContactoDTO
     */
    public function getContactoDTO(): ContactoDTO
    {
        return $this->contactoDTO;
    }

    /**
     * @param ContactoDTO $contactoDTO
     */
    public function setContactoDTO(ContactoDTO $contactoDTO): void
    {
        $this->contactoDTO = $contactoDTO;
    }



}