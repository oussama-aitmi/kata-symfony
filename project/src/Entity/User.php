<?php
namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[UniqueEntity(fields: ['email'], message: "Email est déjà utilisé")]
#[UniqueEntity(fields: ['username'], message: "Username est déjà pris")]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private ?int $id = null;

    #[ORM\Column(type: "string", length: 180, unique: true)]
    #[Assert\NotBlank(message: "Veuillez saisir votre Username")]
    private string $username;

    #[ORM\Column(type: "string", length: 100)]
    #[Assert\NotBlank(message: "Veuillez saisir votre Prénom")]
    private string $firstname;

    #[ORM\Column(type: "string", length: 180, unique: true)]
    #[Assert\NotBlank(message: "Veuillez saisir votre Email")]
    private string $email;

    #[ORM\Column(type: "string")]
    #[Assert\NotBlank(message: "Veuillez saisir votre Password")]
    #[Assert\Length(
        min: 6,
        max: 20,
        minMessage: "Mot de passe doit contenir au moins 6 caractères et 20 au max"
    )]
    private string $password;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Panier::class, orphanRemoval: true)]
    private Collection $paniers; //n'est pas implémenté

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getFirstname(): string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getPaniers(): Collection
    {
        return $this->paniers;
    }

    public function addPanier(Panier $panier): self
    {
        if (!$this->paniers->contains($panier)) {
            $this->paniers[] = $panier;
            $panier->setUser($this);
        }

        return $this;
    }

    public function removePanier(Panier $panier): self
    {
        if ($this->paniers->removeElement($panier)) {
            if ($panier->getUser() === $this) {
                $panier->setUser(null);
            }
        }

        return $this;
    }
}
