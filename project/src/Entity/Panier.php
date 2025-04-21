<?php
namespace App\Entity;

use App\Repository\PanierRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity(repositoryClass: PanierRepository::class)]
class Panier
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToMany(targetEntity: LignePanier::class, mappedBy: 'panier', cascade: ['persist', 'remove'])]
    private Collection $lignesPanier;

    #[ORM\ManyToOne(inversedBy: 'paniers')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null; //n'est pas implémenté

    public function __construct()
    {
        $this->lignesPanier = new ArrayCollection();
    }

    public function getLignesPanier(): Collection
    {
        return $this->lignesPanier;
    }

    public function addLignePanier(LignePanier $lignePanier): self
    {
        if (!$this->lignesPanier->contains($lignePanier)) {
            $this->lignesPanier[] = $lignePanier;
            $lignePanier->setPanier($this);
        }
        return $this;
    }

    public function removeLignePanier(LignePanier $lignePanier): self
    {
        if ($this->lignesPanier->removeElement($lignePanier)) {
            if ($lignePanier->getPanier() === $this) {
                $lignePanier->setPanier(null);
            }
        }
        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }
}
