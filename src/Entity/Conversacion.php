<?php

namespace App\Entity;

use App\Repository\ConversacionRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ConversacionRepository::class)]
class Conversacion
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'conversacions')]
    private ?Usuario $id_usuario = null;

    #[ORM\ManyToOne(inversedBy: 'conversacions')]
    private ?Contacto $id_contacto = null;

    #[ORM\ManyToOne(inversedBy: 'conversacions')]
    private ?Mensaje $id_mensaje = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdUsuario(): ?Usuario
    {
        return $this->id_usuario;
    }

    public function setIdUsuario(?Usuario $id_usuario): self
    {
        $this->id_usuario = $id_usuario;

        return $this;
    }

    public function getIdContacto(): ?Contacto
    {
        return $this->id_contacto;
    }

    public function setIdContacto(?Contacto $id_contacto): self
    {
        $this->id_contacto = $id_contacto;

        return $this;
    }

    /**
     * @return Mensaje|null
     */
    public function getIdMensaje(): ?Mensaje
    {
        return $this->id_mensaje;
    }

    /**
     * @param Mensaje|null $id_mensaje
     */
    public function setIdMensaje(?Mensaje $id_mensaje): void
    {
        $this->id_mensaje = $id_mensaje;
    }





}
