<?php

namespace App\Entity;

use App\Repository\UsuarioRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: UsuarioRepository::class)]
class Usuario implements PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['user_query'])]
    private ?int $id = null;

    #[ORM\Column(length: 150)]
    #[Groups(['user_query'])]
    private ?string $username = null;

    #[ORM\Column(length: 150)]
    #[Groups(['user_query'])]
    private ?string $password = null;

    #[ORM\OneToOne(mappedBy: 'usuario', cascade: ['persist', 'remove'])]
    private ?Perfil $perfil = null;

    #[ORM\OneToMany(mappedBy: 'usuario', targetEntity: ApiKey::class, orphanRemoval: true)]
    private Collection $apiKeys;

    #[ORM\ManyToOne(inversedBy: 'usuarios')]
    #[ORM\JoinColumn(name:'id_rol', nullable: false)]
    private ?Rol $rol = null;

    #[ORM\ManyToMany(targetEntity: Contacto::class, mappedBy: 'id_Usuario')]
    private Collection $contactos;


    #[ORM\OneToMany(mappedBy: 'id_usuario', targetEntity: Galeria::class)]
    private Collection $galerias;

    #[ORM\OneToMany(mappedBy: 'id_emisor', targetEntity: Chat::class)]
    private Collection $chats;


    public function __construct()
    {
        $this->publicaciones = new ArrayCollection();
        $this->apiKeys = new ArrayCollection();
        $this->contactos = new ArrayCollection();
        $this->conversacions = new ArrayCollection();
        $this->galerias = new ArrayCollection();
        $this->chats = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getPerfil(): ?Perfil
    {
        return $this->perfil;
    }

    public function setPerfil(Perfil $perfil): self
    {
        // set the owning side of the relation if necessary
        if ($perfil->getUsuario() !== $this) {
            $perfil->setUsuario($this);
        }

        $this->perfil = $perfil;

        return $this;
    }

    /**
     * @return Collection<int, ApiKey>
     */
    public function getApiKeys(): Collection
    {
        return $this->apiKeys;
    }

    public function addApiKey(ApiKey $apiKey): self
    {
        if (!$this->apiKeys->contains($apiKey)) {
            $this->apiKeys->add($apiKey);
            $apiKey->setUsuario($this);
        }

        return $this;
    }

    public function removeApiKey(ApiKey $apiKey): self
    {
        if ($this->apiKeys->removeElement($apiKey)) {
            // set the owning side to null (unless already changed)
            if ($apiKey->getUsuario() === $this) {
                $apiKey->setUsuario(null);
            }
        }

        return $this;
    }

    public function getRol(): ?Rol
    {
        return $this->rol;
    }

    public function setRol(?Rol $rol): self
    {
        $this->rol = $rol;

        return $this;
    }

    /**
     * @return Collection<int, Contacto>
     */
    public function getContactos(): Collection
    {
        return $this->contactos;
    }

    public function addContacto(Contacto $contacto): self
    {
        if (!$this->contactos->contains($contacto)) {
            $this->contactos->add($contacto);
            $contacto->addIdUsuario($this);
        }

        return $this;
    }

    public function removeContacto(Contacto $contacto): self
    {
        if ($this->contactos->removeElement($contacto)) {
            $contacto->removeIdUsuario($this);
        }

        return $this;
    }


    /**
     * @return Collection<int, Galeria>
     */
    public function getGalerias(): Collection
    {
        return $this->galerias;
    }

    public function addGaleria(Galeria $galeria): self
    {
        if (!$this->galerias->contains($galeria)) {
            $this->galerias->add($galeria);
            $galeria->setIdUsuario($this);
        }

        return $this;
    }

    public function removeGaleria(Galeria $galeria): self
    {
        if ($this->galerias->removeElement($galeria)) {
            // set the owning side to null (unless already changed)
            if ($galeria->getIdUsuario() === $this) {
                $galeria->setIdUsuario(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Chat>
     */
    public function getChats(): Collection
    {
        return $this->chats;
    }

    public function addChat(Chat $chat): self
    {
        if (!$this->chats->contains($chat)) {
            $this->chats->add($chat);
            $chat->setIdEmisor($this);
        }

        return $this;
    }

    public function removeChat(Chat $chat): self
    {
        if ($this->chats->removeElement($chat)) {
            // set the owning side to null (unless already changed)
            if ($chat->getIdEmisor() === $this) {
                $chat->setIdEmisor(null);
            }
        }

        return $this;
    }

}