<?php

namespace App\Entity;

use App\Repository\PerfilRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: PerfilRepository::class)]
class Perfil
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['user_query'])]
    private ?int $id = null;

    #[ORM\Column(length: 150)]
    #[Groups(['user_query'])]
    private ?string $nombre = null;

    #[ORM\Column(length: 150)]
    #[Groups(['user_query'])]
    private ?string $apellidos = null;

    #[ORM\Column]
    #[Groups(['user_query'])]
    private ?int $edad = null;

    #[ORM\Column]
    #[Groups(['user_query'])]
    private ?int $sexo = null;

    #[ORM\OneToOne(inversedBy: 'perfil', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(name: 'id_usuario',nullable: false)]
    private ?Usuario $usuario = null;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int|null $id
     */
    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string|null
     */
    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    /**
     * @param string|null $nombre
     */
    public function setNombre(?string $nombre): void
    {
        $this->nombre = $nombre;
    }

    /**
     * @return string|null
     */
    public function getApellidos(): ?string
    {
        return $this->apellidos;
    }

    /**
     * @param string|null $apellidos
     */
    public function setApellidos(?string $apellidos): void
    {
        $this->apellidos = $apellidos;
    }

    /**
     * @return int|null
     */
    public function getEdad(): ?int
    {
        return $this->edad;
    }

    /**
     * @param int|null $edad
     */
    public function setEdad(?int $edad): void
    {
        $this->edad = $edad;
    }

    /**
     * @return int|null
     */
    public function getSexo(): ?int
    {
        return $this->sexo;
    }

    /**
     * @param int|null $sexo
     */
    public function setSexo(?int $sexo): void
    {
        $this->sexo = $sexo;
    }

    /**
     * @return Usuario|null
     */
    public function getUsuario(): ?Usuario
    {
        return $this->usuario;
    }

    /**
     * @param Usuario|null $usuario
     */
    public function setUsuario(?Usuario $usuario): void
    {
        $this->usuario = $usuario;
    }




}