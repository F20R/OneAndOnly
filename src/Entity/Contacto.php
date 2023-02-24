<?php

namespace App\Entity;

use App\Repository\ContactoRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ContactoRepository::class)]
class Contacto
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 150, nullable: true)]
    private ?string $nombre = null;

    #[ORM\Column(length: 250, nullable: true)]
    private ?string $nombreUsuario = null;

    #[ORM\Column(length: 250, nullable: true)]
    private ?string $telefono = null;

    #[ORM\ManyToOne(inversedBy: 'contacto')]
    private ?Usuario $id_usuario = null;


    public function __construct()
    {
        $this->id_Usuario = new ArrayCollection();
        $this->conversacions = new ArrayCollection();
    }

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
    public function getNombreUsuario(): ?string
    {
        return $this->nombreUsuario;
    }

    /**
     * @param string|null $nombreUsuario
     */
    public function setNombreUsuario(?string $nombreUsuario): void
    {
        $this->nombreUsuario = $nombreUsuario;
    }

    /**
     * @return Usuario|null
     */
    public function getIdUsuario(): ?Usuario
    {
        return $this->id_usuario;
    }

    /**
     * @param Usuario|null $id_usuario
     */
    public function setIdUsuario(?Usuario $id_usuario): void
    {
        $this->id_usuario = $id_usuario;
    }



    public function addIdUsuario(Usuario $idUsuario): self
    {
        if (!$this->id_Usuario->contains($idUsuario)) {
            $this->id_Usuario->add($idUsuario);
        }

        return $this;
    }

    public function removeIdUsuario(Usuario $idUsuario): self
    {
        $this->id_Usuario->removeElement($idUsuario);

        return $this;
    }

    /**
     * @return Collection<int, Conversacion>
     */
    public function getConversacions(): Collection
    {
        return $this->conversacions;
    }

    public function addConversacion(Conversacion $conversacion): self
    {
        if (!$this->conversacions->contains($conversacion)) {
            $this->conversacions->add($conversacion);
            $conversacion->setIdContacto($this);
        }

        return $this;
    }

    public function removeConversacion(Conversacion $conversacion): self
    {
        if ($this->conversacions->removeElement($conversacion)) {
            // set the owning side to null (unless already changed)
            if ($conversacion->getIdContacto() === $this) {
                $conversacion->setIdContacto(null);
            }
        }

        return $this;
    }

    /**
     * @return string|null
     */
    public function getTelefono(): ?string
    {
        return $this->telefono;
    }

    /**
     * @param string|null $telefono
     */
    public function setTelefono(?string $telefono): void
    {
        $this->telefono = $telefono;
    }


}
