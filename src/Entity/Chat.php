<?php

namespace App\Entity;

use App\Repository\ChatRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ChatRepository::class)]
class Chat
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $mensaje = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTime $fecha = null;

    #[ORM\ManyToOne(inversedBy: 'chats')]
    private ?Usuario $id_emisor = null;

    #[ORM\ManyToOne(inversedBy: 'chats')]
    private ?Usuario $id_receptor = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMensaje(): ?string
    {
        return $this->mensaje;
    }

    public function setMensaje(?string $mensaje): self
    {
        $this->mensaje = $mensaje;

        return $this;
    }

    public function getFecha(): ?\DateTimeInterface
    {
        return $this->fecha;
    }

    public function setFecha(?\DateTimeInterface $fecha): self
    {
        $this->fecha = $fecha;

        return $this;
    }

    public function getIdEmisor(): ?Usuario
    {
        return $this->id_emisor;
    }

    public function setIdEmisor(?Usuario $id_emisor): self
    {
        $this->id_emisor = $id_emisor;

        return $this;
    }

    public function getIdReceptor(): ?Usuario
    {
        return $this->id_receptor;
    }

    public function setIdReceptor(?Usuario $id_receptor): self
    {
        $this->id_receptor = $id_receptor;

        return $this;
    }
}
