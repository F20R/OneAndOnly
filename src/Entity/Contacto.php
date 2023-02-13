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

    #[ORM\ManyToMany(targetEntity: Usuario::class, inversedBy: 'contactos')]
    private Collection $id_Usuario;

    #[ORM\OneToMany(mappedBy: 'id_contacto', targetEntity: Conversacion::class)]
    private Collection $conversacions;

    public function __construct()
    {
        $this->id_Usuario = new ArrayCollection();
        $this->conversacions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    public function setNombre(?string $nombre): self
    {
        $this->nombre = $nombre;

        return $this;
    }

    public function getNombreUsuario(): ?string
    {
        return $this->nombreUsuario;
    }

    public function setNombreUsuario(?string $nombreUsuario): self
    {
        $this->nombreUsuario = $nombreUsuario;

        return $this;
    }

    /**
     * @return Collection<int, Usuario>
     */
    public function getIdUsuario(): Collection
    {
        return $this->id_Usuario;
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
}
