<?php

namespace App\DTO;

use App\Entity\Perfil;
use App\Entity\Usuario;

class PerfilDto
{

    private int $id;
    private string $nombre;
    private string $apellidos;
    private int $telefono;
    private string $fechaNacimiento;
    private int $sexo;
    private string $username;


    /**
     * @param int $id
     * @param string $nombre
     * @param string $apellidos
     * @param int $telefono
     * @param string $fechaNacimiento
     * @param int $sexo
     * @param string $username
     */
    public function __construct(int $id, string $nombre, string $apellidos, int $telefono,string $fechaNacimiento, int $sexo, string $username)
    {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->apellidos = $apellidos;
        $this->telefono = $telefono;
        $this->fechaNacimiento = $fechaNacimiento;
        $this->sexo = $sexo;
        $this->username = $username;
    }


    /**
     * @param Perfil $perfil
     */
    public function perfilToDto(Perfil $perfil)
    {
        $this->id = $perfil->getId();
        $this->nombre = $perfil->getNombre();
        $this->apellidos = $perfil->getApellidos();
        $this->telefono = $perfil->getTelefono();
        $this->fechaNacimiento = $perfil->getFechaNacimiento();
        $this->sexo = $perfil->getSexo();
        $this->username = $perfil->getUsuario()->getUsername();
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
    public function getNombre(): string
    {
        return $this->nombre;
    }

    /**
     * @param string $nombre
     */
    public function setNombre(string $nombre): void
    {
        $this->nombre = $nombre;
    }

    /**
     * @return string
     */
    public function getApellidos(): string
    {
        return $this->apellidos;
    }

    /**
     * @param string $apellidos
     */
    public function setApellidos(string $apellidos): void
    {
        $this->apellidos = $apellidos;
    }

    /**
     * @return int
     */
    public function getTelefono(): int
    {
        return $this->telefono;
    }

    /**
     * @param int $telefono
     */
    public function setTelefono(int $telefono): void
    {
        $this->telefono = $telefono;
    }



    /**
     * @return string
     */
    public function getFechaNacimiento(): string
    {
        return $this->fechaNacimiento;
    }

    /**
     * @param string $fechaNacimiento
     */
    public function setFechaNacimiento(string $fechaNacimiento): void
    {
        $this->fechaNacimiento = $fechaNacimiento;
    }

    /**
     * @return int
     */
    public function getSexo(): int
    {
        return $this->sexo;
    }

    /**
     * @param int $sexo
     */
    public function setSexo(int $sexo): void
    {
        $this->sexo = $sexo;
    }

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @param string $username
     */
    public function setUsername(string $username): void
    {
        $this->username = $username;
    }



}